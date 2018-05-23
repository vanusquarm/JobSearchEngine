package com.julian.jobsearch.ui.home.home;

import android.content.DialogInterface;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.Snackbar;
import android.support.v4.app.Fragment;
import android.support.v7.app.AlertDialog;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.JobPost;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.data.repository.JobPostRepository;
import com.julian.jobsearch.ui.common.JobPostRecyclerAdapter;

import java.util.List;

import javax.inject.Inject;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.Disposable;
import io.reactivex.functions.Consumer;

/**
 *
 */
public class HomeFragment extends Fragment implements JobPostRecyclerAdapter.OnDeleteClickedListener {
    @Inject
    JobPostRepository jobPostRepository;
    @Inject
    SessionManager sessionManager;
    private CompositeDisposable compositeDisposable;
    private JobPostRecyclerAdapter adapter;
    private ProgressBar progressBar;

    public HomeFragment() {
    }

    /**
     * Returns a new instance of this fragment for the given section
     * number.
     */
    public static HomeFragment newInstance() {
        HomeFragment fragment = new HomeFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        ((JobSearchApp) getActivity().getApplication()).getAppComponent().inject(this);
    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        compositeDisposable = new CompositeDisposable();
        adapter = new JobPostRecyclerAdapter(sessionManager.getLoggedInUser().getUuid());
        adapter.setOnDeleteClickedListener(this);
        RecyclerView jobPostsRecyclerView = view.findViewById(R.id.job_posts_recycler_view);

        jobPostsRecyclerView.setHasFixedSize(true);
        jobPostsRecyclerView.setLayoutManager(new LinearLayoutManager(getContext()));
        jobPostsRecyclerView.setAdapter(adapter);
        progressBar = view.findViewById(R.id.progress_bar);
    }

    @Override
    public void onStart() {
        super.onStart();
        progressBar.setVisibility(View.VISIBLE);
        Disposable disposable = jobPostRepository.getJobPosts(0)
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(new Consumer<List<JobPost>>() {
                    @Override
                    public void accept(List<JobPost> jobPosts) throws Exception {
                        progressBar.setVisibility(View.GONE);
                        adapter.clear();
                        adapter.addAll(jobPosts);
                    }
                }, new Consumer<Throwable>() {
                    @Override
                    public void accept(Throwable throwable) throws Exception {
                        progressBar.setVisibility(View.GONE);
                        showError(throwable);
                    }
                });

        compositeDisposable.add(disposable);
    }

    private void showError(Throwable throwable) {
        Snackbar.make(getView(), "An error occured while getting job posts", Snackbar.LENGTH_SHORT).show();
    }

    @Override
    public void onStop() {
        super.onStop();
        compositeDisposable.clear();
    }

    @Override
    public void onDeleteClickedPost(JobPost jobPost) {
        DialogInterface.OnClickListener onClickListener = (dialog, button) -> {
            switch (button) {
                case DialogInterface.BUTTON_POSITIVE:
                    deleteJobPost(jobPost);
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    break;
            }
            dialog.dismiss();
        };
        new AlertDialog.Builder(getContext())
                .setTitle("Delete Post?")
                .setMessage("Are you sure you want to delete " + jobPost.getTitle() + " post?")
                .setPositiveButton(R.string.action_yes, onClickListener)
                .setNegativeButton(R.string.action_no, onClickListener)
                .show();
    }

    private void deleteJobPost(JobPost jobPost) {
        progressBar.setVisibility(View.VISIBLE);
        Disposable disposable = jobPostRepository.deleteJobPost(jobPost)
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(new Consumer<Boolean>() {
                    @Override
                    public void accept(Boolean aBoolean) throws Exception {
                        progressBar.setVisibility(View.GONE);
                        Snackbar.make(progressBar, String.format("%s deleted successfully", jobPost.getTitle()), Snackbar.LENGTH_LONG).show();
                        adapter.removeJobPost(jobPost);
                    }
                }, new Consumer<Throwable>() {
                    @Override
                    public void accept(Throwable throwable) throws Exception {
                        progressBar.setVisibility(View.GONE);
                        Snackbar.make(progressBar, "An error occured deleting your post", Snackbar.LENGTH_LONG).show();
                    }
                });
        compositeDisposable.add(disposable);
    }
}
