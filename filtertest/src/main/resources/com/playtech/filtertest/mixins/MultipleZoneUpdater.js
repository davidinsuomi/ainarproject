var MultipleZoneUpdater = {
    zoneTriggers:new Array(),
    /**
     *
     */
    attacheMultipleZoneUpdateTriggers:function(uri,
    		domTriggerCssIdentifiers, zoneBlocksJSON) {
        var triggers;
        for(i = 0;i<domTriggerCssIdentifiers.length;i++) {
            tempTriggers = $$(domTriggerCssIdentifiers[i]);
            if(!triggers) {
                triggers = tempTriggers;
            }
            else {
                for(t = 0;t<tempTriggers.length;t++) {
                    triggers.push(tempTriggers[t]);
                }
            }
        }
        var zoneBlocks = eval(zoneBlocksJSON);
        newZoneTrigger = new ComponentTrigger(uri, zoneBlocks);
        MultipleZoneUpdater.zoneTriggers.push(newZoneTrigger);
        for (i = 0; i<triggers.length; i++) {
            tagName = triggers[i].tagName.toLowerCase();
            if(tagName == 'a') {
                triggers[i].observe('mouseup',
                		newZoneTrigger.triggerUpdate.bindAsEventListener(newZoneTrigger));
            }
            else if (tagName == 'form') {
                triggers[i].observe('submit',
                		newZoneTrigger.triggerUpdate.bindAsEventListener(newZoneTrigger));
            }
            else if (triggers[i].hasClassName('t-zone')){
                triggers[i].zone.show = function(content) {
                        //a copy of tapestries code in the show method
                            this.updateElement.update(content);
                            var func = this.element.visible() ?
                            		this.updateFunc : this.showFunc;
                            func.call(this, this.element);

                            //our added line
                            newZoneTrigger.triggerUpdate();
                        }
            }

        }
    }
}

function ComponentTrigger(uri, zoneBlocks) {
    this.uri = uri;
    this.zoneBlocks = zoneBlocks;
    this.triggerUpdate = triggerMultipleZoneUpdate;
}

/**
     *
     */
function triggerMultipleZoneUpdate() {
    for(var i in this.zoneBlocks) {
        var zoneToUpdate = $(this.zoneBlocks[i].zone);
        var fullUri = this.uri + '/' + this.zoneBlocks[i].block;
        var myAjax = new Ajax.Request( fullUri, {method: 'get', onSuccess:
        	function(transport) {
                    zoneToUpdate.update(transport.responseJSON.content);
                    Tapestry.processScriptInReply(transport.responseJSON);
                }
            } );
    }
} 