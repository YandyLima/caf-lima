<div class="col-md-4">
    <h6 for="validationDefault04" class="form-label">Tipo</h6>
    <select name="key" class="form-select" id="key" required>
        @foreach($settings as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach

    </select>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Nombre</h6>
    <input name="name" type="text" class="form-control" id="name" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Value</h6>
    <input name="value" type="text" step="0.01" class="form-control" id="value">
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>

