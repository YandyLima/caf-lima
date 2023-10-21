@extends('frontend.index')
@section('content')
    <div class="container py-4 my-3">
        <div class="mt-5">
            <div class="container col-md-4">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <p class="nav-link text-info disabled" aria-current="page">
                            <i class="bi bi-check"></i>
                            Mi carrito
                        </p>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link active text-bg-dark">Pago</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Fin del pedido</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row mt-5 col-12">

            <div class="card col-md-8 opacity-75">
                <div class="card-body">
                    <h5>Datos de la factura</h5>
                    <div>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <x-inputs.text
                                    name="phone"
                                    label="Teléfono"
                                    value="{!! $user->phone !!}"
                                ></x-inputs.text>
                            </div>
                            <div class="col-sm-4">
                                <x-inputs.text
                                    name="nit"
                                    label="NIT"
                                    value="{!! $user->nit !!}"
                                ></x-inputs.text>
                            </div>
{{--                            <div class="col-sm-4">--}}
{{--                                <x-inputs.text--}}
{{--                                    name="dpi"--}}
{{--                                    label="DPI"--}}
{{--                                ></x-inputs.text>--}}
{{--                            </div>--}}
                            <div class="col-sm-12">
                                <x-inputs.text
                                    name="address"
                                    label="Dirección de Envío"
                                    value="{!! $user->address !!}"
                                ></x-inputs.text>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <form id="form-cash">
                        <div class="text-center">
                            <h4>IFORMACIÓN PARA REALIZAR EL PAGO</h4>
                        </div>
                        <div>
                            <p>(El pedido debe ser depositado máximo 1 día después de la orden)</p>
                            <h5>Depositar a la cuenta de ahorros Banrural a nombre de Yandy Lima, cuenta número: <strong>4649117049</strong></h5>
                            <h5>O a la cuenta monetaria de Banco Industrial a nombre de Yandy Lima, cuenta número: <strong>5370090168</strong></h5>
                            <h5>Mandar foto de la boleta de depósito o transferencia al siguiente correo: <a href="mailto:ventas.cafelima@gmail.com">ventas.cafelima@gmail.com</a></h5>
                            <p>Después de verificar el pago, el pedido será enviado</p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mt-3 px-3 py-2">
                    <div class="card-header text-center">
                        <h5>Resumen</h5>
                        <hr>
                    </div>
                    <div class="card-body">
                        <ul class="list-group border-0 opacity-50" id="shopping-container">
                            <template id="shopping-products">
                                <li class="list-group-item border-0 border-bottom">
                                    <div class="row">
                                        <div class="col-sm-3 my-auto">
                                            <img src="" width="100%" alt="...">
                                        </div>
                                        <div class="col my-auto">
                                            <div class="row">
                                                <span class="col-sm-12 fw-bold title"></span>
                                            </div>
                                        </div>
                                        <div class="col my-auto text-end">
                                            <span class="fw-bold subTotal">Q0.00</span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div class="row mt-4">
                            <h6 class="col-3 fw-bold text-start">Total</h6>
                            <h6 class="col fw-bold text-end" id="shopping-total">Q0.00</h6>
                        </div>
                        <div class="d-grid gap-2 col-12 mt-4 mx-auto">
                            <button class="btn btn-warning text-dark" onClick="finalizePurchase()"
                                    type="button" id="hide-checkout-button">
                                Finalizar compra
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            <a href="{{ route('shopping.cart') }}" class="btn btn-primary" type="button">
                                <i class="bi bi-arrow-left"></i>
                                Regresar a mi carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let products;
        let sale_id;
        let totalAmount = 0;
        let paymentMethodId;
        let items = {};
        const loaderElement = document.getElementById("loader");
        const shoppingContainer = document.querySelector('#shopping-container')
        setTimeout(function () {
            const shopping = window.shopping
            products = shopping.products

            const shoppingProducts = document.getElementById('shopping-products').content;
            const fragment = document.createDocumentFragment()

            products.forEach(product => {
                const {productId, url, name, price, total, amount} = product
                shoppingProducts.querySelector('img').setAttribute('src', url)
                shoppingProducts.querySelector('.title').textContent = name
                shoppingProducts.querySelector('.subTotal').textContent = 'Q' + total.toFixed(2)
                const clone = shoppingProducts.cloneNode(true)
                fragment.appendChild(clone)
            })
            shoppingContainer.appendChild(fragment)

            const shoppingTotal = document.getElementById('shopping-total');
            shoppingTotal.textContent = 'Q' + shopping.total.toFixed(2)
            totalAmount = shopping.total.toFixed(2)
        }, 800);

        const paymentMethod = localStorage.getItem("paymentMethod");
        if (paymentMethod === "transferencia") {
            document.querySelector("#form-card").style.display = "none";
            document.querySelector("#form-check").style.display = "none";
            document.querySelector("#form-cash").style.display = "block";
            document.querySelector("#form-test").style.display = "none";
        } else if (paymentMethod === "tarjeta") {
            document.querySelector("#form-card").style.display = "none";
            document.querySelector("#form-check").style.display = "none";
        } else if (paymentMethod === "cheque") {
            document.querySelector("#form-card").style.display = "none";
            document.querySelector("#form-check").style.display = "none";
        } else if (paymentMethod === "efectivo") {
            document.querySelector("#form-card").style.display = "none";
            document.querySelector("#form-check").style.display = "none";
            document.querySelector("#form-cash").style.display = "block";
        }

        function finalizePurchase() {
            loaderElement.classList.remove("d-none");
            Object.keys(products).forEach(key => {
                items[key] = {
                    id: products[key].productId,
                    amount: products[key].amount
                };
            });
            if (paymentMethod === "transferencia") {
                paymentMethodId = 4;
            }else if (paymentMethod === "tarjeta") {
                paymentMethodId = 3;
            } else if (paymentMethod === "cheque") {
                paymentMethodId = 2;
            } else if (paymentMethod === "efectivo") {
                paymentMethodId = 1;
            }
            generateSale();
        }

        function generateSale() {
            const paymentMethod = localStorage.getItem("paymentMethod");

            let paymentType;
            if (paymentMethod === "transferencia") {
                paymentType = "transferencia";
            } else if (paymentMethod === "deposito") {
                paymentType = "deposito";
            } else {
                // Define un valor predeterminado si no se selecciona ningún método de pago
                paymentType = "No seleccionado";
            }

            axios.post('{{ route('api-sales.store') }}', {
                withCredentials: true,
                headers: {
                    'X-XSRF-TOKEN': "{{csrf_token()}}"
                },
                'user_id': {!! $user->id !!},
                'transaction_number': '5',
                'payment_type': paymentType,
                'items': items,
                'date_generated': "{{ \Carbon\Carbon::now() }}"
            })
                .then(function (response) {
                    sale_id = response.data.sale_id;
                    showToast('Éxito', response.data.message, 'success', 'bi bi-check-circle-fill text-success fs-3 me-2');
                    localStorage.removeItem("products")
                    localStorage.removeItem("paymentMethod")
                    window.location.href = '{{ route('shopping.end-of-order', ':sale_id') }}'.replace(':sale_id', sale_id);
                })
                .catch(function (error) {
                    showToast('Error', error.response.data.message, 'danger', 'bi bi-x-circle-fill text-danger fs-3 me-2');
                })
        }
    </script>
@endpush
