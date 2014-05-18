   {{-- Edit menus and its pages --}}
   @foreach(Cms\Models\Menu::orderBy('edit_order', 'asc')->get() as $key => $menu)

   <div class="edit_menu">

   <div class="edit_menu_actions">
      <a href="{{ route('createPage', $menu->id)) }}/0">{{ Lang::get('cms::m.create-page') }}</a> | <a href="{{ route('editMenu', array($menu->id)) }}">{{ Lang::get('cms::m.edit') }}</a>
   </div>
   <h4>{{ $menu->title }}</h4>

   <div id="demo{{ $key }}" class="demo">
   {{ Cms\Models\MenuCreator::jstree($menu->id) }}
   </div>

   </div>

   <script type="text/javascript" class="source">
      $(function () {
         $("#demo{{ $key }}").jstree({
            "plugins" : [ "themes", "html_data", "contextmenu", "cookies" ],
            "auto_save" : true,
            "contextmenu" : {
               "items" : customMenu
            }
         });
      });
   </script>

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
               if(confirm('{{ Lang::get('cms::m.context-delete-page-info') }}')) {
                  $.ajax({
                     url: '{{ route('editPage') }}/' + page_id,
                     type: 'DELETE',
                     success: function() {
                        window.location.href='/edit';
                     }
                  });
               }
            }
         },
         @if(\Cms\Models\User::getUser()->admin)
         "permissions" : {
            "label" : "{{ Lang::get('cms::m.context-permissions') }}",
            "action" : function () {
               window.location.href='/edit/page-permissions/' + page_id;
            }
         },
         "mark_as_home" : {
            "label" : "{{ Lang::get('cms::m.context-mark-as-home') }}",
            "action" : function () {
               $.ajax({
                  url: '/edit/mark-as-home/' + page_id,
                  type: 'PUT',
                  success: function() {
                     window.location.reload();
                  }
               });
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