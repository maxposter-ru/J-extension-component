(function() {
var MaxGallery = this.MaxGallery = {
    init: function(ImageDiv, thumbsDiv) {
        this.thumbsDiv = thumbsDiv;
        this.ImageDiv  = ImageDiv;
        thumbsDiv.getFirst('a').addClass('current');

        this.thumbsDiv.getChildren('a').each(function(link){
            link.addEvent('click', function(event){ MaxGallery.doclick(event, this) });
        });
    },
    doclick: function(event, obj) {
        event.stop(); //Prevents the browser from following the link.
        this.thumbsDiv.getChildren('a').each(function(link){ link.removeClass('current') });
        obj.addClass('current');
        var orig = this.ImageDiv.getElement('img');
        var size = orig.getProperty('src').match(/(\d+x\d+).*?([a-z0-9]+\.jpg)$/);
        orig.setProperty('src', obj.getProperty('href').replace('source', size['1']));
        this.ImageDiv.getElement('a').setProperty('href', obj.getProperty('href'));
    }
};})(document.id);
