package com.example.genzehat; // Sesuaikan package Anda

import android.os.Bundle;
import android.util.Log; // Tambahan untuk melihat log di Logcat
import android.view.View;
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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // 1. Kenalkan Komponen
        rvHistory = findViewById(R.id.rvHistory);
        progressBar = findViewById(R.id.progressBar);

        // 2. Setting RecyclerView
        rvHistory.setLayoutManager(new LinearLayoutManager(this));

        // 3. Panggil Data
        getDataHistory();
    }

    private void getDataHistory() {
        // --- LANGKAH 1: CEK TOKEN DARI INTENT ---
        String token = getIntent().getStringExtra("TOKEN");

        // DEBUG: Cek apakah token benar-benar sampai?
        if (token == null || token.isEmpty()) {
            // JIKA MUNCUL PESAN INI: Masalah ada di LoginActivity (Salah kirim kunci Intent)
            Log.e("DEBUG_APP", "Token KOSONG/NULL dari Intent");
            Toast.makeText(this, "GAWAT: Token Kosong! Login Ulang.", Toast.LENGTH_LONG).show();
            progressBar.setVisibility(View.GONE);
            return; // Berhenti, jangan lanjut ke server
        }

        // Token ada, beri tahu user
        Log.d("DEBUG_APP", "Token Diterima: " + token);
        // Toast.makeText(this, "Token Ada. Menghubungi Server...", Toast.LENGTH_SHORT).show();

        // --- LANGKAH 2: PANGGIL SERVER ---
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        // Panggil API dengan Header Bearer
        Call<List<HistoryModel>> call = apiService.getHistory("Bearer " + token);

        call.enqueue(new Callback<List<HistoryModel>>() {
            @Override
            public void onResponse(Call<List<HistoryModel>> call, Response<List<HistoryModel>> response) {
                progressBar.setVisibility(View.GONE); // Sembunyikan loading

                // DEBUG: Cek Kode Respon Server
                Log.d("DEBUG_APP", "Response Code: " + response.code());

                if (response.isSuccessful() && response.body() != null) {
                    List<HistoryModel> dataList = response.body();

                    // DEBUG: Cek Jumlah Data
                    Log.d("DEBUG_APP", "Jumlah Data: " + dataList.size());

                    if (dataList.isEmpty()) {
                        // JIKA MUNCUL PESAN INI: Koneksi sukses, tapi User ini memang belum punya history di database
                        Toast.makeText(MainActivity.this, "Data Kosong (User ini belum punya history)", Toast.LENGTH_LONG).show();
                    } else {
                        // JIKA MUNCUL PESAN INI: Data berhasil diambil!
                        Toast.makeText(MainActivity.this, "Berhasil! Ada " + dataList.size() + " data.", Toast.LENGTH_SHORT).show();

                        // Pasang Adapter
                        adapter = new HistoryAdapter(MainActivity.this, dataList);
                        rvHistory.setAdapter(adapter);
                    }

                } else {
                    // JIKA MUNCUL PESAN INI: Server menolak (Biasanya Error 401 Unauthorized atau 500 Server Error)
                    Toast.makeText(MainActivity.this, "Gagal Server: Kode " + response.code(), Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<List<HistoryModel>> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                // JIKA MUNCUL PESAN INI: Tidak ada internet atau server mati
                Log.e("DEBUG_APP", "Failure: " + t.getMessage());
                Toast.makeText(MainActivity.this, "Error Koneksi: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }
}

