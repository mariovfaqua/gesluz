<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()) {
            // Si el usuario no es admin, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $pendientes = Order::where('estatus', 0)
        ->where('id_user', auth()->id())
        ->orderBy('fecha', 'desc')
        ->get();

        $completados = Order::where('estatus', 1)
        ->where('id_user', auth()->id())
        ->orderBy('fecha', 'desc')
        ->paginate(20);

        return view('orders.list')->with(['pendientes'=>$pendientes, 'completados'=>$completados]);
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

        DB::beginTransaction(); // Iniciar transacción

        try{
            // Buscar si la dirección ya existe en la base de datos
            $address = Address::where('nombre', $addressData['nombre'])
                            ->where('linea_1', $addressData['linea_1'])
                            ->where('linea_2', $addressData['linea_2'] ?? null)
                            ->where('provincia', $addressData['provincia'])
                            ->where('ciudad', $addressData['ciudad'])
                            ->where('pais', $addressData['pais'])
                            ->where('codigo_postal', $addressData['codigo_postal'])
                            ->when(auth()->check(), function ($query) {
                                return $query->where('id_user', auth()->id());
                            })
                            ->first();

            // Si no existe o no está asociada al usurario, crear la nueva dirección
            if (!$address || (auth()->check() && auth()->id() !== $address->id_user)) {
                $address = new Address();
                $address->nombre = $addressData['nombre'];
                $address->linea_1 = $addressData['linea_1'];
                $address->linea_2 = $addressData['linea_2'] ?? null;
                $address->provincia = $addressData['provincia'];
                $address->ciudad = $addressData['ciudad'];
                $address->pais = $addressData['pais'];
                $address->codigo_postal = $addressData['codigo_postal'];

                // Si el usuario está autenticado, guardar su ID en la dirección nueva
                if (auth()->check()) {
                    $address->id_user = auth()->id();
                }

                $address->save();
            }

            // Crear un nuevo pedido (order)
            $order = new Order();
            $order->id_address = $address->id; // Asignar la dirección obtenida o creada
            $order->fecha = now();

            // Convertir el precio total a formato numérico correcto
            $order->precio_total = floatval(str_replace(',', '', $request->precio_total));

            // Si el usuario está autenticado, guardar su ID en el pedido
            if (Auth::check()) {
                $order->id_user = Auth::id();
            }

            $order->save();

            // Actualizar el stock de los items según el carrito
            $orderItems = [];
            foreach ($cart as $cartItem) {
                $item = $cartItem['item'];
                if ($item instanceof \App\Models\Item) { // Verificar que realmente es una instancia de Item
                    $item->stock -= $cartItem['cantidad'];
                    $item->save();

                    // Añadir al array de sincronización
                    $orderItems[$item->id] = ['cantidad' => $cartItem['cantidad']];
                }
            }

            // Sincronizar la tabla order_items
            $order->items()->sync($orderItems);

            // Vaciar la sesión después de procesar el pedido
            session()->forget('cart');
            session()->forget('address');

            DB::commit(); // Confirmar la transacción
            return redirect()->route('home')->with('success', 'Pedido actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!auth()->check()) {
            // Si el usuario no está autenticado, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Recuperar el pedido por ID junto con la dirección
        $order = Order::with('address')->findOrFail($id);

        // Verificar si el usuario es admin o si el pedido le pertenece
        if (auth()->user()->role !== 'admin' && $order->id_user !== auth()->user()->id) {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Recuperar los id_item y cantidades de la tabla order_items
        $cantidades = DB::table('order_items')
            ->where('id_order', $id)
            ->pluck('cantidad', 'id_item'); // Devuelve un array: [id_item => cantidad]

        // Obtener los detalles de los items
        $items = Item::whereIn('id', array_keys($cantidades->toArray()))->get();

        // Añadir la cantidad a cada item
        foreach ($items as $item) {
            $item->cantidad = $cantidades[$item->id]; // Asignar la cantidad recuperada
        }

        return view('orders.detail')->with(['order'=>$order, 'items'=>$items]);
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
    public function update(Order $order)
    {
        if (!auth()->user() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }
    
        try {
            // Establecer el estatus del pedido como completado
            $order->estatus = true;
            $order->save();
    
            return redirect()->back()->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un problema al actualizar el pedido.');
        }
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        try {
            // Eliminar las relaciones con items en order_items
            $order->items()->detach();

            // Eliminar el pedido
            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Pedido eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Hubo un problema al eliminar el pedido.');
        }
    }

    public function getAdminList(){
        if (!auth()->check() && auth()->user()->role !== 'admin') {
            // Si el usuario no es admin, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $pendientes = Order::where('estatus', 0)
        ->orderBy('fecha', 'desc')
        ->get();

        $completados = Order::where('estatus', 1)
        ->orderBy('fecha', 'desc')
        ->paginate(20);

        return view('orders.list')->with(['pendientes'=>$pendientes, 'completados'=>$completados]);
    }

}
