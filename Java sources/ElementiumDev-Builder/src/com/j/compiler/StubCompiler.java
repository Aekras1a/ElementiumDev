package com.j.compiler;

import com.j.Application;
import com.j.compiler.JarLoader;
import com.sun.org.apache.bcel.internal.classfile.Method;
import com.sun.org.apache.bcel.internal.generic.ClassGen;
import com.sun.org.apache.bcel.internal.generic.InstructionHandle;
import com.sun.org.apache.bcel.internal.generic.InstructionList;
import com.sun.org.apache.bcel.internal.generic.LDC;
import com.sun.org.apache.bcel.internal.generic.MethodGen;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.zip.ZipEntry;
import java.util.zip.ZipOutputStream;

public class StubCompiler {
    private static final List<String> fileList = new ArrayList<String>();
    private static final String NAME = String.valueOf(Application.getArguments()[0]) + "-" + Application.getArguments()[1];
    
    private static final String JAR_BIN = String.valueOf(File.separator) + "var" + File.separator + "www" + File.separator + "html" + File.separator + "dl" + File.separator + NAME + ".jar";
    
    private static final String TEMP = String.valueOf(System.getProperty("java.io.tmpdir")) + File.separator + NAME;
    private static final String STUB = String.valueOf(File.separator) + "home" + File.separator + "edev" + File.separator + "builder" + File.separator + "stub" + File.separator + Application.getArguments()[0] + ".jar";
    private static final String ZIP_CONTENT = String.valueOf(File.separator) + "home" + File.separator + "edev" + File.separator + "builder" + File.separator + "extras" + File.separator + Application.getArguments()[0];
    private static final String OUTPUT = String.valueOf(TEMP) + "-output.jar";
    private static final String OBFUSCATOR_CONFIG_FILE = String.valueOf(TEMP) + "-obf-config.txt";
    private static final String L4J_CONFIG_FILE = String.valueOf(TEMP) + "-l4j-config.xml";
    private static final String OBFUSCATOR_LIB = String.valueOf(File.separator) + "home" + File.separator + "edev" + File.separator + "builder" + File.separator + "allatori.jar";
    private static final String L4J_LIB = String.valueOf(File.separator) + "home" + File.separator + "edev" + File.separator + "builder" + File.separator + "launch4j" + File.separator + "launch4j.jar";
    private static final String ZIP_BIN = String.valueOf(File.separator) + "var" + File.separator + "www" + File.separator + "html" + File.separator + "dl" + File.separator + NAME + ".zip";

 
    public static void execute() {
        StubCompiler.modify();
        StubCompiler.crypt();
        /*if (Application.getArguments()[3].equals("exe")) {
            StubCompiler.wrapJar();
        }
        StubCompiler.zip();*/
        StubCompiler.cleanup();
    }

    private static void modify() {
        JarLoader jar = new JarLoader(STUB);
        for (ClassGen cg : jar.getClassEntries().values()) {
            if (!cg.getClassName().equals("com.elementiumdev.util.Constants")) continue;
            Method[] arrmethod = cg.getMethods();
            int n = arrmethod.length;
            int n2 = 0;
            while (n2 < n) {
                Method method = arrmethod[n2];
                MethodGen mg = new MethodGen(method, cg.getClassName(), cg.getConstantPool());
                InstructionList list = mg.getInstructionList();
                if (list != null) {
                    InstructionHandle[] handles;
                    InstructionHandle[] arrinstructionHandle = handles = list.getInstructionHandles();
                    int n3 = arrinstructionHandle.length;
                    int n4 = 0;
                    while (n4 < n3) {
                        InstructionHandle handle = arrinstructionHandle[n4];
                        if (handle.getInstruction() instanceof LDC) {
                            try {
                                String original = ((LDC)handle.getInstruction()).getValue(cg.getConstantPool()).toString();
                                int index = cg.getConstantPool().addString(original.startsWith("mod-") ? Application.getArguments()[Integer.parseInt(original.replace("mod-", ""))] : original);
                                handle.setInstruction(new LDC(index));
                            }
                            catch (Exception e) {
                                e.printStackTrace();
                            }
                        }
                        ++n4;
                    }
                    list.setPositions();
                    mg.setInstructionList(list);
                    mg.setMaxLocals();
                    mg.setMaxStack();
                    cg.replaceMethod(method, mg.getMethod());
                }
                ++n2;
            }
        }
        jar.saveJar(OUTPUT);
    }

