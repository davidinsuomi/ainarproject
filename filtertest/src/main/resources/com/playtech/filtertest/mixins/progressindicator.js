var ProgressIndicator = Class.create();
ProgressIndicator.prototype = {
        initialize: function(element, image) {
                this.image = image;
                this.element = element;
                Event.observe($(element), 'click', this.show.bindAsEventListener(this));
        },
        
        show: function() {
        	$(this.image).show();
        }
}

