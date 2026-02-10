@extends('layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Hero Equipments</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.hero-equipments.create') }}" class="btn btn-primary">Add New Equipment</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rarity</th>
                        <th>Rank</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipments as $equipment)
                        <tr>
                            <td>{{ $equipment->id }}</td>
                            <td>{{ $equipment->name }}</td>
                            <td>
                                <span
                                    class="badge {{ $equipment->rarity == 'Epic' ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                    {{ $equipment->rarity }}
                                </span>
                            </td>
                            <td>{{ $equipment->rank }}</td>
                            <td>{{ Str::limit($equipment->reason, 50) }}</td>
                            <td>
                                <a href="{{ route('admin.hero-equipments.edit', $equipment->id) }}"
                                    class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.hero-equipments.destroy', $equipment->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection