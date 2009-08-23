package com.playtech.filtertest.services;

import org.apache.tapestry5.ioc.Invocation;
import org.apache.tapestry5.ioc.MethodAdvice;
import org.apache.tapestry5.ioc.services.AspectDecorator;

public class PointlessDecoratorImpl implements PointlessDecorator{
	public final AspectDecorator aspectDecorator;
	
	public PointlessDecoratorImpl(AspectDecorator aspectDecorator){
		this.aspectDecorator = aspectDecorator;
	}
	
	public <T> T build(Class<T> serviceInterface, T delegate, String serviceId){
		MethodAdvice advice = new MethodAdvice(){
    		public void advise(Invocation invocation){
    			invocation.proceed();
    			
    			//System.out.println(invocation.getResult());
    			if(invocation.getResultType().equals(int.class) && 
    					Integer.parseInt(invocation.getResult().toString())>310){
    				invocation.overrideResult(new Integer(1000).intValue());
    			}
    		}
    	};
    	
    	return aspectDecorator.build(serviceInterface, delegate, advice,
    			String.format("<Logging interceptor for %s(%s)>", serviceId,
                      serviceInterface.getName()));
	}
}
