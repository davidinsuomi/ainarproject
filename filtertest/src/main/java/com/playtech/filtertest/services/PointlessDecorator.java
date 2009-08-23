package com.playtech.filtertest.services;

public interface PointlessDecorator {
	public <T> T build(Class<T> serviceInterface, T delegate, String serviceId);
}
