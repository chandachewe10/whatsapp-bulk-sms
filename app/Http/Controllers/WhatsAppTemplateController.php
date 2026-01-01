<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use App\Models\WhatsAppConfig;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WhatsAppTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::where('tenant_id', Auth::user()->tenant_id)->latest()->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-z0-9_]+$/', // WhatsApp names must be lowercase snake_case
            'category' => 'required|string',
            'language' => 'required|string',
            'body_text' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user->tenant_id) {
            return back()->withErrors(['tenant' => 'User is not associated with a tenant.']);
        }

        $config = WhatsAppConfig::where('tenant_id', $user->tenant_id)->first();
        if (!$config) {
            return redirect()->route('configs.index')->withErrors(['config' => 'Please configure WhatsApp settings first.']);
        }

        $service = new WhatsAppService($config->phone_number_id, $config->access_token, $config->business_account_id);

        // Construct payload for WhatsApp API
        $payload = [
            'name' => $request->name,
            'category' => $request->category,
            'language' => $request->language,
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $request->body_text
                ]
            ]
        ];

        $result = $service->createTemplate($payload);

        if (isset($result['error'])) {
            return back()->withErrors(['api_error' => 'WhatsApp API Error: ' . ($result['message'] ?? 'Unknown error')]);
        }

        // Save to DB
        MessageTemplate::create([
            'tenant_id' => $user->tenant_id,
            'name' => $request->name,
            'category' => $request->category,
            'language' => $request->language,
            'status' => 'PENDING',
            'content' => $payload,
            'whatsapp_template_id' => $result['id'] ?? null,
        ]);

        return redirect()->route('templates.index')->with('status', 'Template submitted for approval. Approval usually takes minutes.');
    }

    public function checkStatus(MessageTemplate $template)
    {
        $user = Auth::user();
        if ($template->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $config = WhatsAppConfig::where('tenant_id', $user->tenant_id)->firstOrFail();
        $service = new WhatsAppService($config->phone_number_id, $config->access_token, $config->business_account_id);

        $result = $service->getTemplateStatus($template->name);

        if (isset($result['data'][0])) {
            $status = $result['data'][0]['status'];
            $template->update(['status' => $status]);
            return back()->with('status', "Template status updated: $status");
        }

        return back()->withErrors(['api_error' => 'Could not fetch status. API Response: ' . json_encode($result)]);
    }
}
