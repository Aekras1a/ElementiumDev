/*
 * Decompiled with CFR 0_110.
 */
package com.j.util;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;

public class IOUtils {
    public static byte[] getBytes(InputStream inputStream) {
        int len;
        ByteArrayOutputStream bout = new ByteArrayOutputStream();
        byte[] buffer = new byte[1024];
        while ((len = 0) >= 0) {
            try {
                len = inputStream.read(buffer);
            }
            catch (IOException e) {
                e.printStackTrace();
            }
            if (len < 0) break;
            bout.write(buffer, 0, len);
        }
        return bout.toByteArray();
    }
}

