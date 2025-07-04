<!DOCTYPE html>
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <section class="connectedSortable">
            <div class="card card-primary">
                <div class="card-body">
                    <center>
                        <img src="./LogoCompany.svg" alt="img" width="230" height="110">
                    </center>
                </div>
            </div>
        </section>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-yellow">
            <div class="inner">
                <h3><?php echo $proveedores; ?></h3>
                <p>Proveedores</p>
            </div>
            <div class="icon">
                <i class="fas fa-industry"></i>
            </div>
            <a href="/proveedores" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-green">
            <div class="inner">
                <h3><?php echo $productos; ?></h3>
                <p>Productos</p>
            </div>
            <div class="icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="/productos" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3><?php echo $categorias; ?></h3>
                <p>Categorias</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <a href="/categorias" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-red">
            <div class="inner">
                <h3><?php echo $ventas; ?></h3>
                <p>Ventas</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="/ventas" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-blue">
            <div class="inner">
                <h3><?php echo $clientes; ?></h3>
                <p>Clientes</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="/clientes" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-indigo">
            <div class="inner">
                <h3><?php echo $empresa = 1; ?></h3>
                <p>Empresa</p>
            </div>
            <div class="icon">
                <i class="far fa-building"></i>
            </div>
            <a href="/empresa" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="small-box bg-gradient-secondary">
            <div class="inner">
                <h3><?php echo $reportes = 5; ?></h3>
                <p>Reportes</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="/reportes" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Gráfica No. 1</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="ventasPorDiaChart" style="min-height: 250px; max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Gráfica No. 2</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php echo $r = Date('Y'); ?>
                <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 418px;" width="418" height="250" class="chartjs-render-monitor">
            </div>
        </div>
    </div>

</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        //Peticion para obtener la data del grafico ventas por meses
        axios.get('{{ route("ventas.getDataVentasPorAnio") }}', {})
            .then(function(response) {
                if (response.status == 200 && response.data.length > 0) {
                    data = response.data;
                    const cValor = document.getElementById('lineChart');
                    new Chart(cValor, {
                        type: 'bar',
                        data: {
                            labels: mesesEnLetra(data.map(row => row.mes)),
                            datasets: [{
                                label: 'Valor Ventas Realizadas',
                                data: data.map(row => row.ventas_totales),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    AlertSweet("No hay data para el grafico!", "Favor verificar la información.", "error");
                }
            })
            .catch(function(error) {
                console.error(error);
            });

        function mesesEnLetra(mesesNumericos) {
            const meses = [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ];

            return mesesNumericos.map((mes) => {
                if (mes >= 1 && mes <= 12) {
                    return meses[mes - 1];
                } else {
                    return 'Mes inválido';
                }
            });
        };

        //Peticion para obtener la data del grafico getVentasPorSemana
        axios.get('{{ route("ventas.getDataVentasPorDia") }}')
            .then(function(response) {
                if (response.status == 200 && response.data.length > 0) {
                    const data = response.data;
                    //console.log(data);
                    const diasSemana = [
                        'Domingo', // 1
                        'Lunes', // 2
                        'Martes',
                        'Miércoles',
                        'Jueves',
                        'Viernes',
                        'Sábado'
                    ];

                    const labels = data.map(d => diasSemana[d.dia_semana - 1]);
                    const valores = data.map(d => d.total_ventas);

                    const ctx = document.getElementById('ventasPorDiaChart');
                    let delayed;
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Ventas por Día de la Semana',
                                data: valores,
                                backgroundColor: 'rgba(244,159,10, 1)', //rgba(54, 162, 235, 0.7)
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            animation: {
                                onComplete: () => {
                                    delayed = true;
                                },
                                delay: (context) => {
                                    let delay = 0;
                                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                                    }
                                    return delay;
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    console.warn("No hay datos para graficar ventas por día.");
                }
            })
            .catch(function(error) {
                console.error("Error al obtener ventas por día:", error);
            });
    });
</script>
@stop
