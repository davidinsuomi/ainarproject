package com.playtech.filtertest.mixins;

import org.apache.tapestry5.Asset;
import org.apache.tapestry5.ClientElement;
import org.apache.tapestry5.MarkupWriter;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.annotations.AfterRender;
import org.apache.tapestry5.annotations.Environmental;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.InjectContainer;
import org.apache.tapestry5.annotations.Path;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.dom.Element;
import org.apache.tapestry5.ioc.annotations.Inject;

@IncludeJavaScriptLibrary("progressindicator.js")
public class ProgressIndicator {

	@Inject
	@Path("progressindicator.gif")
	@Property
	private Asset indicatorImage;
	
	@Environmental
	private RenderSupport renderSupport;
	
	@InjectContainer
    private ClientElement element;
	
    @AfterRender
    public void afterRender(MarkupWriter writer) {
    	String imageId = element.getClientId() + "-indicator";
    	
    	renderSupport.addScript(String.format("new ProgressIndicator('%s', '%s');",
    			element.getClientId(), imageId));
    	writer.element("img", 
    			"id", imageId,
    			"src", indicatorImage,
    			"style", "padding-left: 7px; padding-right: 7px;");
    	writer.end();
    }

}
