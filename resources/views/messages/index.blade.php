@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Messages</h1>
        <a href="{{ route('messages.create') }}" class="btn btn-primary">Send Message</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>To</th>
                        <th>Template</th>
                        <th>Status</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $message->recipient_phone }}</td>
                            <td>{{ $message->template->name ?? 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $message->status == 'sent' || $message->status == 'delivered' ? 'success' : ($message->status == 'failed' ? 'danger' : 'secondary') }}">
                                    {{ $message->status }}
                                </span>
                            </td>
                            <td class="text-danger">{{ $message->error_message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $messages->links() }}
        </div>
    </div>
@endsection