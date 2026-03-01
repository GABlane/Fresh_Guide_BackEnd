{{-- Expects: $origins, $rooms, $route (nullable with ->steps loaded) --}}

<div class="form-row">
    <div class="field">
        <label>Start (Origin) <span class="req">*</span></label>
        <select name="origin_id" class="{{ $errors->has('origin_id') ? 'err' : '' }}" required>
            <option value="">— select start —</option>
            @foreach($origins as $origin)
                <option value="{{ $origin->id }}"
                    {{ old('origin_id', $route->origin_id ?? '') == $origin->id ? 'selected' : '' }}>
                    {{ $origin->name }}
                </option>
            @endforeach
        </select>
        @error('origin_id') <div class="field-err">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <label>Destination Room <span class="req">*</span></label>
        <select name="destination_room_id" class="{{ $errors->has('destination_room_id') ? 'err' : '' }}" required>
            <option value="">— select room —</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}"
                    {{ old('destination_room_id', $route->destination_room_id ?? '') == $room->id ? 'selected' : '' }}>
                    {{ $room->name }} — {{ $room->floor->building->name ?? '?' }}
                </option>
            @endforeach
        </select>
        @error('destination_room_id') <div class="field-err">{{ $message }}</div> @enderror
    </div>
</div>

<div class="field">
    <label>Route Name <span class="req">*</span></label>
    <input type="text" name="name" value="{{ old('name', $route->name ?? '') }}"
           placeholder="e.g. Main Gate to Room 201"
           class="{{ $errors->has('name') ? 'err' : '' }}" required>
    @error('name') <div class="field-err">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label>Description</label>
    <textarea name="description" rows="2">{{ old('description', $route->description ?? '') }}</textarea>
</div>

{{-- Steps editor --}}
<div class="divider-label"><span>Direction Steps</span></div>

<div id="stepsContainer">
    @php $existingSteps = old('steps', isset($route) ? $route->steps->toArray() : []); @endphp
    @foreach($existingSteps as $i => $step)
    <div class="step-card">
        <div class="step-card-header">
            <span class="step-badge">Step {{ $i + 1 }}</span>
            <button type="button" class="btn btn-danger btn-sm remove-step">Remove</button>
        </div>
        <input type="hidden" name="steps[{{ $i }}][order]" value="{{ $step['order'] ?? $i + 1 }}" class="step-order">
        <div class="field">
            <label>Instruction <span class="req">*</span></label>
            <textarea name="steps[{{ $i }}][instruction]" rows="2" required>{{ $step['instruction'] ?? '' }}</textarea>
        </div>
        <div class="form-row">
            <div class="field">
                <label>Direction</label>
                <select name="steps[{{ $i }}][direction]">
                    <option value="">— none —</option>
                    @foreach(['straight','left','right','up','down'] as $dir)
                        <option value="{{ $dir }}" {{ ($step['direction'] ?? '') === $dir ? 'selected' : '' }}>{{ ucfirst($dir) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>Landmark</label>
                <input type="text" name="steps[{{ $i }}][landmark]"
                       value="{{ $step['landmark'] ?? '' }}" placeholder="e.g. Registrar building">
            </div>
        </div>
    </div>
    @endforeach
</div>

<button type="button" class="add-step-btn" id="addStepBtn">+ Add Step</button>

@push('scripts')
<script>
(function () {
    let count = {{ count($existingSteps) }};

    function makeStep(i) {
        return `
        <div class="step-card">
            <div class="step-card-header">
                <span class="step-badge">Step ${i + 1}</span>
                <button type="button" class="btn btn-danger btn-sm remove-step">Remove</button>
            </div>
            <input type="hidden" name="steps[${i}][order]" value="${i + 1}" class="step-order">
            <div class="field">
                <label>Instruction <span class="req">*</span></label>
                <textarea name="steps[${i}][instruction]" rows="2" required></textarea>
            </div>
            <div class="form-row">
                <div class="field">
                    <label>Direction</label>
                    <select name="steps[${i}][direction]">
                        <option value="">— none —</option>
                        <option value="straight">Straight</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="up">Up</option>
                        <option value="down">Down</option>
                    </select>
                </div>
                <div class="field">
                    <label>Landmark</label>
                    <input type="text" name="steps[${i}][landmark]" placeholder="e.g. Registrar building">
                </div>
            </div>
        </div>`;
    }

    function reindex() {
        document.querySelectorAll('#stepsContainer .step-card').forEach((card, i) => {
            card.querySelector('.step-badge').textContent = `Step ${i + 1}`;
            card.querySelector('.step-order').name = `steps[${i}][order]`;
            card.querySelector('.step-order').value = i + 1;
            card.querySelector('textarea').name = `steps[${i}][instruction]`;
            card.querySelector('select').name = `steps[${i}][direction]`;
            card.querySelector('input[type=text]').name = `steps[${i}][landmark]`;
        });
        count = document.querySelectorAll('#stepsContainer .step-card').length;
    }

    document.getElementById('addStepBtn').addEventListener('click', () => {
        document.getElementById('stepsContainer').insertAdjacentHTML('beforeend', makeStep(count));
        count++;
    });

    document.getElementById('stepsContainer').addEventListener('click', e => {
        if (e.target.classList.contains('remove-step')) {
            e.target.closest('.step-card').remove();
            reindex();
        }
    });
})();
</script>
@endpush
