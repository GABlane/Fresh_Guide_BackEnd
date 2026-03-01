{{--
    Shared form partial for rooms.
    Expects: $floors (with building), $facilities, $room (nullable)
--}}

{{-- Floor --}}
<div class="mb-3">
    <label class="form-label">Floor <span class="text-danger">*</span></label>
    <select name="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
        <option value="">— select floor —</option>
        @foreach($floors as $floor)
            <option value="{{ $floor->id }}"
                {{ old('floor_id', $room->floor_id ?? '') == $floor->id ? 'selected' : '' }}>
                {{ $floor->building->name ?? '?' }} › {{ $floor->name }}
            </option>
        @endforeach
    </select>
    @error('floor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    {{-- Code --}}
    <div class="col-md-4 mb-3">
        <label class="form-label">Code <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
               value="{{ old('code', $room->code ?? '') }}" placeholder="e.g. R201" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Name --}}
    <div class="col-md-8 mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $room->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Type --}}
<div class="mb-3">
    <label class="form-label">Type <span class="text-danger">*</span></label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
        @foreach(['classroom','office','lab','restroom','other'] as $t)
            <option value="{{ $t }}"
                {{ old('type', $room->type ?? '') === $t ? 'selected' : '' }}>
                {{ ucfirst($t) }}
            </option>
        @endforeach
    </select>
    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
              rows="3">{{ old('description', $room->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Facilities --}}
<div class="mb-3">
    <label class="form-label">Facilities</label>
    <div class="row g-2">
        @foreach($facilities as $facility)
            @php
                $checked = in_array(
                    $facility->id,
                    old('facilities', isset($room) ? $room->facilities->pluck('id')->toArray() : [])
                );
            @endphp
            <div class="col-6 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="facilities[]" value="{{ $facility->id }}"
                           id="fac_{{ $facility->id }}" {{ $checked ? 'checked' : '' }}>
                    <label class="form-check-label" for="fac_{{ $facility->id }}">
                        {{ $facility->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>
