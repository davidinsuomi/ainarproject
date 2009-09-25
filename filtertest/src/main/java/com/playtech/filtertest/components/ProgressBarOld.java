package com.playtech.filtertest.components;

import org.apache.tapestry5.Asset;
import org.apache.tapestry5.ClientElement;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.annotations.AfterRender;
import org.apache.tapestry5.annotations.Environmental;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.Parameter;
import org.apache.tapestry5.annotations.Path;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.ioc.annotations.Inject;

@IncludeJavaScriptLibrary("progressScript.js")
public class ProgressBarOld {

	@Inject
	@Path("progressbar.gif")
	@Property
	private Asset progressGif;
	
	@Parameter
	@Property
	private ClientElement element;
	
	@Environmental
	private RenderSupport renderSupport;

	void setupRender() {
	}
	
	//addDynamicScriptBlock(Element) - 
}
