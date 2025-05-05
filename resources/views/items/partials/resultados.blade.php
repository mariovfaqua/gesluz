<div class="row">
    @forelse($items as $item)
        <x-card :item="$item" />
    @empty
        <p>No se han encontrado productos.</p>
    @endforelse
</div>

<nav aria-label="Page navigation">
    {{ $items->links('pagination.bootstrap-5-custom') }}
</nav>
