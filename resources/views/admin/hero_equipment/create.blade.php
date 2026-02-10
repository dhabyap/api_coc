@extends('layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Add Hero Equipment</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.hero-equipments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hero-equipments.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="rarity" class="form-label">Rarity</label>
                    <select class="form-select" id="rarity" name="rarity" required>
                        <option value="Common">Common</option>
                        <option value="Epic">Epic</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="rank" class="form-label">Rank (e.g. SSS, SS, S, A, A+)</label>
                    <input type="text" class="form-control" id="rank" name="rank" required>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>
@endsection