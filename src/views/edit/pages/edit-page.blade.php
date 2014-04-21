@extends('cms::layouts.edit')

@section('content')

<div class="pull-right">
   <a href="{{ route('removePage', array($page->id)) }}" class="btn btn-danger" onclick="return confirm('{{ Lang::get('cms::m.confirm-delete') }}'); return false;">{{ Lang::get('cms::m.delete') }}</a>
</div>

<h1>{{ $page->title }}</h1>
<p>
   {{ Lang::get('cms::m.template') }}: <strong>{{ ucfirst($page->template) }}</strong>
   {{ Lang::get('cms::m.status') }}: <strong>@if($page->published) {{ Lang::get('cms::m.published') }} @else {{ Lang::get('cms::m.unpublished') }}@endif</strong>
</p>

@include('cms::edit.pages.form-page')

@stop