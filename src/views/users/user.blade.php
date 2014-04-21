@extends('cms::layouts.edit')

@section('content')

@if($user->username)

    <div class="pull-right">
        @if($user->id == 1)
            <span class="text-danger">Cannot remove main user</span>
        @else
            <a href="{{ route('removeUser', array($user->id)) }}" class="btn btn-danger">Remove user</a>
        @endif
    </div>
    <h1>{{ $user->username }}</h1>

@else

    <h1>New user</h1>

@endif

{{ Former::horizontal_open()->secure() }}

<div class="well">
    {{ Former::text('username', 'Username') }}
    {{ Former::password('password', 'Password') }}
    {{ Former::checkbox('edit', 'Editor')->checked($user->edit ? true : false) }}
    {{ Former::checkbox('admin', 'Admin')->checked($user->admin ? true : false) }}
</div>

{{ Former::submit('Save')->class('btn btn-primary btn-block') }}

{{ Former::close() }}

@stop