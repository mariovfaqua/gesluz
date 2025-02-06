<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Tag;
use App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        if (auth()->user()->role !== 'admin') {
            // Si el usuario no es admin, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'distribucion' => 'required|string',
            'material' => 'required|string',
            'stock' => 'required|integer|min:0',
            'id_brand' => 'nullable|exists:brands,id',
            'tags' => 'array', // Tags existentes
            'tags.*' => 'exists:tags,id', // Asegurar que sean tags válidos
            'newTags' => 'array', // Nuevos tags (si los hay)
            'newTags.*' => 'string|max:255',
        ]);

        // Crear el nuevo item
        $item = Item::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'distribucion' => $request->distribucion,
            'material' => $request->material,
            'stock' => $request->stock,
            'id_brand' => $request->id_brand,
        ]);

        // Procesar y sincronizar los tags
        $tagIds = $this->processTags($request);
        $item->tags()->sync($tagIds);

        // Redireccionar con mensaje de éxito
        return redirect()->route('home')->with('success', 'Item creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = Item::with(['images', 'tags'])->findOrFail($id);

        return view('items.detail')->with(['item'=>$item,]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }
    
        $selectedTags = $item->tags->pluck('id')->toArray();
    
        return view('items.edit')->with(['item'=>$item, 'selectedTags'=>$selectedTags]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'distribucion' => 'required|string',
            'material' => 'required|string',
            'stock' => 'required|integer|min:0',
            'id_brand' => 'nullable|exists:brands,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'newTags' => 'array', // Nuevos tags (si los hay)
            'newTags.*' => 'string|max:255',
        ]);

        // Actualizar los datos del item
        $item->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'distribucion' => $request->distribucion,
            'material' => $request->material,
            'stock' => $request->stock,
            'id_brand' => $request->id_brand,
        ]);

        // Procesar y sincronizar los tags
        $tagIds = $this->processTags($request);
        $item->tags()->sync($tagIds);

        // Redireccionar con mensaje de éxito
        return redirect()->route('items.adminList')->with('success', 'Item actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para realizar esta acción.');
        }
    
        try {
            // Eliminar las relaciones con tags antes de borrar el item
            $item->tags()->detach();
    
            // Eliminar la imagen asociada si existe
            // if ($item->imagen && Storage::exists($item->imagen)) {
            //     Storage::delete($item->imagen);
            // }
    
            // Eliminar el item
            $item->delete();
    
            return redirect()->route('items.adminList')->with('success', 'Item eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('items.adminList')->with('error', 'Hubo un problema al eliminar el item.');
        }
    }

    public function quickLink($type, $value)
    {
        switch ($type) {
            case 'distribucion':
                if($value == 'interior'){
                    $items = Item::whereIn('distribucion', ['Dormitorio', 'Cocina', 'Baño', 'Salón'])->paginate(40);
                } elseif ($value == 'exterior'){
                    $items = Item::whereIn('distribucion', ['Jardín'])->paginate(40);
                } else {
                    $items = Item::where('distribucion', $value)->paginate(40);
                }
                break;
            case 'tag':
                // Buscar el tag por nombre y obtener los items relacionados
                $tag = Tag::where('nombre', $value)->first();

                if ($tag) {
                    $items = $tag->items()->paginate(40);
                }
                break;
            default:
                return redirect()->route('items.index');
        }

        return view('items.list', ['items' => $items]);
    }

    public function getAdminList(){
        if (auth()->user()->role !== 'admin') {
            // Si el usuario no es admin, redirigir con un mensaje de error
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $items = Item::all();
        return view('items.adminList')->with(['items'=>$items]);
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

    public function processTags($request)
    {
        // Obtener los IDs de los tags existentes seleccionados
        $tagIds = $request->tags ?? [];

        // Procesar los nuevos tags si los hay
        if ($request->has('newTags')) {
            foreach ($request->newTags as $tagName) {
                $tag = Tag::firstOrCreate(['nombre' => $tagName]);
                $tagIds[] = $tag->id; // Agregar el nuevo tag a la lista de IDs
            }
        }

        return $tagIds;
    }
}
