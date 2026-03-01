{{--
    Shared form partial for campus routes.
    Expects: $origins, $rooms, $route (nullable with ->steps loaded)
--}}

<div class="row">
    {{-- Origin --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Start (Origin) <span class="text-danger">*</span></label>
        <select name="origin_id" class="form-select @error('origin_id') is-invalid @enderror" required>
            <option value="">— select start —</option>
            @foreach($origins as $origin)
                <option value="{{ $origin->id }}"
                    {{ old('origin_id', $route->origin_id ?? '') == $origin->id ? 'selected' : '' }}>
                    {{ $origin->name }}
                </option>
            @endforeach
        </select>
        @error('origin_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Destination room --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">Destination Room <span class="text-danger">*</span></label>
        <select name="destination_room_id" class="form-select @error('destination_room_id') is-invalid @enderror" required>
            <option value="">— select room —</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}"
                    {{ old('destination_room_id', $route->destination_room_id ?? '') == $room->id ? 'selected' : '' }}>
                    {{ $room->name }} ({{ $room->floor->building->name ?? '?' }} › {{ $room->floor->name ?? '?' }})
                </option>
            @endforeach
        </select>
        @error('destination_room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Route name --}}
<div class="mb-3">
    <label class="form-label">Route Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $route->name ?? '') }}"
           placeholder="e.g. Main Gate to Room 201" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Description --}}
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="2">{{ old('description', $route->description ?? '') }}</textarea>
</div>

{{-- ── Steps editor ─────────────────────────────────────────────────────── --}}
<hr class="my-4">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-semibold mb-0">Direction Steps</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" id="addStepBtn">+ Add Step</button>
</div>

<div id="stepsContainer">
    @php
        $existingSteps = old('steps', isset($route) ? $route->steps->toArray() : []);
    @endphp

    @forelse($existingSteps as $i => $step)
    <div class="step-row card mb-2 p-3 border">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong class="step-label">Step {{ $i + 1 }}</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-step">Remove</button>
        </div>
        <input type="hidden" name="steps[{{ $i }}][order]" value="{{ $step['order'] ?? $i + 1 }}" class="step-order">
        <div class="mb-2">
            <label class="form-label form-label-sm">Instruction <span class="text-danger">*</span></label>
            <textarea name="steps[{{ $i }}][instruction]" class="form-control form-control-sm"
                      rows="2" required>{{ $step['instruction'] ?? '' }}</textarea>
        </div>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label form-label-sm">Direction</label>
                <select name="steps[{{ $i }}][direction]" class="form-select form-select-sm">
                    <option value="">— none —</option>
                    @foreach(['straight','left','right','up','down'] as $dir)
                        <option value="{{ $dir }}" {{ ($step['direction'] ?? '') === $dir ? 'selected' : '' }}>
                            {{ ucfirst($dir) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label form-label-sm">Landmark</label>
                <input type="text" name="steps[{{ $i }}][landmark]" class="form-control form-control-sm"
                       value="{{ $step['landmark'] ?? '' }}" placeholder="e.g. Registrar building">
            </div>
        </div>
    </div>
    @empty
    {{-- empty — JS will add the first step if user clicks Add --}}
    @endforelse
</div>

@push('scripts')
<script>
(function () {
    let count = {{ count($existingSteps) }};

    function makeStep(index) {
        return `
        <div class="step-row card mb-2 p-3 border">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="step-label">Step ${index + 1}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger remove-step">Remove</button>
            </div>
            <input type="hidden" name="steps[${index}][order]" value="${index + 1}" class="step-order">
            <div class="mb-2">
                <label class="form-label form-label-sm">Instruction <span class="text-danger">*</span></label>
                <textarea name="steps[${index}][instruction]" class="form-control form-control-sm"
                          rows="2" required></textarea>
            </div>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Direction</label>
                    <select name="steps[${index}][direction]" class="form-select form-select-sm">
                        <option value="">— none —</option>
                        <option value="straight">Straight</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="up">Up</option>
                        <option value="down">Down</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-sm">Landmark</label>
                    <input type="text" name="steps[${index}][landmark]"
                           class="form-control form-control-sm" placeholder="e.g. Registrar building">
                </div>
            </div>
        </div>`;
    }

    function reindex() {
        document.querySelectorAll('#stepsContainer .step-row').forEach((row, i) => {
            row.querySelector('.step-label').textContent = `Step ${i + 1}`;
            row.querySelector('.step-order').name  = `steps[${i}][order]`;
            row.querySelector('.step-order').value = i + 1;
            row.querySelector('textarea').name     = `steps[${i}][instruction]`;
            row.querySelector('select').name       = `steps[${i}][direction]`;
            row.querySelector('input[type=text]').name = `steps[${i}][landmark]`;
        });
        count = document.querySelectorAll('#stepsContainer .step-row').length;
    }

    document.getElementById('addStepBtn').addEventListener('click', function () {
        const container = document.getElementById('stepsContainer');
        container.insertAdjacentHTML('beforeend', makeStep(count));
        count++;
    });

    document.getElementById('stepsContainer').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-step')) {
            e.target.closest('.step-row').remove();
            reindex();
        }
    });
})();
</script>
@endpush
