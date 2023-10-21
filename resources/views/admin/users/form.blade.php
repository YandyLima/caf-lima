<div class="col-md-4 position-relative">
    <h6 class="form-label">Nombre</h6>
    <input name="name" type="text" class="form-control" id="name" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Email</h6>
    <input name="email" type="text" class="form-control" id="email" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Contraseña</h6>
    <input name="password" type="password" class="form-control" id="password" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Dirección</h6>
    <input name="address" type="text" class="form-control" id="address" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Teléfono</h6>
    <input name="phone" type="text" minlength="8" maxlength="8" class="form-control" id="phone" required>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">Tipo</h6>
    <select name="type" class="form-select" id="type" required>
        <option value="1">Administrador</option>
        <option value="2">Cliente</option>
        <option value="3">Proveedor</option>
    </select>
    <div class="invalid-feedback">
        Campo obligatorio.
    </div>
</div>
<div class="col-md-4">
    <h6 class="form-label">NIT</h6>
    <input name="nit" type="number" minlength="6" maxlength="9" class="form-control" id="nit">
    <div class="invalid-feedback">
        Campo numérico.
    </div>
</div>
