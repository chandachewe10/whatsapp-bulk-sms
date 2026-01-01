@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Create Message Template</div>
        <div class="card-body">

            <div class="alert alert-info">
                <strong>Approval Period:</strong> After you submit a template, WhatsApp typically approves or rejects it
                within minutes through a machine-learning assisted process. Templates that cannot be triaged automatically
                are routed for human review and can take up to 48 hours. If a template remains in the Pending state for more
                than 48 hours, open a SwiftSMS support ticket and include the template name.
            </div>

            <form method="POST" action="{{ route('templates.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Template Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="hello_world">
                    <div class="form-text">Lowercase, underscores only.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="MARKETING">MARKETING</option>
                        <option value="UTILITY">UTILITY</option>
                        <option value="AUTHENTICATION">AUTHENTICATION</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Language</label>
                    <select name="language" class="form-select" required>
                        <option value="en_US">English (US)</option>
                        <option value="en_GB">English (UK)</option>
                        <!-- Add more as needed -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Body Text</label>
                    <textarea name="body_text" class="form-control" rows="3" required></textarea>
                    <div class="form-text">The main text of your message.</div>
                </div>

                <button type="submit" class="btn btn-primary">Submit for Approval</button>
            </form>
        </div>
    </div>
@endsection