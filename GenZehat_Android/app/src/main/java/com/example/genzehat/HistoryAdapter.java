package com.example.genzehat; // Sesuaikan jika package Anda berbeda (misal: com.example.genzehat.adapter)

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

public class HistoryAdapter extends RecyclerView.Adapter<HistoryAdapter.ViewHolder> {

    private Context context;
    private List<HistoryModel> listData;

    // Constructor
    public HistoryAdapter(Context context, List<HistoryModel> listData) {
        this.context = context;
        this.listData = listData;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // PENTING: Pastikan nama file XML Anda benar.
        // Di sini saya asumsikan namanya 'item_histori'.
        // Jika nama file XML Anda 'item_history_layout', ganti bagian R.layout.item_histori di bawah ini.
        View view = LayoutInflater.from(context).inflate(R.layout.item_history, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        HistoryModel data = listData.get(position);

        // 1. Set Warna Status (Garis warna di kiri)
        // Mengambil method getStatusColor() dari Model Anda
        holder.viewStatusColor.setBackgroundColor(data.getStatusColor());

        // 2. Set Minggu Ke
        holder.tvMingguKe.setText("Minggu ke-" + data.getMingguKe());

        // 3. Set Detail (Gabungan Latihan Selesai & Total)
        // Karena di XML ID-nya 'tvDetail', kita gabungkan datanya di sini
        String detailText = data.getLatihanSelesai() + " dari " + data.getTotalLatihan() + " Latihan Selesai";
        holder.tvDetail.setText(detailText);

        // 4. Set Bagian Bawah (Di XML ID-nya tvTanggal)
        // Karena di Model tidak ada tanggal, kita pakai ini untuk menampilkan Persentase
        holder.tvTanggal.setText("Progress: " + data.getPersentase() + "%");
    }

    @Override
    public int getItemCount() {
        return (listData != null) ? listData.size() : 0;
    }

    // --- CLASS VIEWHOLDER ---
    // (Bagian ini yang menghubungkan ID XML dengan Java)
    public class ViewHolder extends RecyclerView.ViewHolder {

        // Deklarasi Variable sesuai komponen di XML Anda
        View viewStatusColor;
        TextView tvMingguKe, tvDetail, tvTanggal;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            // MENGHUBUNGKAN DENGAN ID XML (Copy-Paste dari XML yang Anda kirim)
            viewStatusColor = itemView.findViewById(R.id.viewStatusColor);
            tvMingguKe = itemView.findViewById(R.id.tvMingguKe);
            tvDetail = itemView.findViewById(R.id.tvDetail);
            tvTanggal = itemView.findViewById(R.id.tvTanggal);
        }
    }
}