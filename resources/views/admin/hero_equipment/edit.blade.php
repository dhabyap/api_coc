@extends('layouts.admin')

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Edit Hero Equipment</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.hero-equipments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hero-equipments.update', $heroEquipment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $heroEquipment->name }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="rarity" class="form-label">Rarity</label>
                    <select class="form-select" id="rarity" name="rarity" required>
                        <option value="Common" {{ $heroEquipment->rarity == 'Common' ? 'selected' : '' }}>Common</option>
                        <option value="Epic" {{ $heroEquipment->rarity == 'Epic' ? 'selected' : '' }}>Epic</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="rank" class="form-label">Rank (e.g. SSS, SS, S, A, A+)</label>
                    <input type="text" class="form-control" id="rank" name="rank" value="{{ $heroEquipment->rank }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea class="form-control" id="reason" name="reason"
                        rows="3">{{ $heroEquipment->reason }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection