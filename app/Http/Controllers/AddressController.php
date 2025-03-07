<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('inicio')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $addresses = Address::where('id_user', auth()->user()->id)->get();

        return view('addresses.list')->with(['addresses'=>$addresses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('inicio')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return view('addresses.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('inicio')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }
    
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre'        => 'required|string|max:255',
            'linea_1'       => 'required|string|max:255',
            'linea_2'       => 'nullable|string|max:255',
            'pais'          => 'required|string|max:100',
            'provincia'     => 'required|string|max:100',
            'ciudad'        => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
        ]);

        // COmprobar si el usuario ya tiene la dirección guardada
        $existe = Address::where('nombre', $validatedData['nombre'])
            ->where('linea_1', $validatedData['linea_1'])
            ->where('linea_2', $validatedData['linea_2'] ?? null)
            ->where('provincia', $validatedData['provincia'])
            ->where('ciudad', $validatedData['ciudad'])
            ->where('pais', $validatedData['pais'])
            ->where('codigo_postal', $validatedData['codigo_postal'])
            ->where('id_user', auth()->id())
            ->exists();

        if ($existe) {
            return redirect()->route('addresses.index')->with('error', 'Ya tienes registrada esta dirección.');
        }
    
        // Crear la dirección en la base de datos con el id del usuario
        $address = new Address($validatedData);
        $address->id_user = auth()->id();
        $address->save();

        // Si la URL anterior es 'addresses/create', redirigir a 'addresses/index', de lo contrario, volver atrás
        if (str_contains(url()->previous(), route('addresses.create'))) {
            return redirect()->route('addresses.index')->with('success', 'Dirección guardada correctamente.');
        } else {
            return redirect()->back()->with('success', 'Dirección guardada correctamente.');
        }
    }    

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        // Verificar que la dirección exista y pertenezca al usuario autenticado
        if (!auth()->check() || $address->id_user !== auth()->id()) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para editar esta dirección.');
        }

        // Pasar el objeto address a la vista del form
        return view('addresses.form', ['address' => $address]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        // Verificar que el usuario autenticado es el propietario de la dirección
        if (!auth()->check() || $address->id_user !== auth()->id()) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para editar esta dirección.');
        }
    
        // Validación de los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'linea_1' => 'required|string|max:255',
            'linea_2' => 'nullable|string|max:255',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
        ]);

        // Comprobar si la dirección está vinculada a algún pedido
        $isAddressUsed = Order::where('id_address', $address->id)->exists();
    
        if ($isAddressUsed) {
            // Crear una nueva dirección con los datos actualizados
            $newAddress = Address::create(array_merge($validatedData, ['id_user' => auth()->id()]));
    
            // Desvincular la dirección antigua del usuario
            $address->update(['id_user' => null]);
        } else {
            // Si no está vinculada a pedidos, actualizar la dirección existente
            $address->update($validatedData);
        }
    
        return redirect()->route('addresses.index')->with('success', 'Dirección actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        // Verificar que la dirección pertenezca al usuario autenticado
        if (!auth()->check() || $address->id_user !== auth()->id()) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para eliminar esta dirección.');
        }

        // Comprobar si la dirección está vinculada a algún pedido
        $isAddressUsed = Order::where('id_address', $address->id)->exists();

        if ($isAddressUsed) {
            // Desvincular la dirección del usuario
            $address->update(['id_user' => null]);
        } else {
            // Si no está vinculada a pedidos, elimina la dirección
            $address->delete();
        }

        // Si la dirección eliminada era la guardada en sesión, eliminarla de la sesión
        if (session('address.id') == $address->id) {
            session()->forget('address');
        }

        return redirect()->route('addresses.index')->with('success', 'Dirección eliminada correctamente.');
    }


    public function setPrimary($id)
    {
        if (!auth()->check()) {
            return redirect()->route('inicio')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }
    
        $user = auth()->user();
    
        try {
            // Desactivar cualquier dirección primaria anterior del usuario
            Address::where('id_user', $user->id)->where('primaria', true)->update(['primaria' => false]);
    
            // Establecer la nueva dirección como primaria
            $address = Address::where('id', $id)->where('id_user', $user->id)->firstOrFail();
            $address->update(['primaria' => true]);
    
            return redirect()->back()->with('success', 'Dirección principal actualizada correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un problema al actualizar la dirección principal.');
        }
    }
}
