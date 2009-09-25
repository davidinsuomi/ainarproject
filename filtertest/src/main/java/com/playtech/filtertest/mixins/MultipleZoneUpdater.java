package com.playtech.filtertest.mixins;

import java.util.Map;
import java.util.StringTokenizer;

import org.apache.tapestry5.Block;
import org.apache.tapestry5.ComponentResources;
import org.apache.tapestry5.Link;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.annotations.CleanupRender;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.OnEvent;
import org.apache.tapestry5.annotations.Parameter;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.ioc.annotations.Inject;

@IncludeJavaScriptLibrary("MultipleZoneUpdater.js")
public class MultipleZoneUpdater {

    private static final String ZONE_UPDATE_EVENT_NAME = "EXTRA_ZONE_UPDATE";

    /**
     * A Map of Zone id's mapped to the Block id's to update the zone
     */
    @Parameter(required = true)
    private Map<String, String> zoneBlocks;

    @Inject
    private RenderSupport renderSupport;

    /**
     * A comma seperated list of css identifiers of all elements that should
     * trigger the multiple zone update.
     * in case of an a: onmouseup
     * in case of a zone: once loaded
     * in case of a form: onsubmit
     */
    @Property
    @Parameter(required = true, defaultPrefix = "literal")
    private String zoneTriggerCssIdentifiers;

    /**
     *
     */
    @Inject
    private ComponentResources componetResources;

    /**
     * @param blockId
     * @return
     */
    @SuppressWarnings("unused")
	@OnEvent(value = ZONE_UPDATE_EVENT_NAME)
    private Object handleZoneUpdate(String blockId) {
        ComponentResources handle = componetResources;
        Block ret = null;
        while(ret == null && handle != null) {
            ret = handle.findBlock(blockId);
            handle = handle.getContainerResources();
        }
        return ret;
    }

    /**
     * @param writer
     */
	@CleanupRender
    private void addScripts () {
        Link link = componetResources.createActionLink(ZONE_UPDATE_EVENT_NAME, false);
        StringBuffer buffy = new StringBuffer();
        buffy.append("'({");
        int i = 0;
        for (String key : zoneBlocks.keySet()) {
            if(buffy.length() > 4) {
                buffy.append(",");
            }
            buffy.append("\"zoneBlock_" + i + "\":{\"zone\":\"" + key +
            		"\",\"block\":\"" + zoneBlocks.get(key) + "\"}");
            i++;
        }
        buffy.append("})'");

        StringBuffer zoneTriggers = new StringBuffer("[");
        StringTokenizer strt = new StringTokenizer(zoneTriggerCssIdentifiers, ",");
        while (strt.hasMoreElements()) {
            if(zoneTriggers.length() > 1) {
                zoneTriggers.append(",");
            }
            zoneTriggers.append("'" + strt.nextToken() + "'");
        }
        zoneTriggers.append("]");
        //have the call executed at window load so that the zone's are
        //initialized before this call is made
        renderSupport.addScript("Event.observe(window, 'load', function(){" +
        		"MultipleZoneUpdater.attacheMultipleZoneUpdateTriggers('" +
        		link.toAbsoluteURI() + "'," + zoneTriggers.toString() + "," +
        		buffy.toString() +");});");
    }

} 
