package com.example.genzehat; // Sesuaikan package Anda

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    private RecyclerView rvHistory;
    private ProgressBar progressBar;
    private HistoryAdapter adapter;
    private Button btnRefresh, btnLogout; // Deklarasi Tombol
    private String token; // Variabel global untuk token

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // 1. Kenalkan Komponen
        rvHistory = findViewById(R.id.rvHistory);
        progressBar = findViewById(R.id.progressBar);
        
        // 🚨 PASTIKAN ID TOMBOL INI SAMA DENGAN DI activity_main.xml 🚨
        btnRefresh = findViewById(R.id.btnRefresh); 
        btnLogout = findViewById(R.id.btnLogout);

        // 2. Setting RecyclerView
        rvHistory.setLayoutManager(new LinearLayoutManager(this));

        // 3. Ambil Token dari Intent Login
        token = getIntent().getStringExtra("TOKEN");

        // 4. Panggil Data Pertama Kali
        getDataHistory();

        // ==========================================
        // FITUR 2: TOMBOL REFRESH
        // ==========================================
        btnRefresh.setOnClickListener(v -> {
            Toast.makeText(MainActivity.this, "Menyegarkan data...", Toast.LENGTH_SHORT).show();
            getDataHistory(); // Panggil ulang fungsinya!
        });

        // ==========================================
        // FITUR 4: TOMBOL LOGOUT
        // ==========================================
        btnLogout.setOnClickListener(v -> {
            prosesLogout();
        });
    }

    private void getDataHistory() {
        
        if (token == null || token.isEmpty()) {

            Toast.makeText(this, "GAWAT: Token Kosong! Login Ulang.", Toast.LENGTH_LONG).show();
            progressBar.setVisibility(View.GONE);
            return; 
        }

        progressBar.setVisibility(View.VISIBLE); // Tampilkan loading saat refresh
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        Call<List<HistoryModel>> call = apiService.getHistory("Bearer " + token);

        call.enqueue(new Callback<List<HistoryModel>>() {
            @Override
            public void onResponse(Call<List<HistoryModel>> call, Response<List<HistoryModel>> response) {
                progressBar.setVisibility(View.GONE); 

                if (response.isSuccessful() && response.body() != null) {
                    List<HistoryModel> dataList = response.body();
                    
                    if (dataList.isEmpty()) {
                        Toast.makeText(MainActivity.this, "Data Kosong (Belum ada jadwal)", Toast.LENGTH_SHORT).show();
                    } else {
                        adapter = new HistoryAdapter(MainActivity.this, dataList);
                        rvHistory.setAdapter(adapter);
                    }

                } else {

                    Toast.makeText(MainActivity.this, "Gagal Server: Kode " + response.code(), Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<List<HistoryModel>> call, Throwable t) {
                progressBar.setVisibility(View.GONE);

                Toast.makeText(MainActivity.this, "Error Koneksi: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private void prosesLogout() {
        if (token == null || token.isEmpty()) {
            kembaliKeLogin(); // Jika token sudah hilang, langsung lempar keluar
            return;
        }

        progressBar.setVisibility(View.VISIBLE);
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        
        // Tembak API Logout ke Laravel
        apiService.logoutUser("Bearer " + token).enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                progressBar.setVisibility(View.GONE);
                Toast.makeText(MainActivity.this, "Berhasil Logout", Toast.LENGTH_SHORT).show();
                kembaliKeLogin();
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                // Walaupun gagal nembak server (misal sinyal putus), tetap paksa keluar
                Toast.makeText(MainActivity.this, "Logout Offline Mode", Toast.LENGTH_SHORT).show();
                kembaliKeLogin();
            }
        });
    }

    private void kembaliKeLogin() {
        Intent intent = new Intent(MainActivity.this, LoginActivity.class);
        // Hapus history halaman agar user tidak bisa tekan tombol back ke MainActivity
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK); 
        startActivity(intent);
        finish();
    }
}
