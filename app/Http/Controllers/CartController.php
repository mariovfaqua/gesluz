<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Address;

class CartController extends Controller
{
    /**
     * Mostrar artículos en el carrito.
     */
    public function index()
    {
        // Obtener el carrito desde la sesión
        $cart = session()->get('cart', []);

        // Recuperar los IDs de los items en el carrito
        $itemIds = array_keys($cart);

        // Buscar los items en la base de datos
        $items = Item::whereIn('id', $itemIds)->get();

        // Agregar la cantidad a cada item
        foreach ($items as $item) {
            $item->cantidad = $cart[$item->id]['cantidad'] ?? 1;
        }

        // Recuperar todas las direcciones del usuario autenticado
        $addresses = [];
        if (auth()->check()) {
            $addresses = Address::where('id_user', auth()->id())->get();

            // Intentar guardar la dirección primaria en la sesión
            if (!session()->has('address')) {
                $primaryAddress = $addresses->where('primaria', true)->first();

                if ($primaryAddress) {
                    session(['address' => $primaryAddress->toArray()]);
                }
            }
        }

        // Enviar los items y direcciones a la vista
        return view('cart')->with(['items'=>$items, 'addresses'=>$addresses]);
    }

    /**
     * Agregar un producto al carrito.
     */
    public function add(Request $request)
    {
        $item = Item::find($request->id_item);

        if (!$item || $item->stock < $request->cantidad) {
            return back()->with('error', 'No se pudo completar la solicitud');
        }

        // Obtener el carrito actual desde la sesión
        $cart = session()->get('cart', []);

        // Si el producto ya está en el carrito, incrementa la cantidad
        if (isset($cart[$item->id])) {
            $cart[$item->id]['cantidad'] += $request->cantidad;
        } else {
            // Si el producto no está en el carrito, lo agrega
            $cart[$item->id] = [
                'item' => $item,
                'cantidad' => $request->cantidad
            ];
        }

        // Guardar el carrito en la sesión
        session()->put('cart', $cart);

        return back()->with('success', 'Producto agregado al carrito');
    }

    /**
     * Eliminar producto del carrito.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Producto eliminado del carrito');
        }

        return back()->with('error', 'Producto no encontrado');
    }

    /**
     * Vaciar el carrito.
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Carrito vacío');
    }

    /**
     * Guardar la dirección en la sesión actual.
     */
    public function storeAddress(Request $request)
    {
        try {
            // Si se seleccionó una dirección guardada
            if ($request->has('selected_address')) {
                $address = Address::findOrFail($request->selected_address);
                session(['address' => $address->toArray()]);
            }

            // Si se creará una nueva dirección
            else {
                $addressData = $request->validate([
                    'destinatario'  => 'required|string|max:255',
                    'linea_1'       => 'required|string|max:255',
                    'linea_2'       => 'nullable|string|max:255',
                    'pais'          => 'required|string|max:100',
                    'provincia'     => 'required|string|max:50',
                    'ciudad'        => 'required|string|max:50',
                    'codigo_postal' => 'required|string|max:10', 
                ]);

                session(['address' => $addressData]);
            }

            return redirect()->back()->with('success', 'Datos guardados correctamente.');

        } catch (\Exception $e) {

            \Log::error('Error al guardar dirección o contacto: '.$e->getMessage());
            return redirect()->back()->withErrors('Ha ocurrido un error inesperado al guardar los datos. Inténtalo de nuevo.');
        }
    }

    /**
     * Eliminar la dirección de la sesión actual.
     */
    public function clearAddress(Request $request)
    {
        session()->forget('address');
        return back();
    }    
}
