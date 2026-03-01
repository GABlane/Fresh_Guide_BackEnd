{{-- Shared form partial for buildings. Expects: $building (nullable) --}}
<div class="field">
    <label>Code <span class="req">*</span></label>
    <input type="text" name="code" value="{{ old('code', $building->code ?? '') }}"
           placeholder="e.g. MAIN" class="{{ $errors->has('code') ? 'err' : '' }}" required>
    @error('code') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Name <span class="req">*</span></label>
    <input type="text" name="name" value="{{ old('name', $building->name ?? '') }}"
           class="{{ $errors->has('name') ? 'err' : '' }}" required>
    @error('name') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Description</label>
    <textarea name="description" rows="3"
              class="{{ $errors->has('description') ? 'err' : '' }}">{{ old('description', $building->description ?? '') }}</textarea>
    @error('description') <div class="field-err">{{ $message }}</div> @enderror
</div>
