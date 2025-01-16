<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $form = $request->input('form');
        $items = Item::query();

        // Filtrar por texto
        if ($form["query"]) {
            $items->where('nombre', 'like', '%' . $form["query"] . '%');
        }

        // Filtrar por precio
        if ($form['minValue']) {
            $items->where('precio', '>=', $form['minValue']);
        }

        if ($form['maxValue']) {
            $items->where('precio', '<=', $form['maxValue']);
        }

        // Filtrar por material
        if ($form['material'] && $form['material'] !== 'Ninguno') {
            $items->where('material', $form['material']);
        }

        // Filtrar por marca
        if ($form['brand'] && $form['brand'] !== 'Ninguno') {
            $items->where('id_brand', $form['brand']);
        }

        // Filtrar por color
        // if ($form['color'] && $form['color'] !== 'Ninguno') {
        //     $items->where('color', $form['color']);
        // }

        // Obtener los items filtrados con paginación
        $items = $items->paginate(40);
        
        return view('items.list')->with(['items'=>$items]);
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
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
