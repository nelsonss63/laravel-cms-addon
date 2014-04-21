if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};

RedactorPlugins.edit = {

   init: function()
   {
      var link_text = this.getSelectedHtml();
      var callback = $.proxy(function()
      {
         $('#redactor_modal .redactor_clip_link').each($.proxy(function(i,s)
         {
            $(s).click($.proxy(function()
            {
               this.insertClip($(s).next().html());

               return false;

            }, this));

         }, this));


         this.saveSelection();
         this.setBuffer();

      }, this);

      this.addBtn('clips', 'Clips', function(obj)
      {
         obj.modalInit('Create Internal Link<br />' +
                  '<br />' +
                  '<input type="button" class="btn" value="Select Page" /><br />' +
                  '<br />' +
                  '<br /><br />', '#clipsmodal', 500, callback);

      });

   },
   insertClip: function(html)
   {
      alert('test');
      this.restoreSelection();
      this.execCommand('inserthtml', html);
      this.modalClose();
   }
}