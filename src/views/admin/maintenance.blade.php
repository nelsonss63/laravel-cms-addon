@extends('cms::layouts.admin')

@section('content')

<h1>{{ Lang::get('cms::m.crawler') }}</h1>
<p>{{ Lang::get('cms::m.crawler-info') }}</p>

{{ Former::horizontal_open()->id('crawl_form')->secure()->method('POST') }}

@foreach($available_maintenances as $action => $name)
   <input type="submit" name="action" class="btn maintenance_button" data-action="{{ $action }}" value="{{ $action }}" />
@endforeach


{{
Former::close()
}}

<script>
   /*
   $(document).ready(function() {
      $('.maintenance_button').on("click", function() {
         var action = $(this).attr("data-action");
         $.ajax({
            type: "POST",
            url: '{{ route('crawler') }}',
            data: {
               action: action
            },
            success: function(response) {
               alert(response);
               console.log(response);
               //window.location.refresh();
            }
         });
      });
   });
   */
</script>

<hr />

<h3>{{ Lang::get('cms::m.crawl-a-url') }}</h3>

{{ Former::horizontal_open()->id('crawl_form2')->secure()->rules(array( 'name' => 'required' ))->method('POST') }}

{{ Former::hidden('action')->value('crawl') }}

{{ Former::checkbox('crawl_found_links', Lang::get('cms::m.crawl-found-links')) }}

{{ Former::checkbox('crawl_only_subpages', Lang::get('cms::m.crawl-only-subpages'))->help(Lang::get('cms::m.crawl-only-subpages-info')) }}

{{ Former::xlarge_text('crawl_url', Lang::get('cms::m.crawl-this-url'))->value('http://')->required() }}

{{ Former::small_text('crawl_menu_id', Lang::get('cms::m.crawl-menu-id'))->value('0') }}

{{ Former::small_text('crawl_parent_id', Lang::get('cms::m.crawl-parent-id'))->value('0') }}

{{ Former::checkbox('crawl_convert', Lang::get('cms::m.crawl-convert')) }}

{{ Former::actions()->large_primary_submit('Submit') }}

{{ Former::close() }}

@stop