@extends('cms::layouts.empty')

@section('content')

<h1>Login to CMS admin</h1>

{{ Former::horizontal_open('login')
      ->secure()
      ->rules(array(
         'username' => 'required',
         'password' => 'required',
      ))
      ->method('POST'); }}

{{ Former::xlarge_text('username')
      ->class('myclass')
      ->value(Input::old('username'))
      ->required(); }}

{{ Former::password('password')
      ->class('myclass')
      ->value('')
      ->required(); }}

{{ Former::actions()
      ->large_primary_submit('Logga in'); }}

{{ Former::close(); }}

@stop