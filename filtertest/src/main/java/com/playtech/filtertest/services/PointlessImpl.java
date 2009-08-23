package com.playtech.filtertest.services;

public class PointlessImpl implements Pointless{
	public int value;
	
	public PointlessImpl(int value){
		this.value=value;
	}
	
	public int getInt(){
		return value;
	}
	
	public synchronized void addInt(){
		value++;
	}
	
}
