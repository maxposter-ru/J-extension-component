var PhotoManager = function(_original, _thumbnails){
  if ($$(_original+" img") && $$(_thumbnails)) {
    $$(_thumbnails).getChildren('a').each(function(el){
      el.addEvents({
        click : function(e){
          e = new Event(e); e.stop();
          $$(_original+" img").setProperty("src", this.getProperty("href"));
          if($$(_thumbnails+" img.current")) {
            $$(_thumbnails+" img.current").removeClass("current");
          }
          this.getFirst().addClass("current");
        },
        mouseenter: function(){this.getFirst().addClass("active")},
        mouseleave: function(){this.getFirst().removeClass("active")}
      });
    });
  }
}