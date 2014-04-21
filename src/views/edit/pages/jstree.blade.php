{{-- Edit menus and its pages --}}

@foreach(Cms\Models\Menu::orderBy('edit_order', 'asc')->get() as $key => $menu)

<div class="edit_menu">

   <div class="edit_menu_actions">
      <a href="{{ route('createPage', array($menu->id)) }}/0">{{ Lang::get('cms::m.create-page') }}</a> | <a href="{{ route('editMenu', array($menu->id)) }}">{{ Lang::get('cms::m.edit') }}</a>
   </div>
   <h4>{{ $menu->title }}</h4>

   <div class="edit_menu">

      <div id="demo{{ $menu->id  }}" class="demo">

         @foreach(\Cms\Models\Page::with("content")->onlyMainPages()->where("menu_id", "=", $menu->id)->get() as $mainPage)

               <ul>
                  <li id="{{ $mainPage->id }}" data-menuid="1" data-pageid="{{ $mainPage->id }}" data-parentid="0">
                      <a href="{{ route('editPage', $mainPage->id) }}">@if($mainPage->content->title) {{ $mainPage->content->title }} @else {{ mb_substr(strip_tags($mainPage->content->body),0,10) }}... @endif</a>
                     {{ Cms\Models\MenuCreator::jstree($mainPage->id) }}
                  </li>
               </ul>

         @endforeach

      </div>

      <script type="text/javascript" class="source">
         $(function () {
            $("#demo{{ $menu->id  }}").jstree({
               "plugins" : [ "themes", "html_data", "contextmenu", "cookies" ],
               "auto_save" : true,
               "contextmenu" : {
                  "items" : customMenu
               },
               "core" : {
                  "animation" : 50
               }
            });
         });
      </script>

   </div>

</div>


@endforeach

<script>
   function customMenu(node) {
      console.log(node);
      parent_id = $(node).attr("data-parentid");
      page_id = $(node).attr("data-pageid");
      menu_id = $(node).attr("data-menuid");

      var items = {
         "insert" : {
            "label" : "{{ Lang::get('cms::m.context-create-page') }}",   //Different label (defined above) will be shown depending on node type
            "action" : function () {
               window.location.href='{{ route('createPage') }}/' + menu_id + '/' + page_id;
            }
         },
         "edit" : {
            "label" : "{{ Lang::get('cms::m.context-edit-page') }}",
            "action" : function() {
               window.location.href='{{ route('editPage') }}/' + page_id;
            }
         },
         "delete" : {
            "label" : "{{ Lang::get('cms::m.context-delete-page') }}",
            "action" : function () {
               window.location.href = '{{ route('removePage') }}/' + page_id;
            }
         },
         @if(Auth::user()->admin)
          "permissions" : {
             "label" : "{{ Lang::get('cms::m.context-permissions') }}",
                   "action" : function () {
                window.location.href='/edit/page-permissions/' + page_id;
             }
          },
          "mark_as_home" : {
             "label" : "{{ Lang::get('cms::m.context-mark-as-home') }}",
                   "action" : function () {
                    window.location.href = '{{ route('markAsHome') }}/' + page_id;
                 }
          }
         @endif
   };

   //If node is a folder do not show the "delete" menu item
   if ($(node).hasClass("jstree-closed") || $(node).hasClass("jstree-open")) {
      delete items.remove;
   }

   return items;
   }
</script>