{{-- Expects: $origin (nullable) --}}
<div class="form-row">
    <div class="field">
        <label>Code <span class="req">*</span></label>
        <input type="text" name="code" value="{{ old('code', $origin->code ?? '') }}"
               placeholder="e.g. GATE"
               class="{{ $errors->has('code') ? 'err' : '' }}" required>
        @error('code') <div class="field-err">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label>Name <span class="req">*</span></label>
        <input type="text" name="name" value="{{ old('name', $origin->name ?? '') }}"
               placeholder="e.g. Main Gate"
               class="{{ $errors->has('name') ? 'err' : '' }}" required>
        @error('name') <div class="field-err">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label>Description</label>
    <textarea name="description" rows="2"
              class="{{ $errors->has('description') ? 'err' : '' }}">{{ old('description', $origin->description ?? '') }}</textarea>
    @error('description') <div class="field-err">{{ $message }}</div> @enderror
</div>
