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
        //
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
        //
    }

    public function setPrimary($id)
    {
        if (!auth()->check()) {
            return redirect()->route('home')->with('error', 'Debes iniciar sesión para realizar esta acción.');
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
