{{-- Shared form partial for floors. Expects: $buildings, $floor (nullable) --}}
<div class="mb-3">
    <label class="form-label">Building <span class="text-danger">*</span></label>
    <select name="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
        <option value="">— select —</option>
        @foreach($buildings as $b)
            <option value="{{ $b->id }}"
                {{ old('building_id', $floor->building_id ?? '') == $b->id ? 'selected' : '' }}>
                {{ $b->name }} ({{ $b->code }})
            </option>
        @endforeach
    </select>
    @error('building_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Floor Number <span class="text-danger">*</span></label>
    <input type="number" name="number" class="form-control @error('number') is-invalid @enderror"
           value="{{ old('number', $floor->number ?? '') }}" min="0" placeholder="0 = Ground floor" required>
    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $floor->name ?? '') }}" placeholder="e.g. Ground Floor" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
