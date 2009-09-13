package com.playtech.filtertest.mixins;

import org.apache.tapestry5.Asset;
import org.apache.tapestry5.ClientElement;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.annotations.AfterRender;
import org.apache.tapestry5.annotations.Environmental;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.InjectContainer;
import org.apache.tapestry5.annotations.Path;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.dom.Element;
import org.apache.tapestry5.ioc.annotations.Inject;

@IncludeJavaScriptLibrary("progressScript.js")
public class ProgressBar {

	@Inject
	@Path("progressbar.gif")
	@Property
	private Asset progressGif;
	
	@Environmental
	private RenderSupport renderSupport;
	
	@InjectContainer
    private ClientElement element;
	
    @AfterRender
    public void afterRender() {
            renderSupport.addScript(String.format("new Confirm('%s', '%s');",
            		element.getClientId(), progressGif));
    }

}
