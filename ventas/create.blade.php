<!DOCTYPE html>
@extends('adminlte::page')

@section('title', 'Ventas')
@section('content_header')
<h1>Datos de la Factura</h1>
<style>
    #flexSwitchCheckDefault {
        width: 40px;
        height: 20px;
    }

    .colored-toast.swal2-icon-success {
        background-color: #a5dc86 !important;
    }

    .colored-toast.swal2-icon-error {
        background-color: #f27474 !important;
    }

    .colored-toast.swal2-icon-warning {
        background-color: #f8bb86 !important;
    }

    .colored-toast.swal2-icon-info {
        background-color: #3fc3ee !important;
    }

    .colored-toast.swal2-icon-question {
        background-color: #87adbd !important;
    }

    .colored-toast .swal2-title {
        color: white;
    }

    .colored-toast .swal2-close {
        color: white;
    }

    .colored-toast .swal2-html-container {
        color: white;
    }
</style>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="modal-title">Nueva Venta - Vendedor: {{$vendedor}}</h4>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <p>
            <center>
                <form action="/ventas" method="post" id="myFormVenta">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-body">
                            {{ csrf_field() }}
                            <div class="row g-3 mb-3 rounded">
                                <input type="hidden" name="idCl" id="idCl" value="">
                                <div class="col-md-3">
                                    <label for="dni_cl" class="form-label">DNI Cliente</label>
                                    <input type="number" class="form-control" name="dni_cl" id="dni_cl" placeholder="CC" tabindex="1" value="2222222222">
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre_cl" class="form-label">Nombres del Cliente</label>
                                    <input type="text" class="form-control" name="nombre_cl" id="nombre_cl" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="tel_cl" class="form-label">Telefono</label>
                                    <input type="number" class="form-control" name="tel_cl" id="tel_cl" readonly>
                                </div>
                            </div><!-- fin div row -->
                        </div><!-- fin div card-body head -->
                    </div><!-- fin div card head -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Cód. Barra
                                                    <a href="#searchProductoModal" data-toggle="modal" class="btn btn-sm btn-info" id="modalProductos" title="Search productos por nombre"><i class="fas fa-list"></i></a>
                                                </th>

                                                <th>Nombre del Producto</th>
                                                <th>Valor</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="productRows" class="table-bordered rounded">
                                                <input type="hidden" name="idProducto[]" id="idProducto" value="">
                                                <td><input type="text" class="form-control" name="cod_pd[]" id="cod_pd" placeholder="123456"></td>
                                                <td>
                                                    <input type="text" class="form-control" name="nombre_pd[]" id="nombre_pd" readonly>
                                                    <input type="hidden" name="stop_pd[]" id="stop_id" value="">
                                                </td>
                                                <td><input type="number" class="form-control" name="valor_pd[]" id="valor_pd"></td>
                                                <td><input type="number" class="form-control" name="cant[]" id="cant" placeholder="1" value="1"></td>
                                                <td><input type="number" class="form-control" name="valor_neto[]" id="valor_neto" readonly></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success add-row"><i class="fas fa-plus"></i></button>
                                                    <button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div><!-- fin div card-body table -->
                    </div><!-- fin div card table-->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <textarea name="observaciones" id="observaciones" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                </div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <div class="table-responsive">
                                            <label for="">Acciones Generales: </label>
                                            <a href="#" onclick="recalculateTotals()" class="btn btn-warning btn-sm"><i class="fas fa-sync-alt"></i> Recalcular</a>
                                            <button type="button" class="btn btn-primary btn-xs">
                                                <i class="fas fa-list"></i> # Filas: <span class="badge btn btn-xs" id="filas">1</span>
                                            </button>
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>
                                                    <span id="valNeto">$0</span>
                                                    <input type="hidden" name="valNeto" id="valNetoFinal" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>IVA (19%):</th>
                                                <td>
                                                    <span id="valDesc">$0</span>
                                                    <input type="hidden" name="valDesc" id="valDescuento" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td class="text-danger">
                                                    <h2><span id="valTotal">$0</span></h2>
                                                    <input type="hidden" name="valTotal" id="valTotalFinal" value="0">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div><!-- fin div card-body contadores -->
                    </div><!-- fin div card contadores-->
        </p>
        </center>
    </div><!-- /.fin div card-body -->
    <div class="card-footer">
        <center>
            <a href="../ventas" class="btn btn-warning"><i class="far fa-window-close"></i> Cerrar</a>
            <button type="button" class="btn btn-primary" onclick="resetform()">Limpiar</button>
            <a href="#" id="btnEnvio" class="btn btn-success disabled"><i class="far fa-save"></i> Guardar Información</a>
        </center>
        </form>
        <br>
        <div class="justify-content-center">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div><!-- /.fin div card-footer -->
