{{-- Shared form partial for facilities. Expects: $facility (nullable) --}}
<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $facility->name ?? '') }}" placeholder="e.g. WiFi" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Icon key</label>
    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
           value="{{ old('icon', $facility->icon ?? '') }}" placeholder="e.g. ic_wifi">
    <div class="form-text">Android drawable name used by the app.</div>
    @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
