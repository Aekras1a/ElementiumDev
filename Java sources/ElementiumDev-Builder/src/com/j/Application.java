/*
 * Decompiled with CFR 0_110.
 */
package com.j;

import com.j.compiler.StubCompiler;

public class Application {
    private static String[] args;

    public static void main(String[] args) {
        Application.args = args;
        StubCompiler.execute();
    }

    public static String[] getArguments() {
        return args;
    }
}

