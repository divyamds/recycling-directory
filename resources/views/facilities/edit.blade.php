@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Facility</h2>
    <form action="{{ route('facilities.update', $facility->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" name="business_name" class="form-control" 
                   value="{{ old('business_name', $facility->business_name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Update Date</label>
            <input type="date" name="last_update_date" class="form-control" 
                   value="{{ old('last_update_date', $facility->last_update_date->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Street Address</label>
            <input type="text" name="street_address" class="form-control" 
                   value="{{ old('street_address', $facility->street_address) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" 
                   value="{{ old('city', $facility->city) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" 
                   value="{{ old('state', $facility->state) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal_code" class="form-control" 
                   value="{{ old('postal_code', $facility->postal_code) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Materials Accepted</label><br>
            @foreach($materials as $material)
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="materials[]" value="{{ $material->id }}"
                        class="form-check-input"
                        {{ in_array($material->id, $facility->materials->pluck('id')->toArray()) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $material->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
