var Confirm = Class.create();
Confirm.prototype = {
        initialize: function(element, gifimage) {
                this.gifimage = gifimage;
                this.element = element;
                Event.observe($(element), 'click', this.show.bindAsEventListener(this));
        },
        
        show: function() {
        	bar = new Element('img', {src: this.gifimage});
        	$(this.element).insert({before: bar});
        }
}

