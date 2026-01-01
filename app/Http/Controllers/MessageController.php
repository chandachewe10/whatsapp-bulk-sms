<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageTemplate;
use App\Models\WhatsAppConfig;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('tenant_id', Auth::user()->tenant_id)->with('template')->latest()->paginate(20);
        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        $templates = MessageTemplate::where('tenant_id', Auth::user()->tenant_id)->where('status', 'APPROVED')->get(); // Only approved templates
        return view('messages.create', compact('templates'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message_template_id' => 'required|exists:message_templates,id',
            'recipient_phone' => 'required|string', // Basic validation, better to use regex or lib
        ]);

        $user = Auth::user();
        if (!$user->tenant_id)
            abort(403, 'No tenant assigned');

        $config = WhatsAppConfig::where('tenant_id', $user->tenant_id)->firstOrFail();
        $template = MessageTemplate::findOrFail($request->message_template_id);

        // Ensure template belongs to tenant (security)
        if ($template->tenant_id !== $user->tenant_id)
            abort(403);

        $recipients = array_map('trim', explode(',', $request->recipient_phone));
        $successCount = 0;
        $failCount = 0;

        // Pass Business Account ID as well
        $service = new WhatsAppService($config->phone_number_id, $config->access_token, $config->business_account_id);

        foreach ($recipients as $recipient) {
            if (empty($recipient))
                continue;

            $result = $service->sendMessage($recipient, $template->name, $template->language);

            if (isset($result['error'])) {
                // Log failure
                Message::create([
                    'tenant_id' => $user->tenant_id,
                    'message_template_id' => $template->id,
                    'recipient_phone' => $recipient,
                    'status' => 'failed',
                    'error_message' => json_encode($result['message']),
                ]);
                $failCount++;
            } else {
                // Log success
                Message::create([
                    'tenant_id' => $user->tenant_id,
                    'message_template_id' => $template->id,
                    'recipient_phone' => $recipient,
                    'status' => 'queued',
                    'whatsapp_message_id' => $result['messages'][0]['id'] ?? null,
                ]);
                $successCount++;
            }
        }

        return redirect()->route('messages.index')->with('status', "Processed: $successCount sent, $failCount failed.");
    }
}
