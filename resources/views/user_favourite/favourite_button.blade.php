@if (Auth::id() != $user->id)
        {!! Form::open(['route' => ['users.unfavourites', $micropost->id], 'method' => 'delete']) !!} 
            {!! Form::submit('Unfavourites', ['class' => "btn btn-danger btn-xs"]) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => ['users.favourites', $micropost->id]]) !!}
            {!! Form::submit('Favourites', ['class' => "btn btn-primary btn-xs"]) !!}
        {!! Form::close() !!}
    @endif