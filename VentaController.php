<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Venta;
use App\Models\detalleVenta;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Salida;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with('cliente')->orderBy('idVenta', 'DESC')->get();
        $user_data_DB = Auth::user();
        $user_DB = $user_data_DB->name;
        return view('Ventas.index')->with('v', $ventas)->with('vendedor', $user_DB);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_data_DB = Auth::user();
        $user_DB = $user_data_DB->name;
        return view('ventas.create')->with('vendedor', $user_DB);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'idCl' => ['required', 'int'],
                'valTotal' => ['required', 'numeric']
            ]);
            $user_idDB = Auth::id();
            $obj = new Venta();
            $obj->cliente_id = Controller::limpiarCadena($request->get('idCl'));
            $obj->total_fact = Controller::limpiarCadena($request->get('valTotal'));
            $obj->observaciones = Controller::limpiarCadena($request->get('observaciones'));
            $obj->estado_v = 'CERRADA';
            $obj->user_id = $user_idDB;
            $obj->save();

            $idVenta = $obj->idVenta;
            try {
                // Obtener los datos del formulario
                $id_pd = $request->input('idProducto');
                $cod_pd = $request->input('cod_pd');
                $valor_pd = $request->input('valor_pd');
                $cant = $request->input('cant');
                $valor_neto = $request->input('valor_neto');
                $fechaActual = date('Y-m-d');
                // Iterar sobre los arrays e insertar los datos en la base de datos
                for ($i = 0; $i < count($cod_pd); $i++) {
                    detalleVenta::create([
                        'venta_id' => $idVenta,
                        'producto_id' => $id_pd[$i],
                        'valor_v' => $valor_pd[$i],
                        'cantidad_v' => $cant[$i],
                        'neto_v' => $valor_neto[$i],
                        'user_id' => $user_idDB
                    ]);
                    Salida::create([
                        'producto_id' => $id_pd[$i],
                        'motivo_sal' => 'Venta',
                        'fecha_sal' => $fechaActual,
                        'cantidad_sal' => $cant[$i],
                        'user_id' => $user_idDB
                    ]);
                    $idLimp = Controller::limpiarCadena($id_pd[$i]);
                    $obj = Producto::find($idLimp);
                    $obj->existencia_pd -= $cant[$i];
                    $obj->save();
                }
            } catch (ValidationException $e) {
                Log::error($e->getMessage());
                return redirect()->back()->withErrors($e->validator->errors())->withInput();
            }

            return redirect('/ventas/');
        } catch (ValidationException $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function invoice($id)
    {
        $e = Empresa::all();
        $v = Venta::join('Clientes', 'cliente_id', '=', 'Clientes.idCliente')
            ->join('users', 'ventas.user_id', '=', 'users.id')
            ->where('idVenta', $id)
            ->select('users.name', 'ventas.*', 'clientes.*')
            ->get();
        $d = detalleVenta::join('Productos', 'producto_id', '=', 'Productos.idProducto')
            ->where('venta_id', $id)
            ->get();
        //echo $v;
        return view('ventas.invoice')->with('v', $v)->with('d', $d)->with('e', $e);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(venta $venta)
    {
        //
    }

    public function getDataVentasPorAnio()
    {
        $anioActual = date('Y');
        try {
            $ventasPorMes = DB::table('ventas')
                ->select(
                    DB::raw('YEAR(fecha_v) as anio'),
                    DB::raw('MONTH(fecha_v) as mes'),
                    DB::raw('SUM(total_fact) as ventas_totales')
                )
                ->whereYear('fecha_v', $anioActual)
                ->groupBy(DB::raw('YEAR(fecha_v)'), DB::raw('MONTH(fecha_v)'))
                ->orderBy(DB::raw('YEAR(fecha_v)'), 'asc')
                ->orderBy(DB::raw('MONTH(fecha_v)'), 'asc')
                ->get();
            return response()->json($ventasPorMes);
        } catch (QueryException $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Error al realizar la consulta'], 500);
        }
    }

    //Trae data del grafico días de ventas
    public function getDataVentasPorDiaSemana()
    {
        try {
            $ventasPorDia = DB::table('ventas')
                ->select(
                    DB::raw('DAYOFWEEK(fecha_v) as dia_semana'),
                    DB::raw('SUM(total_fact) as total_ventas')
                )
                ->whereBetween('fecha_v', [
                    now()->startOfWeek(), // lunes
                    now()->endOfWeek()    // domingo
                ])
                ->groupBy(DB::raw('DAYOFWEEK(fecha_v)'))
                ->orderBy(DB::raw('DAYOFWEEK(fecha_v)'))
                ->get();

            return response()->json($ventasPorDia);
        } catch (QueryException $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Error al obtener datos por día'], 500);
        }
    }


    public function generarTicket($idVenta)
    {
        $empresa = Empresa::first();
        $venta = Venta::with(['cliente', 'detalles.producto'])->find($idVenta);

        if (!$venta) {
            return redirect()->back()->with('error', 'La venta no existe.');
        }

        // Calcular totales
        $total = $venta->detalles->sum(function ($detalle) {
            return $detalle->cantidad_v * $detalle->valor_v;
        });
        $iva = $total * 0.19;
        $bruto = $total - $iva;

        // Datos para el ticket
        $datos = [
            'empresa' => $empresa,
            'venta' => $venta,
            'subtotal' => $bruto,
            'iva' => $iva,
            'total' => $total,
        ];

        // Generar el PDF
        $pdf = Pdf::loadView('ventas.ticket', $datos)->setPaper([20, 5, 170, 600], 'portrait');

        // Descargar el PDF
        //$pdf->download('ticket.pdf');

        // Opcional: Mostrar el PDF en el navegador
        return $pdf->stream('ticket.pdf');
    }
}
