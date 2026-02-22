package com.example.genzehat;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class HistoryResponse {
    // "history" harus sama persis dengan key JSON dari Laravel
    @SerializedName("history")
    private List<HistoryModel> historyList;

    public List<HistoryModel> getHistoryList() {
        return historyList;
    }
}