package com.playtech.filtertest.mixins;

import java.io.Writer;

import org.apache.tapestry5.Asset;
import org.apache.tapestry5.ClientElement;
import org.apache.tapestry5.MarkupWriter;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.annotations.AfterRender;
import org.apache.tapestry5.annotations.Environmental;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.IncludeStylesheet;
import org.apache.tapestry5.annotations.InjectContainer;
import org.apache.tapestry5.annotations.Parameter;
import org.apache.tapestry5.annotations.Path;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.ioc.annotations.Inject;

@IncludeJavaScriptLibrary("ProgressBar.js")
@IncludeStylesheet("ProgressBar.css")
public class ProgressBar {
	
	@Inject
	@Path("white.gif")
	@Property
	private Asset whiteImage;
	
	@Inject
	@Path("statusindicatorGif.gif")
	@Property
	private Asset statusindicatorGif;
	
	@Environmental
	private RenderSupport renderSupport;
	
	/*
	 * The time in seconds while the progressbar fills from 0% to 100%.
	 */
	@Parameter(value = "5")
	private double progressTime;
	
	@InjectContainer
    private ClientElement element;
		
	@AfterRender
    public void afterRender(MarkupWriter writer) {
		
    	renderSupport.addScript(String.format("new ProgressBar('%s', '%s');", 
    			element.getClientId(), progressTime));
    	
    	if(renderSupport.allocateClientId("progressBarFrame")
    			.equals("progressBarFrame")){
    		
    		writer.element("div",
    				"id", "progressBarFrame",
    				"class", "progressBar_frame",
    				"style", "display: none;"
    				);
    			writer.write("Please wait");
    			writer.element("div", 
    					"id", "progressBarSatusIndicator", 
    					"class", "progressBar_statusBar");
    				writer.element("img", 
    						"id", "progressBarWhiteOverlay",
    						"class", "progressBar_whiteOverlay",
    						"src", whiteImage);
    				writer.end();
    			writer.end();
    		writer.end();
    	}
    }
}
