{{-- TEMPLATE FOR NEW OR EDIT OF A PAGE --}}

{{
Former::horizontal_open()
->id('PageForm')
->secure()
->rules($rules)
->method('POST')
}}

   <ul id="myTab" class="nav nav-tabs">
      <li class="active"><a href="#content" data-toggle="tab">{{ Lang::get('cms::m.content') }}</a></li>
      <li><a href="#publishing" data-toggle="tab">{{ Lang::get('cms::m.publishing') }}</a></li>
      <li><a href="#advanced" data-toggle="tab">{{ Lang::get('cms::m.advanced') }}</a></li>
      <li><a href="#history" data-toggle="tab">{{ Lang::get('cms::m.history') }}</a></li>
   </ul>
   <div id="myTabContent" class="tab-content">
      <div class="tab-pane fade in active" id="content">

         {{
         Former::xxlarge_text('title', 'Rubrik')
         ->value($page->title)
         ->autofocus()
         }}

         {{
         Former::xxlarge_textarea('body', 'Innehåll')
         ->id('redactor1')
         ->class('textarea_body')
         ->value($page->body)
         ->style('height: 200px')
         }}

         <script type="text/javascript">
            $(document).ready(
                  function()
                  {
                     $('.textarea_body').redactor({
                        plugins: ['edit'], //custom for Edit, i.e link to internal URL
                        minHeight: 200
                     });
                  }
            );
         </script>

      </div>
      <div class="tab-pane fade" id="publishing">

         {{
         Former::checkbox('published', Lang::get('cms::m.published'))
         ->value($page->published)
         ->text(Lang::get('cms::m.published_help').(!empty($page->id) ? " | <a href=\"".\Cms\Models\Page::getUrl($page->id)."\" target=\"_blank\">".Lang::get('cms::m.view-page')."</a>" : ""))
         ->check($page->published ? TRUE : FALSE)
         }}

         {{-- Datepicker format must be yyyy-MM-dd hh:mm:ss (same as MySQL datetime) to be able to use strtotime or other date formatting functions in PHP --}}
         <div class="control-group">
            <label for="link" class="control-label">{{ Lang::get('cms::m.publish-start-date') }}</label>
            <div class="controls">
               <div id="datetimepicker1" class="input-append date">
                  <input name="publish_start" data-format="yyyy-MM-dd hh:mm:ss" type="text" value="{{ $page->publish_start }}" />
                <span class="add-on">
                  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                  </i>
                </span>
               </div>
            </div>
            <script type="text/javascript">
               $(function() {
                  $('#datetimepicker1').datetimepicker({
                     language: "{{ Cms\Models\Setting::get('language-code') }}"
                  });
               });
            </script>
         </div>

         <div class="control-group">
            <label for="link" class="control-label">{{ Lang::get('cms::m.publish-end-date') }}</label>
            <div class="controls">
               <div id="datetimepicker2" class="input-append date">
                  <input name="publish_end" data-format="yyyy-MM-dd hh:mm:ss" type="text" value="{{ $page->publish_end }}" />
                <span class="add-on">
                  <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                  </i>
                </span>
               </div>
            </div>
            <script type="text/javascript">
               $(function() {
                  $('#datetimepicker2').datetimepicker({
                     language: "{{ Cms\Models\Setting::get('language-code') }}"
                  });
               });
            </script>
         </div>

         {{
         Former::number('order', Lang::get('cms::m.order'))
         ->style('width: 40px')
         ->value($page->order ? : 0)
         }}

         {{--
         @if(!empty($page->menu_id))
         <div class="control-group">
            <label for="link" class="control-label">Meny</label>
            <div class="controls">
               {{ Cms\Models\Menu::find($page->menu_id)->title }}
            </div>
         </div>
         @else
         --}}
         {{
         Former::select('menu_id', Lang::get('cms::m.menu'))->fromQuery(Cms\Models\Menu::all(), 'title', 'id')
         ->select($page->menu_id)
         ->help('Välj meny sidan ska ligga under')
         ->disabled((!empty($new) AND $parent_id > 0) ? TRUE : FALSE)
         }}


         <div class="control-group">
            <label for="link" class="control-label">{{ Lang::get('cms::m.parent-page') }}</label>
            <div class="controls">
               @if(!empty($parent_id))
                  {{ \Cms\Models\Page::find($parent_id)->content->title }}
               @elseif(!empty($page->parent_id))
                  {{ \Cms\Models\Page::find($page->parent_id)->content->title }}
               @else
                  {{ Lang::get('cms::m.no-parent') }}
               @endif
            </div>
         </div>

      </div>
      <div class="tab-pane fade" id="advanced">

         {{
         Former::checkbox('allow_dropdown', Lang::get('cms::m.allow-dropdown'))
         ->value($page->allow_dropdown)
         ->text(Lang::get('cms::m.allow-dropdown-info'))
         ->check($page->allow_dropdown ? TRUE : FALSE)
         }}

         @if(Auth::user()->admin)

         {{
         Former::text('controller', Lang::get('cms::m.controller'))
         ->value($page->controller)
         ->help(Lang::get('cms::m.controller-info'))
         }}

         {{
         Former::select('template', Lang::get('cms::m.template'))->options(Cms\Libraries\Template::getTemplateOptions())
         ->select($page->template)
         }}

         @endif

         {{
         Former::xxlarge_text('link', Lang::get('cms::m.link'))
         ->value($page->link)
         }}

      </div>

      <div class="tab-pane fade" id="history">

         @include('cms::edit.pages.history')

      </div>
   </div>

@if(empty($page->id))
{{
Former::actions()
->large_primary_submit(Lang::get('cms::m.create-page'))
}}
@else
{{
Former::actions()
->large_primary_submit(Lang::get('cms::m.update-page'))
}}
@endif

{{
Former::close()
}}





<div class="modal hide fade">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h3>Modal header</h3>
   </div>
   <div class="modal-body">
      <p>One fine body…</p>
   </div>
   <div class="modal-footer">
      <a href="#" class="btn">Close</a>
      <a href="#" class="btn btn-primary">Save changes</a>
   </div>
</div>

