@extends('cms::layouts.edit')

@section('content')

<div class="pull-right">
    <a href="{{ route('createUser') }}" class="btn btn-primary">Create user</a>
</div>

<h1>Users</h1>

{{ Former::horizontal_open()->secure() }}

<table class="table">
    <thead>
    <th>Username</th>
    <th>Editor</th>
    <th>Admin</th>
    <th></th>
    </thead>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->username }}</td>
        <td>{{ $user->edit ? "Yes" : "No" }}</td>
        <td>{{ $user->admin ? "Yes" : "No" }}</td>
        <td class="text-right">
            <a href="{{ route('editUser', array($user->id)) }}">Manage</a>
        </td>
    </tr>
    @endforeach
</table>

{{ Former::close() }}

@stop