@foreach($items as $item)
    @include('partials.search.' . $item->getTable(), [$item->getTable() => $item])
@endforeach
