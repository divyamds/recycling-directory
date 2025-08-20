@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">{{ $facility->business_name }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Last Updated:</strong> {{ \Carbon\Carbon::parse($facility->last_update_date)->format('Y-m-d') }}</p>

        
        <p><strong>Street Address:</strong> {{ $facility->street_address }}</p>
        <p><strong>City:</strong> {{ $facility->city }}</p>
        <p><strong>State:</strong> {{ $facility->state }}</p>
        <p><strong>Postal Code:</strong> {{ $facility->postal_code }}</p>

        <p><strong>Materials Accepted:</strong></p>
        <ul>
            @foreach($facility->materials as $material)
                <li>{{ $material->name }}</li>
            @endforeach
        </ul>

        <hr>

        <h5>Location Map</h5>
        <div class="ratio ratio-16x9">
            <iframe
                src="https://www.google.com/maps?q={{ urlencode($facility->street_address . ', ' . $facility->city . ', ' . $facility->state . ' ' . $facility->postal_code) }}&output=embed"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <div class="mt-3">
            <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-warning">Edit</a>

            <form action="{{ route('facilities.destroy', $facility) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this facility?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
