@extends('cms::layouts.edit')

@section('content')

<h1>{{ Lang::get('cms::m.create-page') }}</h1>


{{
Former::horizontal_open()
->id('CreatePageStart')
->secure()
->rules(array(
'menu_id' => 'required|integer',
'title' => 'required|max:255',
'body' => 'trim',
))
->method('GET')
}}

<p>
   {{ Lang::get('cms::m.create-page-start-info') }}
</p>

{{
Former::select('menu_id', Lang::get('cms::m.menu'))->fromQuery(Cms\Models\Menu::all(), 'title', 'id')
}}

{{
Former::actions()
->large_primary_submit(Lang::get('cms::m.continue'))
}}

{{ Former::close() }}

<script>
   $(document).ready(function() {
      $('#CreatePageStart').submit(function(e) {
         e.preventDefault();
         var menu_id = $('select[name=menu_id]').val();
         window.location.href = '{{ route('createPage') }}/' + menu_id + '/0';
      });
   });
</script>

@stop