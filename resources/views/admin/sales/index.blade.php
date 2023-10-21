@extends('app')
@section('content')
    <div class="pagetitle">
        <h1>Pedidos</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Pedidos</li>
                <li class="breadcrumb-item active">Lista</li>
            </ol>
        </nav>
    </div>
    <div class="card">
        <div class="card-body pt-3">
            <table id="sales-table" class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Monto</th>
{{--                    <th scope="col"># Transacción</th>--}}
                    <th scope="col">Tipo de pago</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal Sale Details --}}
    <div class="modal fade " id="modal-sale-details" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title ">Detalle de pedido</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center">
                    <table id="sales-details-table" class="table table-striped table-bordered overflow-auto w-100">
                        <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <h6 style="text-align: right" id="total"></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <a type="submit" class="btn btn-submit rounded-3" id="billing" >Facturar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tracking --}}
    <div class="modal fade " id="modal-tracking" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title-tracking">Detalle de tracking</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center dashboard">
                    <div class="activity" id="tracking">
                    </div>
                    <br>
                    <form class="row g-3 needs-validation" method="POST" novalidate enctype="multipart/form-data" id="form-tracking">
                        @csrf
                        <div class="col-md-4">
                            <h6 class="form-label">Actualizar tracking</h6>
                            <select name="status" class="form-select" id="status" required>
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}">{!! $status !!}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Campo obligatorio.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-submit rounded-3" form="form-tracking">Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Bill --}}
    <div class="modal fade " id="modal-bill" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 px-4 py-2">
                <div class="modal-header">
                    <h5 class="modal-title ">Factura</h5>
                    <button type="button" class="btn fw-bold" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body align-content-center">
                    <embed id="bill" style="width:100%; height:70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel rounded-3" data-bs-dismiss="modal">Cancelar</button>
                    <a type="submit" class="btn btn-submit rounded-3" id="show-details" onclick="hideBill()">Ver detalle</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let table = $('#sales-table')
        table.DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            },
            destroy: true,
            order: [[0, "desc"]],
            responsive: true,
            serverSide: true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: 'Pedidos',
                filename: 'Pedidos',
            }],
            ajax: '{{ route('sales.list') }}',
            columns: [
                {data: 'id'},
                {data: 'customer'},
                {data: 'amount'},
                // {data: 'transaction_number'},
                {data: 'paid_type'},
                {data: 'status'},
                {data: 'date'},
                {
                    data: 'id',
                    render: function (id) {
                        return '<button onclick="tracking('+id+')" type="button" class="btn  btn-sm btn-success" title="Track"><i class="fa-solid fa-truck-fast"></i></button> '+
                            '<button onclick="saleDetail('+ id +')" type="button" class="btn  btn-sm btn-outline-primary" title="Detalle pedido"><i class="fa-solid fa-eye"></i></button> '
                    }
                },
            ],
        });

        let modalDetails = new bootstrap.Modal(document.getElementById('modal-sale-details'), { keyboard: false })
        let modalBill    = new bootstrap.Modal(document.getElementById('modal-bill'),         { keyboard: false })

        function saleDetail(id) {
            axios.get('{{ route('sale-details.index') }}?sale_id='+id)
                .then(function (response) {
                    let item = response.data
                    let total = 0
                    let tableDetails = $('#sales-details-table tbody')
                    tableDetails.empty()
                    $.each(item.data, function (key, value) {
                        tableDetails.append('<tr> '+
                            '<td>'+ value.product +'</td>'+
                            '<td>'+ value.amount +'</td>'+
                            '<td>'+ value.price +'</td>'+
                            '<td>'+ value.subtotal.toFixed(2) +'</td>'+
                        '</tr>')
                        total += value.subtotal
                    })
                    $('#total').html('Total: Q.'+total.toFixed(2))

                    //Boton facturar o ver factura
                    let billing = $('#modal-sale-details #billing')
                    if (item.sale.status !== 5) {
                        billing.prop("hidden", true)
                    }
                    if (!item.sale.authorization_number) {
                        billing.attr('href', '/admin/billing/'+id)
                        billing.text('Facturar')
                        billing.off('click');
                    } else {
                        billing.removeAttr('href')
                        billing.text('Ver factura')
                        billing.click(showBill)
                        $("#modal-bill #bill").attr('src', 'https://drive.google.com/viewerng/viewer?embedded=true&url='+item.sale.url)
                    }

                    modalDetails.show()
                })
                .catch(function (error) {
                    showAlert('error', error.message)
                })
        }

        function showBill() {
            modalDetails.hide()
            modalBill.show()
        }

        function hideBill() {
            modalDetails.show()
            modalBill.hide()
        }

        function tracking(id) {
            let div_tracking = $('#tracking')
            let label_tracking = $('#modal-title-tracking')
            let status = document.getElementById('status')
            axios.get('{{ route('tracking') }}?sale_id='+id)
                .then(function (response) {
                    div_tracking.empty()
                    label_tracking.empty()
                    status.value = response.data.status
                    label_tracking.html('Tracking de pedido #'+response.data.sale_id)
                    $.each(response.data.tracking, function (key, value) {
                        div_tracking.append('' +
                            '<div class="activity-item d-flex">'+
                                '<div class="activite-label">'+ value.date +' &nbsp;</div>'+
                                '<i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>'+
                                '<div class="activity-content">'+
                                    ''+ value.icon +''+
                                '</div>'+
                            '</div>'
                        )
                    })
                })
                .catch(function (error) {
                    showAlert('error', 'Ha ocurrido un error')
                })
            document.getElementById('form-tracking').setAttribute('action', '/admin/update-tracking/' + id)

            let modal = new bootstrap.Modal(document.getElementById('modal-tracking'), {
                keyboard: false
            })
            modal.show()
        }

    </script>
@endpush
