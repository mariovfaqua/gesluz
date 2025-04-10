<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order_Item;
use App\Models\Tag;
use App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Mostrar listado de artículos.
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

        if ($request->ajax()) {
            return view('items.partials.resultados', compact('items'))->render();
        }
        
        return view('items.list')->with(['items'=>$items]);
    }

    /**
     * Mostrar formulario para creación de artículos.
     */
    public function create()
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return view('items.form');
    }

    /**
     * Guardar nuevo artículo en la base de datos.
     */
    public function store(Request $request)
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0|max:99999.99',
            'distribucion'  => 'required|in:salón,dormitorio,cocina,baño,jardín,otros',
            'material' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'id_brand' => 'nullable|exists:brands,id',

            // Tags
            'tags' => 'array', // Tags existentes
            'tags.*' => 'exists:tags,id', // Asegurar que sean tags válidos
            'newTags' => 'array', // Nuevos tags (si los hay)
            'newTags.*' => 'string|max:50',
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
     * Mostrar el detalle de un artículo.
     */
    public function show($id)
    {
        $item = Item::with(['images', 'tags'])->findOrFail($id);

        // Obtener la cantidad total reservada en pedidos pendientes
        $cantidadReservada = Order_Item::where('id_item', $id)
        ->whereHas('order', function ($query) {
            $query->where('estatus', false); // Filtrar solo los pedidos pendientes
        })
        ->sum('cantidad'); // Sumar la cantidad reservada en esos pedidos

        // Restar la cantidad reservada del stock
        $item->stock -= $cantidadReservada;

        return view('items.detail')->with(['item'=>$item,]);
    }

    /**
     * Mostrar formulario para editar artículos.
     */
    public function edit(Item $item)
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }
    
        $selectedTags = $item->tags->pluck('id')->toArray();
    
        return view('items.form')->with(['item'=>$item, 'selectedTags'=>$selectedTags]);
    }

    /**
     * Actualizar artículo en la base de datos.
     */
    public function update(Request $request, Item $item)
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0|max:99999.99',
            'distribucion'  => 'required|in:salón,dormitorio,cocina,baño,jardín,otros',
            'material' => 'required|string|max:50',
            'stock' => 'required|integer|min:0',
            'id_brand' => 'nullable|exists:brands,id',

            // Tags
            'tags' => 'array', // Tags existentes
            'tags.*' => 'exists:tags,id', // Asegurar que sean tags válidos
            'newTags' => 'array', // Nuevos tags (si los hay)
            'newTags.*' => 'string|max:50',
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
     * Eliminar artículo de la base de datos.
     */
    public function destroy(Item $item)
    {
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Si el artículo ya está asociado a un pedido, no se permite eliminarlo
        if (Order_Item::where('id_item', $item->id)->exists()) {
            return redirect()->route('items.adminList')->with('error', 'No se puede eliminar este item porque está asociado a pedidos anteriores.');
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

    /**
     * Aplicar tags de acceso rápido.
     */
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
                } else {
                    return redirect()->route('items.index');
                }
                break;
            default:
                return redirect()->route('items.index');
        }

        return view('items.list', ['items' => $items]);
    }

    /**
     * Mostrar listado de artículos como administrador.
     */
    public function getAdminList(){
        // Si el usuario no es admin, redirigir con un mensaje de error
        if (auth()->check() && auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        $items = Item::all();
        return view('items.adminList')->with(['items'=>$items]);
    }

    /**
     * --- Otras funciones ---
     */

    public function filterByForm($query, $form)
    {
        $appliedFilters = [];

        // Filtrar por texto
        if (!empty($form['query'])) {
            $query->where('nombre', 'like', '%' . $form['query'] . '%');
            $appliedFilters['query'] = $form['query'];
        }

        // Filtrar por precio mínimo
        if (!empty($form['minValue'])) {
            $query->where('precio', '>=', $form['minValue']);
            $appliedFilters['minValue'] = $form['minValue'];
        }

        // Filtrar por precio máximo
        if (!empty($form['maxValue'])) {
            $query->where('precio', '<=', $form['maxValue']);
            $appliedFilters['maxValue'] = $form['maxValue'];
        }

        // Filtrar por material
        if (!empty($form['material']) && $form['material'] !== 'Ninguno') {
            $query->where('material', $form['material']);
            $appliedFilters['material'] = $form['material'];
        }

        // Filtrar por marca
        if (!empty($form['brand']) && $form['brand'] !== 'Ninguno') {
            $query->where('id_brand', $form['brand']);
            $appliedFilters['brand'] = $form['brand'];
        }

        // Filtrar por tags
        if (!empty($form['tags']) && is_array($form['tags'])) {
            $query->whereHas('tags', function ($tagQuery) use ($form) {
                $tagQuery->whereIn('id', $form['tags']); 
            });
            $appliedFilters['tags'] = $form['tags'];
        }

        // Filtrar por color
        // if ($form['color'] && $form['color'] !== 'Ninguno') {
        //     $items->where('color', $form['color']);
        // }

        // Guardar filtros en sesión
        session(['filters' => $appliedFilters]);

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
