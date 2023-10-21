@extends('frontend.index')
@push('styles')
    <style>
        .header {
            margin-top: 3rem;
            height: 88vh;
        }

        .img-header {
            width: 37rem;
        }

        .bg-moss-green {
            background: #c16737;
        }

        .bg-green-gray {
            background: #48514C;
        }

        @media (min-width: 1920px) {
            .header {
                margin-top: 2rem;
            }
        }

        @media (max-width: 1250px) {
            .img-header {
                width: 35rem;
            }

            .header {
                margin-top: 2rem;
                height: 90vh;
            }
        }

        @media (max-width: 820px) {
            .img-header {
                width: 21rem;
            }

            .header {
                margin-top: 4rem;
                height: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container header d-flex align-items-center">
        <div class="row">
            <div class="col-sm-6">
                <img
                    src="{{ asset('assets/img/covers/redondo.png') }}"
                    class="img-header"
                    alt="">
            </div>
            <div class="col-sm-6 text-start p-sm-3">
                <div class="row">
                    <p class="fs-5 fw-bold">
                        Productos de la mejor calidad
                    </p>
                    <p class="display-3 fw-bold">
                        ¡Disfruta del sabor y aroma con nuestro café en grano y molido!
                    </p>
                    <p>
                        Nuestro café es cuidadosamente seleccionado y tostado para resaltar su sabor y
                        aroma. Disfruta del café perfecto con nuestra selección de granos o café molido, para una
                        experiencia excepcional en cada taza.
                    </p>
                    <a href="#products" class="btn btn-success col-sm-3 py-2">
                        <i class="bi bi-cart-fill"></i>
                        Compra ahora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-moss-green p-4 p-sm-3 mt-0">
        <div class="row justify-content-center">
            <div class="card bg-green-gray shadow-none text-light col-md-5 col-lg-4 col-xl-2 col-xxl-2 m-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <i class="bi bi-truck fs-1 text-success"></i>
                        </div>
                        <div class="col">
                            <h5 class="card-title">Envío</h5>
                            <p class="card-text">Envíos a toda Guatemala</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-green-gray shadow-none text-light col-md-5 col-lg-4 col-xl-3 col-xxl-2 m-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
                        </div>
                        <div class="col">
                            <h5 class="card-title">Producto Natural</h5>
                            <p class="card-text">100% natural garantizado</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-green-gray shadow-none text-light col-md-5 col-lg-4 col-xl-3 col-xxl-2 m-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <i class="bi bi-cash fs-1 text-success"></i>
                        </div>
                        <div class="col">
                            <h5 class="card-title">Grandes ahorros</h5>
                            <p class="card-text">Al precio más bajo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-5  container" id="products">
        <h3 class="text-center display-6">Productos</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4 text-center">
            @foreach($products as $product)
                <div class="col">
                    <div class="card">
                        <div>
                            <img
                                src="{!! Storage::disk('public')->url($product->images->where('type', 1)->first()->url ?? '') !!}"
                                class="w-25" alt="...">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{!! $product->name !!}</h5>
                            <h5 class="card-text">Q{!! $product->price !!}</h5>
                            <div class="btn-group" role="group">
                                <button onClick="getImageGallery({{ $product->id }})" class="btn">
                                    <i class="bi bi-images text-secondary fs-2"></i>
                                </button>
                                <button class="btn btn-remove-product" id="product-remove-{{ $product->id }}"
                                        onClick="removeProduct({{ $product->id }})">
                                    <i class="bi bi-dash-circle-fill text-danger fs-2"></i>
                                </button>
                                <button class="btn btn-add-product" id="product-{{ $product->id }}"
                                        onClick="addProduct({{ $product->id }})">
                                    <span class="fs-4" id="amount-{{ $product->id }}">0</span>
                                    <i class="bi bi-plus-circle-fill text-primary fs-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal fade" id="modal-edit" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-3 px-4 py-2">
                    <div class="modal-header">
                        <h5 class="modal-title">Galeria de imágenes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body align-content-center" id="container-images">
                        <template id="template-images">
                            <img src="" alt="..." class="img-fluid" style="width: 30%">
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const containerImages = document.querySelector('#container-images')
        const template = document.querySelector('#template-images').content

        async function getImageGallery(productId) {
            try {
                const response = await axios.get('{{ route('products.imageGallery', ':id') }}'.replace(':id', productId));
                const data = response.data.data
                containerImages.innerHTML = ''
                const fragment = document.createDocumentFragment()
                data.forEach(image => {
                    template.querySelector('img').setAttribute('src', image.attributes.url)
                    const clone = template.cloneNode(true)
                    fragment.appendChild(clone)
                })
                containerImages.appendChild(fragment)
                if (containerImages.innerHTML === '') {
                    containerImages.innerHTML = `<p class="display-6 text-center mt-5">No se encontraron resultados ...</h3>`
                }
                let modal = new bootstrap.Modal(document.getElementById('modal-edit'), {
                    keyboard: false
                })
                modal.show()
            } catch (error) {
                console.error(error);
            }
        }

        function updateProductAmount(productId) {
            const productAmountEl = document.querySelector(`#amount-${productId}`);
            const products = JSON.parse(localStorage.getItem("products")) || {};
            const product = products[productId];
            productAmountEl.textContent = product ? product.amount : 0;
        }

        function updateRemoveButtonVisibility(productId) {
            const products = JSON.parse(localStorage.getItem("products")) || {};
            const product = products[productId];
            const removeButtonEl = document.getElementById(`product-remove-${productId}`);
            if (removeButtonEl) {
                const productCount = product && product.amount > 0 ? product.amount : 0;
                removeButtonEl.style.display = productCount > 0 ? "inline-block" : "none";
            }
        }

        window.addEventListener('load', function () {
            const products = JSON.parse(localStorage.getItem('products')) || {};
            const removeButtons = document.querySelectorAll('.btn-remove-product');
            removeButtons.forEach(function (button) {
                const productId = button.id.split('-')[2];
                if (!products[productId]) {
                    button.style.display = 'none';
                }
            });
        });

        const addProductButtons = document.querySelectorAll(".btn-add-product");
        addProductButtons.forEach((button) => {
            const productId = button.id.split("-")[1];
            updateProductAmount(productId);
        });

        function addProduct(productId) {
            const products = JSON.parse(localStorage.getItem("products")) || {};
            if (products[productId]) {
                products[productId].amount++;
            } else {
                products[productId] = {productId, amount: 1};
            }
            localStorage.setItem("products", JSON.stringify(products));
            updateProductAmount(productId);
            updateCartCount()
            productCalculation();
            updateRemoveButtonVisibility(productId);
        }

        function removeProduct(productId) {
            const products = JSON.parse(localStorage.getItem("products")) || {};
            if (products[productId]) {
                products[productId].amount--;
                if (products[productId].amount <= 0) {
                    delete products[productId];
                }
                localStorage.setItem("products", JSON.stringify(products));
                updateProductAmount(productId);
                updateCartCount();
                productCalculation();
                updateRemoveButtonVisibility(productId);
            }
        }
    </script>
@endpush
