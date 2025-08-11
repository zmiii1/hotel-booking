@extends('admin.admin_dashboard')

@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Beach Ticket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Tickets</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('backend.beach-tickets.manage.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Ticket
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 bg-success alert-dismissible fade show">
    <div class="text-white">{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Lalassa Beach Tickets -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Lalassa Beach Club Tickets</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Regular Tickets</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets->where('beach_name', 'lalassa')->where('ticket_type', 'regular') as $ticket)
                                    <tr>
                                        <td>{{ $ticket->name }}</td>
                                        <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($ticket->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span> 
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('backend.beach-tickets.manage.edit', $ticket->id) }}" class="btn btn-primary btn-sm me-2">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('backend.beach-tickets.manage.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No regular tickets found for Lalassa Beach Club.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Bundling Tickets</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets->where('beach_name', 'lalassa')->where('ticket_type', 'bundling') as $ticket)
                                    <tr>
                                        <td>{{ $ticket->name }}</td>
                                        <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($ticket->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('backend.beach-tickets.manage.edit', $ticket->id) }}" class="btn btn-primary btn-sm me-2">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('backend.beach-tickets.manage.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No bundling tickets found for Lalassa Beach Club.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bodur Beach Tickets -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">Bodur Beach Tickets</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Regular Tickets</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets->where('beach_name', 'bodur')->where('ticket_type', 'regular') as $ticket)
                                    <tr>
                                        <td>{{ $ticket->name }}</td>
                                        <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($ticket->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('backend.beach-tickets.manage.edit', $ticket->id) }}" class="btn btn-primary btn-sm me-2">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('backend.beach-tickets.manage.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No regular tickets found for Bodur Beach.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Bundling Tickets</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets->where('beach_name', 'bodur')->where('ticket_type', 'bundling') as $ticket)
                                    <tr>
                                        <td>{{ $ticket->name }}</td>
                                        <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($ticket->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('backend.beach-tickets.manage.edit', $ticket->id) }}" class="btn btn-primary btn-sm me-2">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <form action="{{ route('backend.beach-tickets.manage.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No bundling tickets found for Bodur Beach.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection