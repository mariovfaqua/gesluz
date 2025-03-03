<?php

namespace App\Http\Controllers;

use App\Models\Address;
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

        return view('addresses.adminList')->with(['addresses'=>$addresses]);
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
    
        // Crear la dirección en la base de datos con el id del usuario
        $address = new Address($validatedData);
        $address->id_user = auth()->id();
        $address->save();
    
        return redirect()->back()->with('success', 'Dirección guardada correctamente.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('inicio')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }

        // Verificar que la dirección pertenezca al usuario autenticado
        if ($address->id_user !== auth()->id()) {
            return redirect()->route('addresses.index')->with('error', 'No tienes permiso para eliminar esta dirección.');
        }

        // Eliminar la dirección
        $address->delete();

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