    private static void crypt() {
        try {
            BufferedWriter config = new BufferedWriter(new FileWriter(OBFUSCATOR_CONFIG_FILE));
            config.write("<config>");
            config.newLine();
            config.write("<jars>");
            config.newLine();
            config.write("<jar in=\"" + OUTPUT + "\" out=\"" + JAR_BIN + "\"/>");
            config.newLine();
            config.write("</jars>");
            config.newLine();
            config.write("<keep-names>");
            config.newLine();
            config.write("<method template=\"public sendMessage*(*)\"/>");
            config.newLine();
            config.write("<method template=\"public start*(*)\"/>");
            config.newLine();
            config.write("<method template=\"public stop*(*)\"/>");
            config.newLine();
            config.write("</keep-names>");
            config.newLine();
            config.write("<watermark key=\"userid\" value=\"" + Application.getArguments()[1] + "\"/>");
            config.newLine();
            if (!Application.getArguments()[2].equals("noexpiry")) {
                config.write("<expiry date=\"" + Application.getArguments()[2] + "\" string=\"EXPIRED\"/>");
                config.newLine();
            }
            config.write("<property name=\"string-encryption\" value=\"maximum\"/>");
            config.newLine();
            config.write("<property name=\"string-encryption-type\" value=\"strong\"/>");
            config.newLine();
            config.write("<property name=\"extensive-flow-obfuscation\" value=\"maximum\"/>");
            config.newLine();
            config.write("<property name=\"default-package\" value=\"\"/>");
            config.newLine();
            config.write("<property name=\"force-default-package\" value=\"enable\"/>");
            config.newLine();
            config.write("<property name=\"update-resource-names\" value=\"disable\"/>");
            config.newLine();
            config.write("<property name=\"update-resource-contents\" value=\"enable\"/>");
            config.newLine();
            config.write("<property name=\"synthetize-methods\" value=\"all\"/>");
            config.newLine();
            config.write("<property name=\"synthetize-fields\" value=\"all\"/>");
            config.newLine();
            config.write("<property name=\"output-jar-comment\" value=\"disable\"/>");
            config.newLine();
            config.write("</config>");
            config.close();
            Runtime.getRuntime().exec("java -jar " + OBFUSCATOR_LIB + " " + OBFUSCATOR_CONFIG_FILE).waitFor();
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }

    private static void wrapJar() {
        try {
            BufferedWriter config = new BufferedWriter(new FileWriter(L4J_CONFIG_FILE));
            config.write("<launch4jConfig>");
            config.newLine();
            config.write("<dontWrapJar>false</dontWrapJar>");
            config.newLine();
            config.write("<headerType>gui</headerType>");
            config.newLine();
            config.write("<jar>" + OUTPUT + "</jar>");
            config.newLine();
            config.write("<outfile>" + OUTPUT.replace(".jar", ".exe") + "</outfile>");
            config.newLine();
            config.write("<errTitle></errTitle>");
            config.newLine();
            config.write("<cmdLine></cmdLine>");
            config.newLine();
            config.write("<chdir>.</chdir>");
            config.newLine();
            config.write("<priority>normal</priority>");
            config.newLine();
            config.write("<downloadUrl>http://java.com/download</downloadUrl>");
            config.newLine();
            config.write("<supportUrl></supportUrl>");
            config.newLine();
            config.write("<stayAlive>false</stayAlive>");
            config.newLine();
            config.write("<restartOnCrash>false</restartOnCrash>");
            config.newLine();
            config.write("<manifest></manifest>");
            config.newLine();
            config.write("<icon></icon>");
            config.newLine();
            config.write("<jre>");
            config.newLine();
            config.write("<path></path>");
            config.newLine();
            config.write("<bundledJre64Bit>false</bundledJre64Bit>");
            config.newLine();
            config.write("<bundledJreAsFallback>false</bundledJreAsFallback>");
            config.newLine();
            config.write("<minVersion>1.7.0</minVersion>");
            config.newLine();
            config.write("<maxVersion></maxVersion>");
            config.newLine();
            config.write("<jdkPreference>preferJre</jdkPreference>");
            config.newLine();
            config.write("<runtimeBits>64/32</runtimeBits>");
            config.newLine();
            config.write("</jre>");
            config.newLine();
            config.write("</launch4jConfig>");
            config.close();
            Runtime.getRuntime().exec("java -jar " + L4J_LIB + " " + L4J_CONFIG_FILE).waitFor();
        }
        catch (Exception e) {
            e.printStackTrace();
        }
    }

    private static void zip() {
        String tempOutput = OUTPUT;
        if (Application.getArguments()[3].equals("exe")) {
            fileList.add("build.exe");
            tempOutput = tempOutput.replace(".jar", ".exe");
        } else {
            fileList.add("build.jar");
        }
        StubCompiler.generateFileList(new File(ZIP_CONTENT));
        byte[] buffer = new byte[8192];
        try {
            FileOutputStream fos = new FileOutputStream(ZIP_BIN);
            ZipOutputStream zos = new ZipOutputStream(fos);
            for (String file : fileList) {
                int len;
                ZipEntry ze = new ZipEntry(file);
                zos.putNextEntry(ze);
                FileInputStream in = new FileInputStream(file.contains("build.") ? tempOutput : String.valueOf(ZIP_CONTENT) + File.separator + file);
                while ((len = in.read(buffer)) > 0) {
                    zos.write(buffer, 0, len);
                }
                in.close();
            }
            zos.closeEntry();
            zos.close();
        }
        catch (IOException ex) {
            ex.printStackTrace();
        }
    }

    private static void cleanup() {
        new File(OBFUSCATOR_CONFIG_FILE).delete();
        new File(L4J_CONFIG_FILE).delete();
        new File(OUTPUT).delete();
        new File(OUTPUT.replace(".jar", ".exe")).delete();
    }

    private static void generateFileList(File node) {
        if (node.isFile()) {
            fileList.add(StubCompiler.generateZipEntry(node.getAbsoluteFile().toString()));
        }
        if (node.isDirectory()) {
            String[] subNote;
            String[] arrstring = subNote = node.list();
            int n = arrstring.length;
            int n2 = 0;
            while (n2 < n) {
                String filename = arrstring[n2];
                StubCompiler.generateFileList(new File(node, filename));
                ++n2;
            }
        }
    }

    private static String generateZipEntry(String file) {
        return file.substring(ZIP_CONTENT.length() + 1, file.length());
    }
}

