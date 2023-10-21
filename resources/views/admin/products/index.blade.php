@extends('app')
@section('content')
    <div class="pagetitle">
        <h1>Productos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Productos</li>
                <li class="breadcrumb-item active">Lista</li>
            </ol>
        </nav>
    </div>
    <div class=" card">
        <div class="card-body">
            <div style="display: flex; justify-content: right;" class="py-3">
                <button onclick="showCreate()" class="btn btn-primary rounded-3"><i class="fa-solid fa-plus"></i> Crear
                </button>
            </div>
            <table id="users-table" class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Peso</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="text" hidden="hidden" id="route-images">

    {{-- Modal Create --}}
    <div class="modal fade " id="modal-create" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title ">Nuevo Registro</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center">
                    <form class="row g-3 needs-validation" method="POST" action="{{ route('products.store') }}" novalidate enctype="multipart/form-data" id="form-create">
                        @csrf
                        @include('admin.products.form')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-submit rounded-3" form="form-create">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modal-edit" tabindex="-1">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title ">Editar Registro</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center">
                    <form class="row g-3 needs-validation" method="POST" novalidate id="form-edit">
                        @method('PUT')
                        @csrf
                        @include('admin.products.form')
                    </form>
                    <div id="div-cover">
                        <br>
                        <h6 class="form-label" id="label-images">Portada</h6>
                        <form action="/" class="dropzone" id="cover">
                            @csrf
                        </form>
                    </div>
                    <div id="div-galery">
                        <br>
                        <h6 class="form-label" id="label-images">Galería</h6>
                        <form action="/" class="dropzone" id="galery">
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel rounded-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-submit rounded-4" form="form-edit">Guardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        let table = $('#users-table')
        table.DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            destroy: true,
            responsive: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: 'Usuarios',
                filename: 'Usuarios',
            }],
            ajax: '{{ route('product.list') }}',
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'description'},
                {data: 'stock'},
                {data: 'price'},
                {data: 'weight'},
                {
                    data: 'active',
                    render: function (active) {
                        return active === 1 ? '<span class="badge rounded-pill bg-success">Activo</span>' :
                            '<span class="badge rounded-pill bg-danger">Inactivo</span>'
                    }
                },
                {
                    data: 'id',
                    render: function (id) {
                        return '<button onclick="showModalEdit('+id+')" type="button" class="btn  btn-sm btn-warning" title="Editar"><i class="fa-sharp fa-solid fa-pen-to-square"></i></button> '+
                            '<button onclick="destroy('+"'"+"products/"+ id +"'"+')" type="button" class="btn  btn-sm btn-danger" title="Eliminar"><i class="fa-solid fa-trash"></i></button> '
                    }
                },
            ],
        });

        // Ejecuta la función al cargar la tabla y al hacer cambios en ella
        table.on('draw.dt', function () {
            checkStock();
        });

        // Ejecuta la función al cargar la página
        $(document).ready(function () {
            checkStock();
        });

        function checkStock() {
            const rows = table.find('tbody tr');
            rows.each((index, row) => {
                const stockCell = $(row).find('td:nth-child(4)');
                const stockValue = parseInt(stockCell.text());
                const minStock = 4; // Mínimo permitido de stock

                if (stockValue === 6) {
                    alert(`El producto ${$(row).find('td:nth-child(2)').text()} está a punto de llegar al mínimo permitido.`);
                }

                if (stockValue <= minStock) {
                    stockCell.css('color', 'red'); // Cambia el color del texto del stock a rojo
                    // O bien, para cambiar el fondo a rojo
                    // stockCell.css('background-color', 'red');
                }
            });
        }

        function showModalEdit(id) {
            axios.get('/admin/products/' + id)
                .then(function (response) {
                    let product = response.data
                    document.getElementById('form-edit').querySelector('#name').value = product.name;
                    document.getElementById('form-edit').querySelector('#description').value = product.description;
                    document.getElementById('form-edit').querySelector('#price').value = product.price;
                    document.getElementById('form-edit').querySelector('#stock').value = product.stock;
                    document.getElementById('form-edit').querySelector('#weight').value = product.weight;
                    document.getElementById('form-edit').querySelector('#active').value = product.active;
                    document.getElementById('form-edit').setAttribute('action', '/admin/products/' + id)
                    dropzoneCover(id)
                })
                .catch(function (error) {
                    showAlert('error', error.data.message)
                })
            let modal = new bootstrap.Modal(document.getElementById('modal-edit'), {
                keyboard: false
            })
            modal.show()
        }

        function showCreate() {
            let modal = new bootstrap.Modal(document.getElementById('modal-create'), {
                keyboard: false
            })
            modal.show()
        }

        function dropzoneCover(id) {
            let dropzoneCover = Dropzone.forElement("#cover");
            if (dropzoneCover) {
                restoreDropzone('cover', 'div-cover')
            }
            let dropzoneImages = Dropzone.forElement("#galery");
            if (dropzoneImages) {
                restoreDropzone('galery', 'div-galery')
            }
            new Dropzone("#cover", {
                init: function() {
                    let myDropzone = this;
                    let route = '/admin/images/'+id+'?type=1'
                    $.ajax({
                        type: "GET",
                        url: route,
                        success: function(response)
                        {
                            $.each(response, function( index, value) {
                                let mockFile = { name: value.name , size: value.size };
                                myDropzone.displayExistingFile(mockFile, value.route);
                            })
                        }
                    });
                },
                url: '/admin/product/images/' + id+'?type=1',
                autoProcessQueue: true,
                paramName: "file",
                maxFilesize: 4,
                addRemoveLinks: true,
                maxFiles: 1,
                dictDefaultMessage: 'Click o arrastre la imagen de portada',
                dictRemoveFile: "Borrar",
                acceptedFiles: 'image/*',
                removedfile: function (file) {
                    $.ajax({
                        type: 'POST',
                        url: '/admin/product/delete',
                        data: {name: file.name, id: id, type: 1 },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    });
                    let _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            });

            new Dropzone("#galery", {
                init: function() {
                    let myDropzone = this;
                    let route = '/admin/images/'+id+'?type=2'
                    $.ajax({
                        type: "GET",
                        url: route,
                        success: function(response)
                        {
                            $.each(response, function( index, value) {
                                let mockFile = { name: value.name , size: value.size };
                                myDropzone.displayExistingFile(mockFile, value.route);
                            })
                        }
                    });
                },
                url: '/admin/product/images/' + id+'?type=2',
                autoProcessQueue: true,
                paramName: "file",
                maxFilesize: 4,
                addRemoveLinks: true,
                maxFiles: 10,
                dictDefaultMessage: 'Click o arrastre imágenes para cargar',
                dictRemoveFile: "Borrar",
                acceptedFiles: 'image/*',
                removedfile: function (file) {
                    $.ajax({
                        type: 'POST',
                        url: '/admin/product/delete',
                        data: {name: file.name, id: id, type: 2 },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    });
                    let _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            });
        }

        function restoreDropzone(id, div) {
            let dropzoneElement = document.getElementById(id);
            dropzoneElement.parentNode.removeChild(dropzoneElement);
            let nuevoDropzoneElement = document.createElement('form');
            nuevoDropzoneElement.setAttribute('action', '/');
            nuevoDropzoneElement.setAttribute('class', 'dropzone');
            nuevoDropzoneElement.setAttribute('id', id);
            nuevoDropzoneElement.innerHTML = '@csrf';
            document.getElementById(div).appendChild(nuevoDropzoneElement);
        }
    </script>
@endpush
