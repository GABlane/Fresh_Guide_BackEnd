{{-- Expects: $facility (nullable) --}}
<div class="field">
    <label>Name <span class="req">*</span></label>
    <input type="text" name="name" value="{{ old('name', $facility->name ?? '') }}"
           placeholder="e.g. WiFi"
           class="{{ $errors->has('name') ? 'err' : '' }}" required>
    @error('name') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Icon key</label>
    <input type="text" name="icon" value="{{ old('icon', $facility->icon ?? '') }}"
           placeholder="e.g. ic_wifi"
           class="{{ $errors->has('icon') ? 'err' : '' }}">
    <div class="field-hint">Android drawable name used in the app.</div>
    @error('icon') <div class="field-err">{{ $message }}</div> @enderror
</div>
