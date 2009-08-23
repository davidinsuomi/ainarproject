package com.playtech.filtertest.pages;

import java.io.File;
import java.util.Date;

import org.apache.tapestry5.annotations.Property;
import org.apache.tapestry5.ioc.annotations.Inject;
import org.apache.tapestry5.ioc.annotations.InjectService;
import org.apache.tapestry5.upload.services.UploadedFile;

import com.playtech.filtertest.services.Pointless;

/**
 * Start page of application filtertest.
 */
public class Index
{
	@InjectService("pointlessness")
	private Pointless jura;
	
	public Date getCurrentTime() 
	{ 
		jura.addInt();
		System.out.println(jura.getInt());
		return new Date();
	}
	@Property
	private UploadedFile file;
	
	@Property
	private String text1;
	
	public void onSuccess()
	{
		System.out.println(file.getFileName());
		System.out.println(text1);
	}

}
