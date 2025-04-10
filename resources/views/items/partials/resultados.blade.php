<div class="row">
    @forelse($items as $item)
        <x-card :item="$item" />
    @empty
        <p>No se han encontrado productos.</p>
    @endforelse
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>
