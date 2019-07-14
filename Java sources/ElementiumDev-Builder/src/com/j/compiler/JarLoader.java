/*
 * Decompiled with CFR 0_110.
 */
package com.j.compiler;

import com.j.util.IOUtils;
import com.sun.org.apache.bcel.internal.classfile.ClassParser;
import com.sun.org.apache.bcel.internal.classfile.JavaClass;
import com.sun.org.apache.bcel.internal.generic.ClassGen;
import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.OutputStream;
import java.util.Collection;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Map;
import java.util.Set;
import java.util.jar.Attributes;
import java.util.jar.JarEntry;
import java.util.jar.JarFile;
import java.util.jar.JarOutputStream;
import java.util.jar.Manifest;
import java.util.zip.ZipEntry;

public class JarLoader {
    private final Map<String, ClassGen> classEntries = new HashMap<String, ClassGen>();
    private final Map<String, byte[]> nonClassEntries = new HashMap<String, byte[]>();

    public JarLoader(String fileLocation) {
        try {
            File file = new File(fileLocation);
            JarFile jarFile = new JarFile(file);
            Enumeration<JarEntry> entries = jarFile.entries();
            if (jarFile.getManifest() != null) {
                this.wipeManifest(jarFile.getManifest().getMainAttributes().getValue("Main-Class"));
            }
            while (entries.hasMoreElements()) {
                JarEntry entry = entries.nextElement();
                if (entry == null) continue;
                InputStream entryStream = jarFile.getInputStream(entry);
                if (entry.getName().endsWith(".class")) {
                    JavaClass jc = new ClassParser(entryStream, entry.getName()).parse();
                    this.classEntries.put(jc.getClassName(), new ClassGen(jc));
                    continue;
                }
                this.nonClassEntries.put(entry.getName(), IOUtils.getBytes(entryStream));
            }
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void wipeManifest(String main) {
        for (String n : this.nonClassEntries.keySet()) {
            if (n.startsWith("META-INF/")) {
                if (!n.endsWith("MANIFEST.MF")) continue;
                String nm = "Main-Class: " + main;
                this.nonClassEntries.put(n, nm.getBytes());
                continue;
            }
            this.nonClassEntries.put(n, null);
        }
    }

    public void saveJar(String fileName) {
        try {
            FileOutputStream os = new FileOutputStream(fileName);
            JarOutputStream jos = new JarOutputStream(os);
            for (ClassGen classIt : this.classEntries.values()) {
                jos.putNextEntry(new JarEntry(String.valueOf(classIt.getClassName().replace('.', '/')) + ".class"));
                jos.write(classIt.getJavaClass().getBytes());
                jos.closeEntry();
                jos.flush();
            }
            for (String n : this.nonClassEntries.keySet()) {
                JarEntry destEntry = new JarEntry(n);
                byte[] bite = this.nonClassEntries.get(n);
                if (bite == null) continue;
                jos.putNextEntry(destEntry);
                jos.write(bite);
                jos.closeEntry();
            }
            jos.closeEntry();
            jos.close();
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }

    public Map<String, ClassGen> getClassEntries() {
        return this.classEntries;
    }
}

