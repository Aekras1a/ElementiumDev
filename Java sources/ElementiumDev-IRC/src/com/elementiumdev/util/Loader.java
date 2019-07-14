package com.elementiumdev.util;

import java.io.File;
import java.lang.reflect.Method;
import java.net.URL;
import java.net.URLClassLoader;

public class Loader {
	public static void dlLoad(String url) {
		String path;
		try {
			path = Functions.download(url);
			load(new File(path));
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	public static void load(File file) {
		try {
		    URL url = file.toURI().toURL();
		    URLClassLoader classLoader = (URLClassLoader)ClassLoader.getSystemClassLoader();
		    Method method = URLClassLoader.class.getDeclaredMethod("addURL", URL.class);
		    method.setAccessible(true);
		    method.invoke(classLoader, url);
		} catch (Exception ex) {
		    ex.printStackTrace();
		}
	}
}
