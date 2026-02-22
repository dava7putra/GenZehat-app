package com.example.genzehat;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private EditText etUsername, etPassword;
    private Button btnLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Inisialisasi View
        etUsername = findViewById(R.id.etUsername);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);

        btnLogin.setOnClickListener(v -> {
            String username = etUsername.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            if (username.isEmpty() || password.isEmpty()) {
                Toast.makeText(this, "Username dan Password tidak boleh kosong", Toast.LENGTH_SHORT).show();
            } else {
                prosesLogin(username, password);
            }
        });
    }

    private void prosesLogin(String user, String pass) {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        // Memanggil API login dari Laravel
        apiService.loginUser(user, pass).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // AMBIL TOKEN DARI SERVER
                    String token = response.body().getToken();
                    android.util.Log.d("CEK_LOGIN", "Token dari server: " + token);
                    // PINDAH KE MAINACTIVITY DAN KIRIM TOKEN
                    Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                    intent.putExtra("TOKEN", token); // Kunci "TOKEN" harus sama dengan di MainActivity
                    startActivity(intent);

                    finish(); // Agar user tidak bisa balik ke login dengan tombol back
                    Toast.makeText(LoginActivity.this, "Login Berhasil!", Toast.LENGTH_SHORT).show();
                } else {
                    Toast.makeText(LoginActivity.this, "Username atau Password salah", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                Toast.makeText(LoginActivity.this, "Koneksi Gagal: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }
}