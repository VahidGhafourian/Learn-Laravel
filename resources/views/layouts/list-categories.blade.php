<ul>
    @foreach($categories as $cat)
        <li>
            <a href="">{{ $cat->name }}</a>
            @include('layouts.list-categories', ['categories' => $cat->child])
        </li>
    @endforeach
</ul>
