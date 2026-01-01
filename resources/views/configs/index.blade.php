@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">WhatsApp Configuration</div>
        <div class="card-body">
            <form method="POST" action="{{ route('configs.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Phone Number ID</label>
                    <input type="text" name="phone_number_id" class="form-control"
                        value="{{ $config->phone_number_id ?? '104927222402855' }}" required>
                    <div class="form-text">Your WhatsApp Phone Number ID (e.g. 104927222402855 for the example).</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Business Account ID (Optional)</label>
                    <input type="text" name="business_account_id" class="form-control"
                        value="{{ $config->business_account_id ?? '' }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Access Token</label>
                    <textarea name="access_token" class="form-control" rows="3"
                        required>{{ $config->access_token ?? '' }}</textarea>
                    <div class="form-text">System User Access Token with `whatsapp_business_messaging` permission.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">App ID (Optional)</label>
                    <input type="text" name="app_id" class="form-control" value="{{ $config->app_id ?? '' }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Configuration</button>
            </form>
        </div>
    </div>
@endsection