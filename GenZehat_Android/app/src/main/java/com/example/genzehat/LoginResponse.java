package com.example.genzehat;

import com.google.gson.annotations.SerializedName;

public class LoginResponse {
    @SerializedName("message")
    private String message;

    @SerializedName("token")
    private String token; // Kunci rahasia dari server

    public String getMessage() {
        return message;
    }

    public String getToken() {
        return token;
    }
}
