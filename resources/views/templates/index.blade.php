@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Templates</h1>
        <a href="{{ route('templates.create') }}" class="btn btn-primary">Create Template</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Language</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->category }}</td>
                            <td>{{ $template->language }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $template->status == 'APPROVED' ? 'success' : ($template->status == 'REJECTED' ? 'danger' : 'warning') }}">
                                    {{ $template->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('templates.checkStatus', $template->id) }}" class="btn btn-sm btn-info">Check
                                    Status</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection