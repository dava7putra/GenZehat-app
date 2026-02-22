package com.example.genzehat;

import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {
    // GANTI IP INI DENGAN IP LAPTOP ANDA (Cek pakai ipconfig)
    // Contoh: "http://192.168.1.5:8000/api/"
    private static final String BASE_URL = "http://192.168.169.2:8000/api/";

    private static Retrofit retrofit;

    public static Retrofit getClient() {
        if (retrofit == null) {
            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL)
                    .addConverterFactory(GsonConverterFactory.create())
                    .build();
        }
        return retrofit;
    }
}
