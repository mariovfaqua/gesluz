<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Item;
use App\Models\Order_Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class OrderController extends Controller
{
    /**
     * Mostrar listado de los pedidos asociados a un usuario.
     */
    public function index()
    {
        // Si el usuario no está autenticado, redirigir con un mensaje de error
        if (!auth()->check()) {
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
     * Función 'create' sin usar.
     */
    public function create()
    {
        return back();
    }

    /**
     * Guardar un nuevo pedido en la base de datos.
     */
    // public function store(Request $request)
    // {
    //     // Si el usuario no está autenticado, redirigir con un mensaje de error
    //     if (!auth()->check()) {
    //         return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
    //     }

    //     // Obtener el usuario, carrito y la dirección desde la sesión
    //     $user = auth()->user();
    //     $cart = session()->get('cart', []);
    //     $addressData = session()->get('address', []);

    //     // Verificar si existen los datos necesarios
    //     if (($request->has('send_home') && !$addressData) || !$cart) {
    //         return back()->with('error', 'No se ha podido completar la operación.');
    //     }

    //     DB::beginTransaction(); // Iniciar transacción
    //     try{
    //         // Crear un nuevo pedido (order)
    //         $order = new Order();
    //         $order->id_user = $user->id; 
    //         $order->fecha = now();
    //         // Convertir el precio total al formato numérico correcto
    //         $order->precio_total = floatval(str_replace(',', '', $request->precio_total));

    //         // Añadir la dirección
    //         if ($request->has('send_home')) {
    //             // Determinar si la dirección ya existe o se creará una nueva
    //             if (isset($addressData['id']) && $user->addresses()->where('id', $addressData['id'])->exists()) {
    //                 // La dirección ya existe y es del usuario
    //                 $address = Address::find($addressData['id']);
    //             } else {
    //                 // Es una dirección nueva
    //                 $address = $user->addresses()->create($addressData);
    //             }

    //             $order->id_address = $address->id; // Asignar la dirección obtenida o creada
    //         }

    //         $order->save();

    //         // Actualizar la tabla order_items
    //         $orderItems = [];
    //         foreach ($cart as $cartItem) {
    //             $item = $cartItem['item'];
    //             if ($item instanceof \App\Models\Item) { // Verificar que realmente es una instancia de Item

    //                 // Añadir al array de sincronización
    //                 $orderItems[$item->id] = ['cantidad' => $cartItem['cantidad']];
    //             }
    //         }

    //         // Sincronizar la tabla order_items
    //         $order->items()->sync($orderItems);

    //         // Vaciar la sesión después de procesar el pedido
    //         session()->forget('cart');
    //         session()->forget('address');

    //         // Enviar correo de confirmación
    //         Mail::to($user->email)->send(new OrderMail($order, $user));

    //         DB::commit(); // Confirmar la transacción
    //         return redirect()->route('home')->with('success', 'Pedido actualizado correctamente.');

    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Revertir la transacción en caso de error
    //         return back()->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
    //     }
    // }

    /**
     * Mostrar el detalle de un pedido.
     */
    public function show($id)
    {
        // Verificar si el usuario es admin o si el pedido le pertenece
        if (!auth()->check() || (auth()->user()->role !== 'admin' && $order->id_user !== auth()->id())) {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Recuperar el pedido por ID junto con la dirección
        $order = Order::findOrFail($id);
        $address = $order->address;

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

        return view('orders.detail')->with(['order'=>$order, 'address'=>$address, 'items'=>$items]);
    }

    /**
     * Función 'edit' sin usar.
     */
    public function edit(ORder $oRder)
    {
        return back();
    }

    /**
     * Marcar un pedido como 'completado' y actualizar stock de los items.
     */
    public function update(Order $order)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }
    
        try {
            // Establecer el estatus del pedido como completado
            $order->estatus = true;
            $order->save();

            // Recuperar items del pedido
            $orderItems = Order_Item::where('id_order', $order->id)->get();

            // Recorrer los order_items y actualizar el stock de los items relacionados
            foreach ($orderItems as $orderItem) {
                $item = Item::find($orderItem->id_item);

                if ($item) {
                    // Restar la cantidad del stock
                    $item->stock -= $orderItem->cantidad;
                    $item->save();
                }
            }
    
            return redirect()->back()->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un problema al actualizar el pedido.');
        }
    }    

    /**
     * Eliminar el pedido de la base de datos.
     */
    public function destroy(Order $order)
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        try {
            // Eliminar todos los registros en order_items donde id_order coincida
            Order_Item::where('id_order', $order->id)->delete();

            // Eliminar el pedido
            $order->delete();

            // Recuperar listado de pedidos antes de redirigir
            $pendientes = Order::where('estatus', 0)
            ->orderBy('fecha', 'desc')
            ->get();

            $completados = Order::where('estatus', 1)
                ->orderBy('fecha', 'desc')
                ->paginate(20);

            session()->flash('success', 'Pedido eliminado correctamente.');
            return redirect()->route('orders.adminList')->with(['pendientes' => $pendientes, 'completados' => $completados]);           
        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un problema al eliminar el pedido.');
            return redirect()->route('orders.adminList')->with(['pendientes' => $pendientes, 'completados' => $completados]);   
        }
    }


    /**
     * Mostrar listado de pedidos como administrador.
     */
    public function getAdminList(){

        // Si el usuario no es admin, redirigir con un mensaje de error
        if (!auth()->check() || auth()->user()->role !== 'admin') {
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
