<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el carrito y la dirección desde la sesión
        $cart = session()->get('cart', []);
        $addressData = session()->get('address', []);

        // Verificar si hay datos de dirección en la sesión
        if (!$addressData) {
            return back()->with('error', 'No se ha especificado una dirección.');
        }

        // Buscar si la dirección ya existe en la base de datos
        $address = Address::where('nombre', $addressData['nombre'])
                        ->where('linea_1', $addressData['linea_1'])
                        ->where('linea_2', $addressData['linea_2'] ?? null)
                        ->where('provincia', $addressData['provincia'])
                        ->where('ciudad', $addressData['ciudad'])
                        ->where('pais', $addressData['pais'])
                        ->where('codigo_postal', $addressData['codigo_postal'])
                        ->first();

        // Si no existe, crear la nueva dirección
        if (!$address) {
            $address = new Address();
            $address->nombre = $addressData['nombre'];
            $address->linea_1 = $addressData['linea_1'];
            $address->linea_2 = $addressData['linea_2'] ?? null;
            $address->provincia = $addressData['provincia'];
            $address->ciudad = $addressData['ciudad'];
            $address->pais = $addressData['pais'];
            $address->codigo_postal = $addressData['codigo_postal'];

            // Si el usuario está autenticado, guardar su ID en la dirección
            if (Auth::check()) {
                $address->id_user = Auth::id();
            }

            $address->save();
        }

        // Crear un nuevo pedido (order)
        $order = new Order();
        $order->id_address = $address->id; // Asignar la dirección obtenida o creada
        $order->fecha = now(); // Fecha actual
        $order->precio_total = $request->precio_total;

        // Si el usuario está autenticado, guardar su ID en el pedido
        if (Auth::check()) {
            $order->id_user = Auth::id();
        }

        $order->save();

        return redirect()->route('home')->with('success', 'Pedido actualizado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ORder $oRder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ORder $oRder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ORder $oRder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ORder $oRder)
    {
        //
    }

    public function getAdminList(){
        if (auth()->user()->role !== 'admin') {
            // Si el usuario no es admin, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $orders = Order::where('estatus', 0)
        ->orderBy('fecha', 'desc')
        ->get();

        return view('orders.adminList')->with(['orders'=>$orders]);
    }
}
