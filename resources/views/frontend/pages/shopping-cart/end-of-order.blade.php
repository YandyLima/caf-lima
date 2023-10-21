@extends('frontend.index')
@section('content')
    <div class="container mt-5 py-4 my-3">
        <div class="container col-md-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <p class="nav-link text-info disabled" aria-current="page">
                        <i class="bi bi-check"></i>
                        Mi carrito
                    </p>
                </li>
                <li class="nav-item">
                    <p class="nav-link text-info disabled" aria-current="page">
                        <i class="bi bi-check"></i>
                        Pago
                    </p>
                </li>
                <li class="nav-item">
                    <p class="nav-link text-info disabled" aria-current="page">
                        <i class="bi bi-check"></i>
                        Fin del pedido
                    </p>
                </li>
            </ul>
        </div>

        <div class="card position-relative mt-5">
            <a href="{{ route('home') }}"
               class="position-absolute top-100 start-50 translate-middle btn btn-primary btn-lg">
                Listo
            </a>
            <div class="card-header text-center">
                <h4 class="text-uppercase">
                    Recibimos tu pedido
                    <i class="bi bi-bag-check-fill text-success fs-1"></i>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <span>Saludos: <strong>{!! $sale->user->name !!}</strong></span>
                    <span>Hemos recibido tu pedido, ahora trabajaremos en él.</span>
                    <span>Gracias por comprar con nosotros</span>
                    <span>Att: <strong>Café Lima</strong></span>
                </div>
                <hr>
                <div class="container">
                    <h3>Resumen de pedido</h3>
                    <p class="h5 mt-3 text-decoration-underline">Detalles facturación</p>
                    <div class="row">
                        <span>Fecha: {!! $sale->created_at !!}</span>
                        <span>Cliente: {!! $sale->user->name !!}</span>
                        <span>Dirección: {!! $sale->user->address !!}</span>
                        <span>Nit: {!! $sale->user->nit ? $sale->user->nit : 'CF' !!}</span>
                    </div>
                    <p class="h5 mt-3 text-decoration-underline">Productos</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Producto</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Sub total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sale->sale_details as $saleDetail)
                                <tr>
                                    <th scope="row">{!! $saleDetail->id !!}</th>
                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                <img
                                                    src="{!! Storage::disk('public')->url($saleDetail->product->images->where('type', 1)->first()->url??'') !!}"
                                                    width="30%"
                                                    alt="...">
                                            </div>
                                            <div class="col">
                                                {!! $saleDetail->product->name !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $saleDetail->amount !!}
                                    </td>
                                    <td>
                                        Q{!! $saleDetail->amount * $saleDetail->product->price !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-active">
                            <td></td>
                            <td class="text-end"><strong>Total cantidad:</strong></td>
                            <td><strong>{!! $sale->sale_details->sum('amount') !!}</strong></td>
                            <td><strong>Total: {!! $sale->amount_paid !!}</strong></td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
