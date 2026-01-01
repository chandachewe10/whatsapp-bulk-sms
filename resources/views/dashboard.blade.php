@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Messages</h5>
                        <p class="card-text display-4">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Delivered/Sent</h5>
                        <p class="card-text display-4">{{ $stats['sent'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Queued</h5>
                        <p class="card-text display-4">{{ $stats['queued'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Failed</h5>
                        <p class="card-text display-4">{{ $stats['failed'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Messages</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>To</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMessages as $msg)
                                    <tr>
                                        <td>{{ $msg->recipient_phone }}</td>
                                        <td>{{ $msg->status }}</td>
                                        <td>{{ $msg->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('messages.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('messages.create') }}" class="btn btn-primary btn-lg">Send New Message</a>
                            <a href="{{ route('templates.create') }}" class="btn btn-outline-secondary">Create Template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection