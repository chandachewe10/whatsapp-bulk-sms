<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppConfigController extends Controller
{
    public function index()
    {
        $config = WhatsAppConfig::where('tenant_id', Auth::user()->tenant_id)->first();
        return view('configs.index', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number_id' => 'required|string',
            'business_account_id' => 'nullable|string',
            'access_token' => 'required|string',
            'app_id' => 'nullable|string',
        ]);

        $user = Auth::user();

        WhatsAppConfig::updateOrCreate(
            ['tenant_id' => $user->tenant_id],
            [
                'phone_number_id' => $request->phone_number_id,
                'business_account_id' => $request->business_account_id,
                'access_token' => $request->access_token,
                'app_id' => $request->app_id,
            ]
        );

        return back()->with('status', 'Configuration saved.');
    }
}
