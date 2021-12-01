package com.example.adapter;

import android.content.Context;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.kazi.mtaani.R;

import java.util.ArrayList;

/**
 * Created by laxmi.
 */
public class SkillsAdapter extends RecyclerView.Adapter<SkillsAdapter.ItemRowHolder> {

    private ArrayList<String> dataList;
    private Context mContext;

    public SkillsAdapter(Context context, ArrayList<String> dataList) {
        this.dataList = dataList;
        this.mContext = context;
    }

    @NonNull
    @Override
    public ItemRowHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_skill_item, parent, false);
        return new ItemRowHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ItemRowHolder holder, final int position) {
        holder.text.setText(dataList.get(position));
    }

    @Override
    public int getItemCount() {
        return (null != dataList ? dataList.size() : 0);
    }

    public class ItemRowHolder extends RecyclerView.ViewHolder {
        public TextView text;

        ItemRowHolder(View itemView) {
            super(itemView);
            text = itemView.findViewById(R.id.text_job_skill);
        }
    }
}
