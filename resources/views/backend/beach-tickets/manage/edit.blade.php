@extends('admin.admin_dashboard')
@section('admin')

<link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet">

<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Beach Ticket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.beach-tickets.manage.index') }}">Manage Tickets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Ticket</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title">Edit Beach Ticket</h5>
        <hr>
        <form action="{{ route('backend.beach-tickets.manage.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-body mt-4">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="border border-3 p-4 rounded">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ticket Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $ticket->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $ticket->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Benefits</label>
                                <div id="benefits-container">
                                    @forelse($ticket->benefits as $index => $benefit)
                                    <div class="input-group mb-2">
                                        <input type="hidden" name="benefits[{{ $index }}][id]" value="{{ $benefit->id }}">
                                        <input type="text" class="form-control" name="benefits[{{ $index }}][benefit_name]" value="{{ $benefit->benefit_name }}" placeholder="Enter benefit">
                                        <button type="button" class="btn btn-danger remove-benefit" {{ count($ticket->benefits) <= 1 ? 'disabled' : '' }}>
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                    @empty
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="benefits[0][benefit_name]" placeholder="Enter benefit">
                                        <button type="button" class="btn btn-danger remove-benefit" disabled>
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" id="add-benefit">
                                    <i class="bx bx-plus"></i> Add Benefit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border border-3 p-4 rounded">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="beach_name" class="form-label">Beach</label>
                                    <select class="form-select @error('beach_name') is-invalid @enderror" id="beach_name" name="beach_name" required>
                                        <option value="">Select Beach</option>
                                        <option value="lalassa" {{ old('beach_name', $ticket->beach_name) == 'lalassa' ? 'selected' : '' }}>Lalassa Beach Club</option>
                                        <option value="bodur" {{ old('beach_name', $ticket->beach_name) == 'bodur' ? 'selected' : '' }}>Bodur Beach</option>
                                    </select>
                                    @error('beach_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="ticket_type" class="form-label">Ticket Type</label>
                                    <select class="form-select @error('ticket_type') is-invalid @enderror" id="ticket_type" name="ticket_type" required>
                                        <option value="">Select Type</option>
                                        <option value="regular" {{ old('ticket_type', $ticket->ticket_type) == 'regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="bundling" {{ old('ticket_type', $ticket->ticket_type) == 'bundling' ? 'selected' : '' }}>Bundling</option>
                                    </select>
                                    @error('ticket_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="price" class="form-label">Price (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $ticket->price) }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="image" class="form-label">Ticket Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($ticket->image_url)
                                    <div class="mt-2">
                                        <label class="text-muted small">Current Image:</label><br>
                                        <img src="{{ $ticket->image_url }}" alt="{{ $ticket->name }}" class="img-thumbnail" style="max-height: 150px;">
                                        <div class="text-muted small mt-1">{{ basename($ticket->getAttributes()['image_url'] ?? '') }}</div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $ticket->active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">Update Ticket</button>
                                    <a href="{{ route('backend.beach-tickets.manage.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle dynamic benefits fields
    $(document).ready(function() {
        let benefitIndex = {{ count($ticket->benefits) > 0 ? count($ticket->benefits) - 1 : 0 }};
        
        // Add new benefit field
        $('#add-benefit').click(function() {
            benefitIndex++;
            
            const newBenefit = `
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="benefits[${benefitIndex}][benefit_name]" placeholder="Enter benefit">
                    <button type="button" class="btn btn-danger remove-benefit">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            `;
            
            $('#benefits-container').append(newBenefit);
            
            // Enable all remove buttons if we have more than one benefit
            if ($('#benefits-container .input-group').length > 1) {
                $('#benefits-container .remove-benefit').prop('disabled', false);
            }
        });
        
        // Remove benefit field
        $(document).on('click', '.remove-benefit', function() {
            $(this).closest('.input-group').remove();
            
            // If only one benefit left, disable its remove button
            if ($('#benefits-container .input-group').length === 1) {
                $('#benefits-container .remove-benefit').prop('disabled', true);
            }
        });
    });
</script>
@endpush