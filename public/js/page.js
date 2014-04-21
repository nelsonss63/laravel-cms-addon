$(document).ready(function () {
   /* Place marker for external links */
   $("a[href^='http']:not([href*='" + window.location.host + "'])").each(function() {
      var text_link = $(this).text();
      var url = $(this).attr('href');
      var found = $(this).find('img');
      if (text_link && url && found.length == 0) {
         $(this).attr("target", "_blank");
         $(this).addClass("external");
      }
   });
});
