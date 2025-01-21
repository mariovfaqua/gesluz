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
        $items = Item::query();

        // Si hay filtros en el formulario, los aplicamos
        if ($request->has('form')) {
            $form = $request->input('form');
            $items = $this->filterByForm($items, $form);
        }

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

    public function quickTag($tag)
    {
        // Obtener los items que tienen el tag especificado
        $items = Item::whereHas('tags', function ($query) use ($tag) {
            $query->where('nombre', $tag);
        })->paginate(40);

        return view('items.list', ['items' => $items]);
    }

    public function filterByForm($query, $form)
    {
        // Filtrar por texto
        if (!empty($form['query'])) {
            $query->where('nombre', 'like', '%' . $form['query'] . '%');
        }

        // Filtrar por precio mínimo
        if (!empty($form['minValue'])) {
            $query->where('precio', '>=', $form['minValue']);
        }

        // Filtrar por precio máximo
        if (!empty($form['maxValue'])) {
            $query->where('precio', '<=', $form['maxValue']);
        }

        // Filtrar por material
        if (!empty($form['material']) && $form['material'] !== 'Ninguno') {
            $query->where('material', $form['material']);
        }

        // Filtrar por marca
        if (!empty($form['brand']) && $form['brand'] !== 'Ninguno') {
            $query->where('id_brand', $form['brand']);
        }

        // Filtrar por tags
        if (!empty($form['tags']) && is_array($form['tags'])) {
            $query->whereHas('tags', function ($tagQuery) use ($form) {
                $tagQuery->whereIn('id', $form['tags']); 
            });
        }

        // Filtrar por color
        // if ($form['color'] && $form['color'] !== 'Ninguno') {
        //     $items->where('color', $form['color']);
        // }

        return $query;
    }
}
