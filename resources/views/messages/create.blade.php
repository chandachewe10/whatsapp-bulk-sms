@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Send Message</div>
        <div class="card-body">
            <form method="POST" action="{{ route('messages.send') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Select Template</label>
                    <select name="message_template_id" class="form-select" required>
                        <option value="">-- Select --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }} ({{ $template->language }})</option>
                        @endforeach
                    </select>
                    <div class="form-text">Only APPROVED templates are shown.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Recipient Phone</label>
                    <input type="text" name="recipient_phone" class="form-control" required placeholder="e.g. 260971234567">
                    <div class="form-text">Full phone number with country code, no + sign.</div>
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
@endsection