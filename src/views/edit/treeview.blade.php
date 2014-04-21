<!-- Tree View -->
<link href="/packages/cednet/cms/css/edit/jquery.treeview.css" rel="stylesheet">
<script src="/packages/cednet/cms/js/edit/jquery.treeview.js"></script>
<script type="text/javascript">
   $(function() {
      $("#tree").treeview({
         collapsed: true,
         animated: "fast",
         control:"#sidetreecontrol",
         prerendered: true,
         persist: "location"
      });
   })
</script>

<div id="sidetree">
   <div class="treeheader">&nbsp;</div>
   <div id="sidetreecontrol"> <a href="?#">Collapse All</a> | <a href="?#">Expand All</a> </div>

   <ul class="treeview" id="tree">

      {{-- Edit menus and its pages --}}
      @foreach(Cms\Models\Menu::orderBy('edit_order', 'asc')->get() as $menu)
      <li class="expandable"><div class="hitarea expandable-hitarea"></div>{{ $menu->title }}
         <ul style="display: none;">
            {{ Cms\Models\MenuCreator::treeview($menu->slug, 0) }}
         </ul>
         @endforeach

   </ul>
</div>