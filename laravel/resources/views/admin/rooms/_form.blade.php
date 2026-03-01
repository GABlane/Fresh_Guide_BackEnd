{{-- Expects: $floors (with building), $facilities, $room (nullable) --}}
<div class="field">
    <label>Floor <span class="req">*</span></label>
    <select name="floor_id" class="{{ $errors->has('floor_id') ? 'err' : '' }}" required>
        <option value="">— select floor —</option>
        @foreach($floors as $floor)
            <option value="{{ $floor->id }}"
                {{ old('floor_id', $room->floor_id ?? '') == $floor->id ? 'selected' : '' }}>
                {{ $floor->building->name ?? '?' }} › {{ $floor->name }}
            </option>
        @endforeach
    </select>
    @error('floor_id') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <div class="field">
        <label>Code <span class="req">*</span></label>
        <input type="text" name="code" value="{{ old('code', $room->code ?? '') }}"
               placeholder="e.g. R201"
               class="{{ $errors->has('code') ? 'err' : '' }}" required>
        @error('code') <div class="field-err">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label>Name <span class="req">*</span></label>
        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}"
               class="{{ $errors->has('name') ? 'err' : '' }}" required>
        @error('name') <div class="field-err">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label>Type <span class="req">*</span></label>
    <select name="type" class="{{ $errors->has('type') ? 'err' : '' }}" required>
        @foreach(['classroom','office','lab','restroom','other'] as $t)
            <option value="{{ $t }}"
                {{ old('type', $room->type ?? '') === $t ? 'selected' : '' }}>
                {{ ucfirst($t) }}
            </option>
        @endforeach
    </select>
    @error('type') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Description</label>
    <textarea name="description" rows="3"
              class="{{ $errors->has('description') ? 'err' : '' }}">{{ old('description', $room->description ?? '') }}</textarea>
    @error('description') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Facilities</label>
    <div class="check-grid">
        @foreach($facilities as $facility)
            @php
                $checked = in_array(
                    $facility->id,
                    old('facilities', isset($room) ? $room->facilities->pluck('id')->toArray() : [])
                );
            @endphp
            <label class="check-item" style="text-transform:none; letter-spacing:0; font-size:13px; font-family:inherit;">
                <input type="checkbox" name="facilities[]"
                       value="{{ $facility->id }}" {{ $checked ? 'checked' : '' }}>
                <span>{{ $facility->name }}</span>
            </label>
        @endforeach
    </div>
</div>