</div><!-- /.fin div card -->

<div class="modal fade" tabindex="-1" id="searchProductoModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Buscar productos por nombre</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <p>
                            <center>
                                <div class="card">
                                    <div class="card-body">
                                        <table id="tb_prod" class="table table-striped table-bordered shadow-lg mt-4 table-responsive-xl">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th scope="col">ESTADO</th>
                                                    <th scope="col">CÓD. BARRA</th>
                                                    <th scope="col">NOMBRE PRODUCTO</th>
                                                    <th scope="col">VALOR</th>
                                                    <th scope="col">EXISTENCIA</th>
                                                    <th scope="col">PROVEEDOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="rowProdSeach">
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </center>
                        </p>
                    </div><!-- /.fin div card-body -->

                </div><!-- /.fin div card -->
            </div><!-- /.fin div modal-body -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-warning" data-dismiss="modal" id="btnCerrarModalCUPS"><i class="far fa-window-close"></i> Cerrar</button>
            </div>
            <!-- /.modal-footer -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dataService;
    let dataProduct;
    let countRow = 1;
    const formatter = new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    });

    function formatCurrency(value) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'center',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast',
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
    });
    /* (async () => {
        await Toast.fire({
            icon: 'success',
            title: 'Success',
        })
        await Toast.fire({
            icon: 'error',
            title: 'Error',
        })
        await Toast.fire({
            icon: 'warning',
            title: 'Warning',
        })
        await Toast.fire({
            icon: 'info',
            title: 'Info',
        })
        await Toast.fire({
            icon: 'question',
            title: 'Question',
        })
    })() */

    document.getElementById("dni_cl").addEventListener('blur', function() {
        var id = document.getElementById('dni_cl').value;
        const idCl = document.getElementById('idCl');
        const name = document.getElementById('nombre_cl');
        const cel = document.getElementById('tel_cl');

        if (id) {
            axios.get('{{ route("clientes.getDataCliente") }}', {
                    params: {
                        dni_cl: id
                    }
                })
                .then(function(response) {
                    if (response.status == 200 && response.data.data.length > 0) {
                        data = response.data.data;
                        idCl.value = data[0].idCliente;
                        name.value = data[0].nombres_cl;
                        cel.value = data[0].telefono_cl;
                        $('#btnEnvio').removeClass('disabled');
                    } else {
                        name.value = '';
                        cel.value = '';
                        AlertSweet("Cliente No Existe o Inactivo!", "Favor verificar la información.", "error");
                        $('#btnEnvio').addClass('disabled');
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    });

    document.getElementById('modalProductos').addEventListener('click', function() {
        //Me permite eliminar los datos y reiniciar el DataTable
        if ($.fn.DataTable.isDataTable('#tb_prod')) {
            $('#tb_prod').DataTable().clear().destroy();
        }
        //Genero el DataTable
        axios.get('{{ route("productos.getProductosActivos") }}')
            .then(function(response) {

                $("#tb_prod").DataTable({
                    data: response.data,
                    "lengthMenu": [
                        [5, 10, 20, 50, -1],
                        [5, 10, 20, 50, "All"]
                    ],
                    "order": [2, 'DESC'],
                    "columns": [{
                            "data": null,
                            render: function(data, type, row) {
                                if (data['estado_pd'] == 'ACTIVO') {
                                    return "<a href='#' id='btnProdSeach' onclick='btnProdSeach(" + data['cod_barra'] + ")' class='btn btn-sm btn-success'><i class='fas fa-bolt'></i></a>";
                                } else {
                                    return "<button class='btn btn-sm btn-danger' title='" + data['estado_pd'] + "'><i class='fas fa-times'></i></button>";
                                }
                            }
                        },
                        {
                            "data": "cod_barra"
                        },
                        {
                            "data": "descripcion_pd"
                        },
                        {
                            "data": null,
                            render: function(data, type, row) {
                                return "<div class='text-bold'>" + formatter.format(data['valor_pd']) + "</div>"
                            }
                        },
                        {
                            "data": "existencia_pd"
                        },
                        {
                            "data": "proveedor.nombre_pv"
                        }
                    ],
                    bJQueryUI: true,
                    "responsive": true,
                    "autoWidth": false,
                });
            })
            .catch(function(error) {
                console.error(error);
            });
    });

    /*     function btnProdSeach(cod) {
            dataProduct = cod; ----el modal debe dispararse directamente desde la row
        }

        document.getElementById("btnProdSeach").addEventListener('click', function() {
            const row = event.target.closest('#productRows');
            row.querySelector('#cod_pd').value = dataProduct;
        }); */

    var total = 0;
    document.addEventListener('DOMContentLoaded', (event) => {
        let productRows = document.getElementById('productRows');

        function formatCurrency(value) {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }

        function handleCodPdBlur(event) {
            const row = event.target.closest('#productRows');
            const idProducto = row.querySelector('#idProducto');
            const cod = row.querySelector('#cod_pd').value;
            const prod = row.querySelector('#nombre_pd');
            const val = row.querySelector('#valor_pd');
            const stop = row.querySelector('#stop_id');

            if (cod) {
                axios.get('{{ route("productos.codBarra") }}', {
                        params: {
                            cod_pd: cod
                        }
                    })
                    .then(function(response) {
                        if (response.status == 200 && response.data.data.length > 0) {
                            const data = response.data.data;
                            idProducto.value = data[0].idProducto;
                            prod.value = data[0].descripcion_pd;
                            val.value = Math.trunc(data[0].valor_pd);
                            stop.value = data[0].existencia_pd;
                            document.getElementById('btnEnvio').classList.remove('disabled');
                        } else {
                            idProducto.value = '';
                            prod.value = '';
                            val.value = '';
                            stop.value = '';
                            Swal.fire("Producto No Existe o Inactivo!", "Favor verificar la información.", "error");
                            document.getElementById('btnEnvio').classList.add('disabled');
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                        prod.value = '';
                        val.value = '';
                        Swal.fire("Producto No Existe o Inactivo!", "Favor verificar la información.", "error");
                        document.getElementById('btnEnvio').classList.add('disabled');
                    });
            }
        }

        function handleCantBlur(event) {
            const row = event.target.closest('#productRows');
            const cant = row.querySelector('#cant').value;
            const val = row.querySelector('#valor_pd').value;
            const stop = row.querySelector('#stop_id').value;
            const neto = row.querySelector('#valor_neto');
            const netoFinal = document.getElementById('valNetoFinal');
            const totalFinal = document.getElementById('valTotalFinal');

            if (cant > 0) {
                const stop_p = parseInt(stop, 10);
                const cant_p = parseInt(cant, 10);
                if (stop_p > cant_p) {
                    const resp = val * cant;
                    neto.value = resp;
                    total += resp;
                    netoFinal.value = total;
                    totalFinal.value = total;
                    document.getElementById('valNeto').textContent = formatCurrency(total);
                    document.getElementById('valTotal').textContent = formatCurrency(total);
                    handleDescBlur(event);
                } else {
                    Swal.fire("Stop Insuficiente", "Favor verificar la cantidad de existencia del producto ya que es menor a la cantidad solicitada para la operación.", "error");
                    neto.value = 0;
                }
            }
        }

        function handleDescBlur(event) {
            const porc = 19;

            const netoFinal = document.getElementById('valNetoFinal').value;
            const descFinal = document.getElementById('valDescuento').value;
            const totalFinal = document.getElementById('valTotalFinal').value;

            const neto = document.getElementById('valNeto');
            const desc = document.getElementById('valDesc');
            const total = document.getElementById('valTotal');

            if (porc >= 0) {
                const pporc = (porc / 100);
                const descOperacion = netoFinal * pporc;
                document.getElementById('valDescuento').value = descOperacion;
                document.getElementById('valDesc').textContent = formatCurrency(descOperacion);

                const SubtotalOperacion = netoFinal - descOperacion;
                document.getElementById('valNetoFinal').value = SubtotalOperacion;
                document.getElementById('valNeto').textContent = formatCurrency(SubtotalOperacion);

                const totalOperacion = netoFinal;
                document.getElementById('valTotalFinal').value = totalOperacion;
                document.getElementById('valTotal').textContent = formatCurrency(totalOperacion);
            } else {
                Swal.fire("Porcentaje Incorrecto", "Favor verificar el valor del porcentaje registrado, debe estar espresado de [0-100]", "error");
                neto.value = 0;
            }
        }

        function recalculateTotals() {
            total = 0; // Reiniciar el total
            document.querySelectorAll('.table-bordered.rounded').forEach(row => {
                const netoValue = parseFloat(row.querySelector('#valor_neto').value) || 0;
                total += netoValue;
            });

            const netoFinal = document.getElementById('valNetoFinal');
            const totalFinal = document.getElementById('valTotalFinal');

            netoFinal.value = total;
            totalFinal.value = total;

            document.getElementById('valNeto').textContent = formatCurrency(total);
            document.getElementById('valTotal').textContent = formatCurrency(total);

            handleDescBlur();
        }

        function addRow() {
            const newRow = productRows.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.add-row').addEventListener('click', addRow);
            newRow.querySelector('.remove-row').addEventListener('click', removeRow);
            newRow.querySelector('#cod_pd').addEventListener('blur', handleCodPdBlur);
            newRow.querySelector('#cant').addEventListener('blur', handleCantBlur);
            productRows.parentNode.insertBefore(newRow, productRows.nextSibling);
            countRow++;
            productRows = newRow; //Aqui da el error
        }

        function removeRow(event) {
            const row = event.target.closest('.table-bordered.rounded');
            if (document.querySelectorAll('.table-bordered.rounded').length > 1) {
                row.remove();
                countRow--;
                recalculateTotals();
            } else {
                alert("Debe haber al menos una fila.");
            }
        }

        // Añadir eventos a los botones de la fila inicial
        document.querySelector('.add-row').addEventListener('click', addRow);
        document.querySelector('.remove-row').addEventListener('click', removeRow);

        // Añadir manejadores de eventos a los inputs de la fila inicial
        document.getElementById('cod_pd').addEventListener('blur', handleCodPdBlur);
        document.getElementById('cant').addEventListener('blur', handleCantBlur);
    });

    function recalculateTotals() {
        let total = 0; // Reiniciar el total
        const iva = 0.19; // Iva
        document.querySelectorAll('.table-bordered.rounded').forEach(row => {
            const netoValue = parseFloat(row.querySelector('#valor_neto').value) || 0;
            total += netoValue;
        });

        const netoFinal = document.getElementById('valNetoFinal');
        const totalDesc = document.getElementById('valDescuento');
        const totalFinal = document.getElementById('valTotalFinal');
        const filas = document.getElementById('filas');

        let valIva = total * iva;
        let subtotal = total - valIva;
        netoFinal.value = subtotal;
        totalDesc.value = valIva;
        totalFinal.value = total;
        document.getElementById('valNeto').textContent = formatCurrency(subtotal);
        document.getElementById('valDesc').textContent = formatCurrency(valIva);
        document.getElementById('valTotal').textContent = formatCurrency(total);
        filas.textContent = countRow;

        /* Swal.fire({
            position: "top-end",
            icon: "info",
            title: "Venta recalculada correctamente!",
            showConfirmButton: false,
            timer: 1500
        }); */

        (async () => {
            await Toast.fire({
                icon: 'success',
                title: 'Recalculada',
            })
        })()
    }

    function AlertSweet(title, text, icon) {
        Swal.fire({
            title,
            text,
            icon
        });
    }

    function resetform() {
        $("#myForm")[0].reset();
    }

    document.getElementById('btnEnvio').addEventListener('click', function() {
        var idCliente = document.getElementById('dni_cl').value;
        const codProd = document.getElementById('cod_pd').value;
        const cant = document.getElementById('cant').value;
        const neto = document.getElementById('valor_neto').value;

        if (idCliente === '' || idCliente.length === 0) {
            document.getElementById('dni_cl').classList.add('bg-warning');
        } else {
            document.getElementById('dni_cl').classList.remove('bg-warning');
            if (codProd === '' || codProd.length === 0) {
                document.getElementById('cod_pd').classList.add('bg-warning');
            } else {
                document.getElementById('cod_pd').classList.remove('bg-warning');
                if (cant === '' || cant.length === 0) {
                    document.getElementById('cant').classList.add('bg-warning');
                } else {
                    document.getElementById('cant').classList.remove('bg-warning');
                    if (neto === '' || neto.length === 0) {
                        document.getElementById('valor_neto').classList.add('bg-warning');
                    } else {
                        document.getElementById('valor_neto').classList.remove('bg-warning');
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Información Enviada Correctamente!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        document.getElementById('myFormVenta').submit();
                    }
                }
            }
        }
    });
</script>
@stop