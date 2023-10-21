@extends('app')
@section('content')
    <div class="pagetitle">
        <h1>Compras</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Compras</li>
                <li class="breadcrumb-item active">Lista</li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: right;" class="py-3">
                <button onclick="showCreate()" class="btn btn-primary rounded-3"><i class="fa-solid fa-plus"></i> Crear
                </button>
            </div>
            <table id="purchases-table" class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Peso</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade " id="modal-create" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title ">Nuevo Registro</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center">
                    <form class="row g-3 needs-validation" method="POST" action="{{ route('purchases.store') }}" novalidate enctype="multipart/form-data" id="form-create">
                        @csrf
                        @include('admin.purchases.form')
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
                        @include('admin.purchases.form')
                    </form>
                    <div id="div-picture">
                        <br>
                        <h6 class="form-label" id="label-images">Foto</h6>
                        <form action="/" class="dropzone" id="picture">
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
        let table = $('#purchases-table')
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
                title: 'Compras',
                filename: 'Compras',
            }],
            ajax: '{{ route('purchases.list') }}',
            columns: [
                {data: 'id'},
                {data: 'description'},
                {data: 'price'},
                {data: 'weight'},
                {data: 'user_id'},
                {
                    data: 'id',
                    render: function (id) {
                        return '<button onclick="showModalEdit('+id+')" type="button" class="btn  btn-sm btn-warning" title="Editar"><i class="fa-sharp fa-solid fa-pen-to-square"></i></button> '+
                            '<button onclick="destroy('+"'"+"purchases/"+ id +"'"+')" type="button" class="btn  btn-sm btn-danger" title="Eliminar"><i class="fa-solid fa-trash"></i></button> '
                    }
                },
            ],
        });

        function showModalEdit(id) {
            axios.get('/admin/purchases/' + id)
                .then(function (response) {
                    let user = response.data
                    document.getElementById('form-edit').querySelector('#description').value = user.description;
                    document.getElementById('form-edit').querySelector('#price').value = user.price;
                    document.getElementById('form-edit').querySelector('#weight').value = user.weight;
                    document.getElementById('form-edit').querySelector('#user_id').value = user.user_id;
                    document.getElementById('form-edit').setAttribute('action', '/admin/purchases/' + id)
                    dropzonePicture(id)
                })
                .catch(function (error) {
                    showAlert('error', error)
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

        function dropzonePicture(id) {
            let dropzonePicture = Dropzone.forElement("#picture");
            if (dropzonePicture) {
                restoreDropzone('picture', 'div-picture')
            }
            new Dropzone("#picture", {
                init: function() {
                    let myDropzone = this;
                    let route = '/admin/purchase/image/' + id
                    $.ajax({
                        type: "GET",
                        url: route,
                        success: function(response)
                        {
                            let mockFile = { name: response.name , size: response.size };
                            myDropzone.displayExistingFile(mockFile, response.route);
                        }
                    });
                },
                url: '/admin/purchase/image/' + id + '?type=1',
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
                        url: '/admin/purchase-image/delete',
                        data: {name: file.name, id: id },
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
