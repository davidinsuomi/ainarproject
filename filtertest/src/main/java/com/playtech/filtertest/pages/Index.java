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

import org.apache.tapestry5.OptionGroupModel;
import org.apache.tapestry5.OptionModel;
import org.apache.tapestry5.SelectModel;
import org.apache.tapestry5.SelectModelVisitor;
import org.apache.tapestry5.ValueEncoder;
import org.apache.tapestry5.annotations.Persist;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.internal.OptionModelImpl;
import org.apache.tapestry5.ioc.annotations.Inject;
import org.apache.tapestry5.ioc.annotations.InjectService;
import org.apache.tapestry5.upload.services.UploadedFile;

import utils.SupportedEncodingsSelectModel;

import com.playtech.filtertest.services.Pointless;

/**
 * Start page of application filtertest.
 */
public class Index
{
	@InjectService("pointlessness")
	private Pointless jura;
	
	@Inject
	private HttpServletRequest request;
	
	public Date getCurrentTime() 
	{ 
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
		return new Date();
	}
	@Property
	private UploadedFile file;
	
	@Property
	private String text1;
	
	public void onSuccess()
	{
		System.out.println(file.getFileName());
		System.out.println(text1);; 
	}
	
	@Property
	@Persist("flash")
	private Charset selectedEncoding;
	
	public List<Charset> getSupportedEncodings(){
		return new ArrayList((Charset.availableCharsets()).values());
	}
	
	public ValueEncoder getEncodingValueEncoder(){
		ValueEncoder encodingValueEncoder = new ValueEncoder(){

			public String toClient(Object arg0) {
				return arg0.toString();
			}

			public Object toValue(String arg0) {
				return Charset.forName(arg0);
			}
		};
		return encodingValueEncoder;
	}
	
	/*
	public SelectModel getSupportedEncodings(){
		
	}
	*/
	/*
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
