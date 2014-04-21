@extends('cms::layouts.edit')

@section('content')

<div class="pull-right">
   <a class="btn btn-danger" href="{{ route('removeMenu', array($menu->id)) }}" onclick="return confirm('{{ Lang::get('cms::m.confirm-delete') }}'); return false">{{ Lang::get('cms::m.delete') }}</a>
</div>

@if(!empty($menu))
<h1>{{ Lang::get('cms::m.update-menu') }}</h1>
@else
<h1>{{ Lang::get('cms::m.create-menu') }}</h1>
@endif

{{
Former::horizontal_open()
->secure()
->rules(array(
'id' => 'required|integer',
'title' => 'required|max:255',
'order' => 'numeric'
))
->method('POST')
}}

<p>
   {{ Lang::get('cms::m.create-menu-info') }}
</p>

{{ Former::xxlarge_text('title', Lang::get('cms::m.title')) }}

{{ Former::number('edit_order', Lang::get('cms::m.order')) ->style('width: 40px') }}

{{ Former::actions()->large_primary_submit(Lang::get('cms::m.save')) }}

{{ Former::close() }}

@stop