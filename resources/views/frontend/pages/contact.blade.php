@extends('frontend.index')

@push('styles')
    <style>
        .bg-image {
            position: relative;
            height: 92vh;
        }

        .bg-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('assets/img/covers/cover-contact.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: blur(5px) brightness(0.5);
        }

        .content {
            z-index: 1;
            padding: 5rem;
        }
    </style>
@endpush

@section('content')

    <div class="row text-dark">
        <div class="bg-image row">
            <div class="content row">
                <div class="col-md-8 my-auto">
                    <div class="row text-light">
                        <span class="display-4 col-md-12 fw-bold">Contacto - Descubre nuestro café</span>
                        <span class="fs-5 col-9">
                        ¿Tienes alguna pregunta o sugerencia sobre nuestro café? <br>
                        Contáctanos a través de nuestro formulario en línea o nuestras redes sociales. Estamos listos
                        para ayudarte y compartir nuestra pasión por el café contigo.
                    </span>
                    </div>
                </div>
                <div class="col-md-4 my-auto">
                    <form method="POST" action="{{route('contact.send-email')}}" id="contact-form"
                          class="card bg-transparent text-light">
                        @csrf
                        <div class="card-header text-center">
                            <h3>Contáctanos</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <x-inputs.group>
                                    <x-inputs.email
                                        name="email"
                                        label="Correo Electrónico"
                                        class="bg-transparent shadow-none border-secondary text-light"
                                    />
                                    <div id="emailHelp" class="text-light">
                                        Nunca compartiremos tú correo electrónico con nadie más.
                                    </div>
                                </x-inputs.group>
                                <x-inputs.group>
                                    <x-inputs.textarea
                                        name="message"
                                        label="Mensaje"
                                        class="bg-transparent shadow-none border-secondary text-light"
                                    />
                                </x-inputs.group>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Enviar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="container mt-5 py-5">
            <div class="card col-md-7 mx-auto mt-3 shadow-sm p-3">
                <div class="card-group">
                    @forelse($settings as $setting)
                        <div class="col-sm-4">
                            <div class="card-body pt-0">
                                <div class="card m-1">
                                    {!! $setting->icon ?? '' !!}
                                    <div class="card-body">
                                        <h5 class="card-title"></h5>
                                        <ul class="text-center list-group">
                                            @foreach($setting->links as $link)
                                                <li class="list-group-item border-0 p-0 m-0">
                                                    @if ($link['url'])
                                                        @if (!Str::startsWith($link['url'], 'https://') && !Str::startsWith($link['url'], 'http://'))
                                                            <a href="{{ "https://".$link['url'] }}" target="_blank"
                                                               class="text-decoration-none"
                                                               rel="noopener noreferrer">{!! $link['name'] !!}</a>
                                                        @else
                                                            <a href="{{ $link['url'] }}" target="_blank"
                                                               class="text-light-emphasis"
                                                               rel="noopener noreferrer">{!! $link['name'] !!}</a>
                                                        @endif
                                                    @else
                                                        <span>{!! $link['name'] !!}</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('contact-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const loaderElement = document.getElementById("loader");
            loaderElement.classList.remove("d-none");
            const formData = new FormData(event.target);
            axios.post(event.target.action, formData)
                .then(response => {
                    const emailInput = document.querySelector('#email');
                    const messageInput = document.querySelector('#message');
                    emailInput.value = '';
                    messageInput.value = '';
                    showToast('Éxito', response.data, 'success', 'bi bi-check-circle-fill text-success fs-3 me-2');
                    loaderElement.classList.add("d-none");
                })
                .catch(error => {
                    loaderElement.classList.add("d-none");
                    showToast('Error', error.response.data.message, 'danger', 'bi bi-x-circle-fill text-danger fs-3 me-2');
                });
        });
    </script>
@endpush
