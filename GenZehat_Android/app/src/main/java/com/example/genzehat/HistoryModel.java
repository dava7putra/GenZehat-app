package com.example.genzehat; // Pastikan nama ini sesuai dengan package Anda!

// BARIS INI YANG MENGHILANGKAN MERAH:
import com.google.gson.annotations.SerializedName;
import android.graphics.Color;
public class HistoryModel {

    // KIRI: Nama kolom di Database (Wajib Sama Persis)
    // KANAN: Nama variabel di Java (Boleh beda, tapi biar rapi disamakan konteksnya)

    @SerializedName("id")
    private int id;

    @SerializedName("minggu_ke")
    private String mingguKe;

    @SerializedName("latihan_selesai")
    private String latihanSelesai;

    @SerializedName("total_latihan")
    private String totalLatihan;

    @SerializedName("persentase")
    private String persentase;

    // --- Constructor (Opsional) ---

    public HistoryModel(String mingguKe, String latihanSelesai, String totalLatihan, String persentase) {
        this.mingguKe = mingguKe;
        this.latihanSelesai = latihanSelesai;
        this.totalLatihan = totalLatihan;
        this.persentase = persentase;
    }

    // --- Getter Methods (Wajib Ada untuk Adapter) ---

    public int getId() {
        return id;
    }

    public String getMingguKe() {
        return mingguKe;
    }

    public String getLatihanSelesai() {
        return latihanSelesai;
    }

    public String getTotalLatihan() {
        return totalLatihan;
    }

    public String getPersentase() {
        return persentase;
    }
    public int getStatusColor() {
        try {
            int persen = Integer.parseInt(this.persentase);
            if (persen >= 100) {
                return Color.parseColor("#4CAF50"); // Hijau jika selesai 100%
            } else if (persen >= 50) {
                return Color.parseColor("#FFC107"); // Kuning jika setengah
            } else {
                return Color.parseColor("#F44336"); // Merah jika sedikit
            }
        } catch (NumberFormatException e) {
            return Color.GRAY; // Warna default jika data error
        }
    }
}
