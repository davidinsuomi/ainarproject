package com.playtech.filtertest.components;

import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.List;

import org.apache.tapestry5.Field;
import org.apache.tapestry5.FieldValidator;
import org.apache.tapestry5.MarkupWriter;
import org.apache.tapestry5.ValidationException;
import org.apache.tapestry5.Validator;
import org.apache.tapestry5.ValueEncoder;
import org.apache.tapestry5.annotations.Component;
import org.apache.tapestry5.annotations.OnEvent;
import org.apache.tapestry5.annotations.Persist;
import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.beaneditor.Validate;
import org.apache.tapestry5.corelib.components.Form;
import org.apache.tapestry5.corelib.components.TextField;
import org.apache.tapestry5.validator.Required;

public class FormComp {
	@Persist("flash")
	@Property
	private String output;
	
	@Property
	//@Validate("required")
	private String text1;
	
	@OnEvent(value = "success")
	public void doSearch() {
		if(output == null)
			output = "";
		output = text1;
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
}
