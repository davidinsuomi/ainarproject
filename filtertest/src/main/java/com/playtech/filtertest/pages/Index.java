package com.playtech.filtertest.pages;

import java.io.File;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Date;
import java.util.List;
import java.util.StringTokenizer;

import javax.servlet.ServletRequest;
import javax.servlet.http.HttpServletRequest;

import org.apache.tapestry5.ClientElement;
import org.apache.tapestry5.ComponentResources;
import org.apache.tapestry5.ContentType;
import org.apache.tapestry5.Field;
import org.apache.tapestry5.FieldValidator;
import org.apache.tapestry5.Link;
import org.apache.tapestry5.MarkupWriter;
import org.apache.tapestry5.OptionGroupModel;
import org.apache.tapestry5.OptionModel;
import org.apache.tapestry5.RenderSupport;
import org.apache.tapestry5.SelectModel;
import org.apache.tapestry5.SelectModelVisitor;
import org.apache.tapestry5.ValidationException;
import org.apache.tapestry5.ValueEncoder;
import org.apache.tapestry5.annotations.AfterRender;
import org.apache.tapestry5.annotations.Component;
import org.apache.tapestry5.annotations.Environmental;
import org.apache.tapestry5.annotations.IncludeJavaScriptLibrary;
import org.apache.tapestry5.annotations.InjectComponent;
import org.apache.tapestry5.annotations.InjectContainer;
import org.apache.tapestry5.annotations.OnEvent;
import org.apache.tapestry5.annotations.Persist;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.beaneditor.Validate;
import org.apache.tapestry5.corelib.components.Form;
import org.apache.tapestry5.corelib.components.TextField;
import org.apache.tapestry5.corelib.components.Zone;
import org.apache.tapestry5.internal.OptionModelImpl;
import org.apache.tapestry5.internal.services.FieldValidatorImpl;
import org.apache.tapestry5.ioc.annotations.Inject;
import org.apache.tapestry5.ioc.annotations.InjectResource;
import org.apache.tapestry5.ioc.annotations.InjectService;
import org.apache.tapestry5.services.Request;
import org.apache.tapestry5.services.ResponseRenderer;
import org.apache.tapestry5.upload.services.UploadedFile;
import org.apache.tapestry5.util.TextStreamResponse;

import utils.SupportedEncodingsSelectModel;

import com.playtech.filtertest.components.FormComp;
import com.playtech.filtertest.services.Pointless;

/**
 * Start page of application filtertest.
 */
@IncludeJavaScriptLibrary("ajaxbutton.js")
public class Index
{
	@InjectService("pointlessness")
	private Pointless jura;
	
	@Inject
	private Request request;
	
	@Environmental
	private RenderSupport renderSupport;
	
	@Inject
	private ComponentResources resources;
	
	@InjectComponent
    private ClientElement ajaxbutton;
	
	@Inject
    private ResponseRenderer responseRenderer;
	
	private String updatetextid = "updateparagraph";
	
    @AfterRender
    public void afterRender(MarkupWriter writer) {
    	System.out.println(ajaxbutton.getClientId());
    	Link link = resources.createEventLink("ajaxbuttonrequest");
    	renderSupport.addScript(String.format("new Ajaxbutton('%s', '%s', '%s');",
    			ajaxbutton.getClientId(), link.toAbsoluteURI(), updatetextid));
    }
	
    public String getUpdatetextid(){
    	return updatetextid;
    }
    
    @OnEvent("ajaxbuttonrequest")
    public Object onAjaxButtonClick(){
    	ContentType contentType = responseRenderer.findContentType(this);
    	System.out.println("See on button click");
    	return new TextStreamResponse(contentType.toString(), (new Date()).toString()); 
    }
    
    @InjectComponent
    private Zone timezone;
    
    @OnEvent(value = "action", component = "updatezone")
    public Object updateTimezone(){
    	return timezone.getBody();
    }
    
    @OnEvent(value = "success", component = "timezoneform")
    public Object updateTimezoneForm(){
    	return timezone.getBody();
    }
    
	public Date getCurrentTime() 
	{
		/*
		for(String s : request.getHeaderNames()){
			System.out.println(s + " " + request.getHeader(s));
		}
		
		jura.addInt();
		System.out.println(jura.getInt());
		System.out.println("-------------------");
		
		String encodingRecomendations = "";
		StringTokenizer acceptEncodingTokens = 
			new StringTokenizer(request.getHeader("Accept-Charset"), ";");
		if(acceptEncodingTokens != null)
			acceptEncodingTokens = new StringTokenizer(acceptEncodingTokens.nextToken(),",");
		if(acceptEncodingTokens != null){
			while(acceptEncodingTokens.hasMoreElements())
				System.out.println(acceptEncodingTokens.nextToken());
		}
		System.out.println("-------------------");
		*/
		
		return new Date();
	}
	@Property
	private UploadedFile file;
	
	
	/*
	public void onSuccess()
	{
		System.out.println(file.getFileName());
		System.out.println(text1);; 
	}
	*/

	@Inject
	private Request req;
	
	
	@OnEvent(value = "prepareForSubmit")
	public void doValidation() throws InterruptedException {
		/*
		Form myform = (Form)resources.getEmbeddedComponent("something1").getComponentResources().getEmbeddedComponent("form2");
		String value = req.getParameter("text1");
		//TextField text1 = (TextField)resources.getEmbeddedComponent("something1").getComponentResources().getEmbeddedComponent("text1");
		//if(text1. == null || text1.toString().equals("")){
			myform.recordError("hakka toole");
			System.out.println(value);
			for(String s : req.getParameterNames()){
				System.out.println(s);
			}
		*/
	}
	
	/*
	public SelectModel getSupportedEncodings(){
		
	}

	public SelectModel getSupportedEncodings(){
		SelectModel encodingModel = new EncodingModel extends AbstractSelectmodel{
			public List<OptionGroupModel> getOptionGroups() {
				return null;
			}

			public List<OptionModel> getOptions() {
				List<OptionModel> optionList = new ArrayList<OptionModel>(0);
				Collection<Charset> charSets = (Charset.availableCharsets()).values();
				
				for(Object charSet : charSets){
					optionList.add(new OptionModelImpl(charSet.toString(), (Charset)charSet));
					System.out.println(charSet.toString());
				}
				
				return optionList;
			}

			public void visit(SelectModelVisitor visitor) {}
		};
		return encodingModel;
	}
	*/
	
	/*
	public SelectModel getSupportedEncodings(){
		SelectModel encodingModel = new SelectModel(){
			public List<OptionGroupModel> getOptionGroups() {
				return null;
			}

			public List<OptionModel> getOptions() {
				List<OptionModel> optionList = new ArrayList<OptionModel>(0);
				Collection<Charset> charSets = (Charset.availableCharsets()).values();
				
				for(Object charSet : charSets){
					optionList.add(new OptionModelImpl(charSet.toString(), (Charset)charSet));
				}
				
				return optionList;
			}

			public void visit(SelectModelVisitor visitor) {}
		};
		return encodingModel;
	}
	*/

}
