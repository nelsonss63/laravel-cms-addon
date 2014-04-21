@extends('cms::layouts.edit')

@section('content')

<h1>{{ Lang::get('cms::m.create-page') }}</h1>

@include('cms::edit.pages.form-page')

@stop