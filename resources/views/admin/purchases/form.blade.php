<div class="col-md-4 position-relative">
    <h6 class="form-label">Descripción</h6>
    <input name="description" type="text" class="form-control" id="description" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Precio</h6>
    <input name="price" type="number" step="0.01" class="form-control" id="price" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Peso</h6>
    <input name="weight" type="number" step="0.01" class="form-control" id="weight" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Usuario</h6>
    <select name="user_id" class="form-select" id="user_id" required>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
