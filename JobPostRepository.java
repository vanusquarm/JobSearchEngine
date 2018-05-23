package com.julian.jobsearch.data.repository;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.julian.jobsearch.data.model.JobPost;
import com.julian.jobsearch.data.remote.Api;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.data.remote.response.Error;
import com.julian.jobsearch.data.remote.response.PageResponse;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.concurrent.Callable;

import javax.inject.Inject;

import io.reactivex.Observable;
import io.reactivex.schedulers.Schedulers;
import retrofit2.Response;

/**
 *
 */

public class JobPostRepository {

    private Api api;
    private SessionManager sessionManager;
    private Gson gson;

    @Inject
    public JobPostRepository(Api api, SessionManager sessionManager, Gson gson) {
        this.api = api;
        this.sessionManager = sessionManager;
        this.gson = gson;
    }

    public Observable<List<JobPost>> getJobPosts(int page) {
        return Observable.fromCallable(new Callable<List<JobPost>>() {
            @Override
            public List<JobPost> call() throws Exception {
                PageResponse<List<JobPost>> pageResponse = api.getJobPosts(page).blockingSingle();
                return pageResponse.getContent();
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<List<JobPost>> getMyJobPosts(int page) {
        return Observable.fromCallable(new Callable<List<JobPost>>() {
            @Override
            public List<JobPost> call() throws Exception {
                PageResponse<List<JobPost>> pageResponse = api.getMyJobPosts(page, sessionManager.getAuthenticationToken()).blockingSingle();
                return pageResponse.getContent();
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<Boolean> postJob(JobPost jobPost) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                Response<Void> response = api.postJob(jobPost, sessionManager.getAuthenticationToken()).blockingSingle();
                if (response.isSuccessful())
                    return true;
                else {
                    List<Error> errors = gson.fromJson(response.errorBody().string(), TypeToken.getParameterized(ArrayList.class, Error.class).getType());
                    throw new Api.Exception(errors);
                }
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<Boolean> deleteJobPost(JobPost jobPost) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                Response<Void> response = api.deleteJobPost(jobPost.getUuid(), sessionManager.getAuthenticationToken()).blockingSingle();
                if (response.isSuccessful())
                    return true;
                else
                    throw new RuntimeException();
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<List<JobPost>> searchJobPosts(String keyword) {
        return Observable.fromCallable(new Callable<List<JobPost>>() {
            @Override
            public List<JobPost> call() throws Exception {
                List<JobPost> jobPosts = getJobPosts(0).blockingSingle();
                if (jobPosts != null) {
                    List<JobPost> filteredPosts = new ArrayList<>(jobPosts.size());
                    for (int i = 0; i < jobPosts.size(); i++) {
                        try {
                            JobPost jobPost = jobPosts.get(i);
                            if (jobPost.getTitle().toLowerCase().contains(keyword.toLowerCase()))
                                filteredPosts.add(jobPost);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                    return filteredPosts;
                } else
                    return Collections.emptyList();
            }
        }).subscribeOn(Schedulers.io());
    }
}
