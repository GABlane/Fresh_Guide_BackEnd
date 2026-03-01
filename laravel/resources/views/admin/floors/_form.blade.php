{{-- Expects: $buildings, $floor (nullable) --}}
<div class="field">
    <label>Building <span class="req">*</span></label>
    <select name="building_id" class="{{ $errors->has('building_id') ? 'err' : '' }}" required>
        <option value="">— select building —</option>
        @foreach($buildings as $b)
            <option value="{{ $b->id }}"
                {{ old('building_id', $floor->building_id ?? '') == $b->id ? 'selected' : '' }}>
                {{ $b->name }} ({{ $b->code }})
            </option>
        @endforeach
    </select>
    @error('building_id') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <div class="field">
        <label>Floor Number <span class="req">*</span></label>
        <input type="number" name="number" min="0"
               value="{{ old('number', $floor->number ?? '') }}"
               placeholder="0 = Ground floor"
               class="{{ $errors->has('number') ? 'err' : '' }}" required>
        @error('number') <div class="field-err">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label>Name <span class="req">*</span></label>
        <input type="text" name="name"
               value="{{ old('name', $floor->name ?? '') }}"
               placeholder="e.g. Ground Floor"
               class="{{ $errors->has('name') ? 'err' : '' }}" required>
        @error('name') <div class="field-err">{{ $message }}</div> @enderror
    </div>
</div>
