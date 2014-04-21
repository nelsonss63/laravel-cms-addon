@extends('cms::layouts.edit')

@section('content')

<div class="pull-right">
   {{
   Former::horizontal_open()
   ->id('MyForm')
   ->secure()
   ->rules(array(
   ))
   ->method('DELETE')
   }}
   {{
   Former::submit(Lang::get('cms::m.move-selected-pages'))
   }}
   {{ Former::close() }}
</div>

<h1>{{ Lang::get('cms::m.unsorted-pages') }}</h1>

<p>{{ Lang::get('cms::m.unsorted-pages-info') }}</p>

{{
Former::vertical_open()
->id('UnsortedPages')
->secure()
->rules(array(
'action' => 'required',
))
->method('POST')
}}

@if(count($unsorted) > 0)
<table class="table table-striped">
@foreach($unsorted as $page)
<tr>
   <td><label><input type="checkbox" name="page[]" value="{{ $page->id }}" /> @if(!empty($page->content->title)) {{ $page->content->title }} @else - {{ Lang::get('cms::m.untitled-page') }} - @endif</label></td>
   <td><div class="text-right"><a href="{{ route('editPage', array($page->id)) }}">{{ Lang::get('cms::m.edit') }}</a></div></td>
</tr>
@endforeach
</table>
@else
<p>- No Unsorted Pages found! -</p>
@endif

{{
Former::select('action', Lang::get('cms::m.action'))->options($actions,'')
}}

{{
Former::submit(Lang::get('cms::m.submit'))
}}

{{
Former::close()
}}

@stop