{{-- INCLUDED FILE ON FORM PAGE TO DISPLAY HISTORY (VERSIONS) --}}

@if(!empty($history) AND count($history) > 0)
<table class="table table-bordered">
   <thead>
      <th width="150">Last Update</th>
      <th>Title</th>
      <th width="80"><div class="pull-right">Action</div></th>
   </thead>
@foreach($history as $hpage)
   <tr>
      <td>{{ $hpage->updated_at }}</td>
      <td>{{ $hpage->title }}</td>
      <td>
         <div class="pull-right">
            @if($hpage->content_id == $current_content_id)
               <em>{{ Lang::get('cms::m.current') }}</em>
            @else
               <a href="{{ route('editPage', array($hpage->page_id)) }}/{{ $hpage->content_id }}">{{ Lang::get('cms::m.edit') }}</a>
            @endif
         </div>
      </td>
   </tr>
@endforeach
</table>
@else
<div class="alert alert-info">{{ Lang::get('cms::m.no-history-info') }}</div>
@endif
