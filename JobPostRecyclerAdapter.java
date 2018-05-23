package com.julian.jobsearch.ui.common;

import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.text.SpannableString;
import android.text.Spanned;
import android.text.method.LinkMovementMethod;
import android.text.style.URLSpan;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.JobPost;

import java.util.List;

/**
 *
 */

public class JobPostRecyclerAdapter extends RecyclerView.Adapter<JobPostRecyclerAdapter.ViewHolder> {
    private List<JobPost> jobPosts;
    private LayoutInflater layoutInflater;
    private OnDeleteClickedListener onDeleteClickedListener;
    private String employerUuid;


    public JobPostRecyclerAdapter(String employerUuid) {
        this.employerUuid = employerUuid;
        if (this.employerUuid == null)
            this.employerUuid = "";
    }

    public void setOnDeleteClickedListener(OnDeleteClickedListener onDeleteClickedListener) {
        this.onDeleteClickedListener = onDeleteClickedListener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        if (layoutInflater == null)
            layoutInflater = LayoutInflater.from(parent.getContext());

        return new ViewHolder(layoutInflater.inflate(R.layout.recycler_item_job_post, parent, false));
    }

    public void addAll(List<JobPost> jobPosts) {
        if (this.jobPosts == null) {
            this.jobPosts = jobPosts;
            notifyDataSetChanged();
        } else {
            int start = this.jobPosts.size();
            this.jobPosts.addAll(jobPosts);
            notifyItemRangeInserted(start, jobPosts.size());
        }
    }

    public void clear() {
        if (jobPosts != null) {
            jobPosts.clear();
            notifyDataSetChanged();
        }
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        JobPost jobPost = jobPosts.get(position);
        boolean deletable = employerUuid.equals(jobPost.getEmployerUuid());
        holder.deleteImageView.setVisibility(deletable ? View.VISIBLE : View.GONE);
        holder.titleTextView.setText(jobPost.getTitle());
        holder.descriptionTextView.setText(jobPost.getDescription());
        holder.requirementsTextView.setText(jobPost.getEducationalRequirements());
        holder.hoursTextView.setText(jobPost.getHours());
        holder.closingDateTextView.setText(jobPost.getOfferClosingDate());
        SpannableString contact = new SpannableString(jobPost.getEmployerContact());
        contact.setSpan(new URLSpan("tel:" + jobPost.getEmployerContact()), 0, contact.length(), Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
        holder.employerContactTextView.setText(contact);
    }

    @Override
    public int getItemCount() {
        return jobPosts == null ? 0 : jobPosts.size();
    }

    private void notifyDeleteItemClicked(int position) {
        if (onDeleteClickedListener != null)
            onDeleteClickedListener.onDeleteClickedPost(jobPosts.get(position));
    }

    public void removeJobPost(JobPost jobPost) {
        int index = jobPosts.indexOf(jobPost);
        if (index >= 0) {
            jobPosts.remove(index);
            notifyItemRemoved(index);
        }
    }

    public interface OnDeleteClickedListener {
        void onDeleteClickedPost(JobPost jobPost);
    }

    public class ViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        private final TextView employerContactTextView;
        private final ImageView deleteImageView;
        private TextView titleTextView;
        private TextView descriptionTextView;
        private TextView requirementsTextView;
        private TextView hoursTextView;
        private TextView closingDateTextView;

        public ViewHolder(View view) {
            super(view);
            titleTextView = view.findViewById(R.id.title_text_view);
            descriptionTextView = view.findViewById(R.id.description_text_view);
            requirementsTextView = view.findViewById(R.id.requirements_text_view);
            hoursTextView = view.findViewById(R.id.hours_text_view);
            closingDateTextView = view.findViewById(R.id.closing_date_text_view);
            employerContactTextView = view.findViewById(R.id.employer_contact_text_view);
            employerContactTextView.setMovementMethod(LinkMovementMethod.getInstance());
            deleteImageView = view.findViewById(R.id.delete_image_view);
            deleteImageView.setOnClickListener(this);
        }

        @Override
        public void onClick(View v) {
            notifyDeleteItemClicked(getAdapterPosition());
        }
    }
}
