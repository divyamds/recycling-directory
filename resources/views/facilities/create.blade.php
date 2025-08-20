@extends('layouts.app')

@section('content')
<h1>{{ isset($facility) ? 'Edit Facility' : 'Add Facility' }}</h1>

<form method="POST" 
      action="{{ isset($facility) ? route('facilities.update', $facility) : route('facilities.store') }}">
    @csrf
    @if(isset($facility)) @method('PUT') @endif

    <label>Name</label>
    <input type="text" name="business_name" value="{{ old('business_name', $facility->business_name ?? '') }}" required>

    <label>Last Update Date</label>
    <input type="date" name="last_update_date" value="{{ old('last_update_date', $facility->last_update_date ?? '') }}" required>

    <label>Address</label>
    <input type="text" name="street_address" value="{{ old('street_address', $facility->street_address ?? '') }}" required>

    <label>Materials</label>
    @foreach($materials as $mat)
        <div>
            <input type="checkbox" name="materials[]" value="{{ $mat->id }}"
                {{ isset($facility) && $facility->materials->contains($mat->id) ? 'checked' : '' }}>
            {{ $mat->name }}
        </div>
    @endforeach

    <button type="submit">Save</button>
</form>
@endsection
