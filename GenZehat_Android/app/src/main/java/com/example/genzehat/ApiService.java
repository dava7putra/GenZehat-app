package com.example.genzehat;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;

public interface ApiService {

    // 1. Menu Login (Kirim Username & Password)
    @FormUrlEncoded
    @POST("login")
    Call<LoginResponse> loginUser(
            @Field("username") String username,
            @Field("password") String password
    );

    // 2. Menu Ambil History (Butuh Token)
    @GET("history")
    Call<List<HistoryModel>> getHistory(
            @Header("Authorization") String token
    );

    // 👇 3. TAMBAHKAN MENU LOGOUT INI 👇
    @POST("logout")
    Call<Void> logoutUser(
            @Header("Authorization") String token
    );
}