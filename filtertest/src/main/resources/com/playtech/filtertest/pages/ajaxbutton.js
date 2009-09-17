var Ajaxbutton = Class.create();
Ajaxbutton.prototype = {
        initialize: function(element, uri, updatetextid) {
                this.element = element;
                this.uri = uri;
                this.updatetextid = updatetextid;
                Event.observe($(element), 'click', this.show.bindAsEventListener(this));
        },
        
        show: function() {
        	new Ajax.Request(this.uri, {
        		onSuccess: changetext
        	});
        	updatetextid = this.updatetextid;
        	function changetext(status){
        		$(updatetextid).update(status.responseText);
     		}
        }
}