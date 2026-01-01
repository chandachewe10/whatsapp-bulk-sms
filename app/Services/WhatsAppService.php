<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl = 'https://graph.facebook.com/v22.0';
    protected string $accessToken;
    protected string $phoneNumberId;
    protected ?string $businessAccountId;

    public function __construct(string $phoneNumberId, string $accessToken, ?string $businessAccountId = null)
    {
        $this->phoneNumberId = $phoneNumberId;
        $this->accessToken = $accessToken;
        $this->businessAccountId = $businessAccountId;
    }

    /**
     * Create a WhatsApp Message Template.
     *
     * @param array $data payload for template creation
     * @return array
     */
    public function createTemplate(array $data): array
    {
        // Templates are managed via WABA ID
        $idToUse = $this->businessAccountId ?? $this->phoneNumberId;
        $url = "{$this->baseUrl}/{$idToUse}/message_templates";

        $response = Http::withToken($this->accessToken)
            ->post($url, $data);

        if ($response->failed()) {
            Log::error('WhatsApp Create Template Error', ['response' => $response->body()]);
            // Throw exception or return error
            return ['error' => true, 'message' => $response->body(), 'status' => $response->status()];
        }

        return $response->json();
    }

    /**
     * Get Template Status/Details.
     *
     * @param string $name Template name
     * @return array
     */
    public function getTemplateStatus(string $name): array
    {
        // Using v23.0 as per Postman reference, or fall back to v22.0
        // The user postman used v23.0 for checkStatus.
        $idToUse = $this->businessAccountId ?? $this->phoneNumberId;
        $url = "https://graph.facebook.com/v23.0/{$idToUse}/message_templates";

        $response = Http::withToken($this->accessToken)
            ->get($url, [
                'name' => $name,
            ]);

        if ($response->failed()) {
            return ['error' => true, 'message' => $response->body()];
        }

        return $response->json();
    }

    /**
     * Send a Template Message.
     *
     * @param string $to Recipient Phone Number
     * @param string $templateName Name of the template
     * @param string $languageCode Language code (e.g. en_US)
     * @param array $components Components for variables (optional)
     * @return array
     */
    public function sendMessage(string $to, string $templateName, string $languageCode = 'en_US', array $components = []): array
    {
        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => $languageCode],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        $response = Http::withToken($this->accessToken)
            ->post($url, $payload);

        if ($response->failed()) {
            Log::error('WhatsApp Send Message Error', ['response' => $response->body()]);
            return ['error' => true, 'message' => $response->body()];
        }

        return $response->json();
    }
}
