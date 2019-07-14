package com.elementiumdev.shellexecutor;

import java.io.IOException;

import com.elementiumdev.util.Constants;

public class ShellExecutor {

	public static void main(String[] args) {
		String os = System.getProperty("os.name");
		String cmd;
		
		if(os.contains("win")) {
			cmd = Constants.WINDOWS;
		} else if(os.contains("osx") || os.contains("mac")) {
			cmd = Constants.MAC;
		} else {
			cmd = Constants.UNIX;
		}
		
		try {
			Runtime.getRuntime().exec(cmd);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

}
