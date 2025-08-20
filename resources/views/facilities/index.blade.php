@extends('layouts.app')

@section('content')
<h1>Recycling Facilities</h1>

<form method="GET" action="{{ route('facilities.index') }}" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" 
           placeholder="Search by name, city, or material" class="form-control w-1/3">

    <select name="material_id" class="form-control w-1/4">
        <option value="">-- Filter by Material --</option>
        @foreach($materials as $material)
            <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                {{ $material->name }}
            </option>
        @endforeach
    </select>

    <select name="sort" class="form-control w-1/4">
        <option value="">Sort by Name</option>
        <option value="last_update" {{ request('sort') === 'last_update' ? 'selected' : '' }}>Sort by Last Update</option>
    </select>

    <button type="submit" class="btn btn-primary">Apply</button>
    <a href="{{ route('facilities.index') }}" class="btn btn-secondary">Reset</a>
</form>
<a href="{{ route('facilities.create') }}">Add Facility</a>

<table border="1">
    <tr>
        <th>Name</th>
        <th><a href="?sort=last_update">Last Updated</a></th>
        <th>Materials</th>
        <th>Actions</th>
    </tr>
    @foreach($facilities as $facility)
    <tr>
        <td>{{ $facility->business_name }}</td>
        <td>{{ \Carbon\Carbon::parse($facility->last_update_date)->format('Y-m-d') }}</td>

        <td>{{ $facility->materials->pluck('name')->implode(', ') }}</td>
        <td>
            <a href="{{ route('facilities.show', $facility) }}">View</a>
            <a href="{{ route('facilities.edit', $facility) }}">Edit</a>
            <form action="{{ route('facilities.destroy', $facility) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $facilities->withQueryString()->links() }}
@endsection
