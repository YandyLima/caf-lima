@extends('app')
@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <div class="container text-center">
        <div class="btn-group bg-light py-4" role="group" aria-label="Basic example">
            <input type="radio" class="btn-check" name="type" id="1" autocomplete="off" checked>
            <label class="btn btn-outline-danger" for="1">Hoy</label>
            <input type="radio" class="btn-check" name="type" id="2" autocomplete="off">
            <label class="btn btn-outline-danger" for="2">Este Mes</label>
            <input type="radio" class="btn-check" name="type" id="3" autocomplete="off">
            <label class="btn btn-outline-danger" for="3">Este Año</label>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Ventas</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="sales">0</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Ingresos</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="incomes">0</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Nuevos Clientes</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6 id="customers">0</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Reporte de Ventas</h5>
                                <!-- Line Chart -->
                                <div id="reportsChart"></div>
                                <!-- End Line Chart -->
                            </div>

                        </div>
                    </div>
                    <!-- End Reports -->
                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Pedidos Recientes</h5>
                                <table class="table table-borderless datatable">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                    <!-- End Recent Sales -->
                </div>
            </div>
            <!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Budget Report -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Ventas por Producto</h5>
                        <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
                    </div>
                </div>
                <!-- End Budget Report -->
                <!-- Website Traffic -->
                <div class="card">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Compras</h5>
                        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                    </div>
                </div>
                <!-- End Website Traffic -->
            </div>
            <!-- End Right side columns -->
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            getData()

            const radios = document.getElementsByName('type');
            radios.forEach(radio => {
                radio.addEventListener('click', () => {
                    getData(radio.id)
                })
            })

            function getData(type = 1) {
                $.ajax({
                    type: 	 'GET',
                    url: 	 '/admin/dashboard/data', //dashboard.data
                    data: { type : type},
                    success: function (res){
                        $('#sales').text(res.sales)
                        $('#incomes').text(res.sales_money)
                        $('#customers').text(res.customers)

                        //Grafica de ventas por producto
                        let indicators = getLabelsBudget(res.graphics.sales_by_product.labels)
                        echarts.init(document.querySelector("#budgetChart")).setOption({
                            legend: {
                                data: ['Cantidad']
                            },
                            radar: {
                                indicator: indicators
                            },
                            series: [{
                                type: 'radar',
                                data: [
                                    {
                                        value: res.graphics.sales_by_product.values
                                    }
                                ]
                            }]
                        })

                        //Grafica de compras
                        let data = getDataPie(res.graphics.purchases)
                        echarts.init(document.querySelector("#trafficChart")).setOption({
                            tooltip: {
                                trigger: 'item'
                            },
                            legend: {
                                top: '5%',
                                left: 'center'
                            },
                            series: [{
                                name: 'Compras',
                                type: 'pie',
                                radius: ['40%', '70%'],
                                avoidLabelOverlap: false,
                                label: {
                                    show: false,
                                    position: 'center'
                                },
                                emphasis: {
                                    label: {
                                        show: true,
                                        fontSize: '18',
                                        fontWeight: 'bold'
                                    }
                                },
                                labelLine: {
                                    show: false
                                },
                                data: data
                            }]
                        })

                        //Grafica de ventas
                        $("#reportsChart").empty()
                        new ApexCharts(document.querySelector("#reportsChart"), {
                            series: [{
                                name: 'Ventas',
                                data: res.graphics.sales.values,
                            }],
                            chart: {
                                height: 350,
                                type: 'area',
                                toolbar: {
                                    show: false
                                },
                            },
                            markers: {
                                size: 4
                            },
                            colors: ['#2eca6a'],
                            fill: {
                                type: "gradient",
                                gradient: {
                                    shadeIntensity: 1,
                                    opacityFrom: 0.3,
                                    opacityTo: 0.4,
                                    stops: [0, 90, 100]
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2
                            },
                            xaxis: {
                                categories:  res.graphics.sales.labels
                            },
                        }).render();

                        appendTable(res.graphics.sales_table)
                    },
                    error: function(err){}
                })
            }

            //Convertir labels a object
            function getLabelsBudget(sales) {
                let labels = []
                $.each(sales, function (index, value) {
                    let label = {name: value}
                    labels.push(label)
                })
                return labels
            }

            //Convertir purchases a object
            function getDataPie(purchases) {
                let data = []
                $.each(purchases, function (index, value) {
                    let item = {
                        name: value.description,
                        value: value.price,
                    }
                    data.push(item)
                })
                return data
            }

            function appendTable(sales) {
                $('#table-body').empty()

                let statuses = {!! json_encode($statuses) !!}
                $.each(sales, function (index, value) {
                    let fecha = new Date(value.created_at);
                    let dia = fecha.getUTCDate().toString().padStart(2, '0');
                    let mes = (fecha.getUTCMonth() + 1).toString().padStart(2, '0');
                    let anio = fecha.getUTCFullYear().toString();
                    let fechaFormateada = dia + '/' + mes + '/' + anio;

                    $('#table-body').append(`
                        <tr>
                            <th scope="row">${value.id}</th>
                            <td>${value.customer.name}</td>
                            <td>Q${value.amount_paid}</td>
                            <td>${fechaFormateada}</td>
                            <td>${statuses[value.status]}</td>
                        </tr>
                    `)
                })
            }
        })


    </script>
@endpush
