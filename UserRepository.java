package com.julian.jobsearch.data.repository;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.julian.jobsearch.data.model.Employer;
import com.julian.jobsearch.data.model.User;
import com.julian.jobsearch.data.remote.Api;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.data.remote.response.Error;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.Callable;

import javax.inject.Inject;

import io.reactivex.Observable;
import io.reactivex.schedulers.Schedulers;
import retrofit2.Response;

/**
 *
 */

public class UserRepository {
    private Api api;
    private SessionManager sessionManager;
    private Gson gson;

    @Inject
    public UserRepository(Api api, SessionManager sessionManager, Gson gson) {
        this.api = api;
        this.sessionManager = sessionManager;
        this.gson = gson;
    }

    public Observable<Boolean> registerNewUser(User user) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                Response<Void> response = api.registerUser(user).blockingSingle();
                if (response.isSuccessful())
                    return true;
                else {
                    List<Error> errors = gson.fromJson(response.errorBody().string(), TypeToken.getParameterized(ArrayList.class, Error.class).getType());
                    throw new Api.Exception(errors);
                }
            }
        }).subscribeOn(Schedulers.io());
    }


    public Observable<Boolean> registerNewEmployer(Employer employer) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                Response<Void> response = api.registerEmployer(employer).blockingSingle();
                if (response.isSuccessful())
                    return true;
                else {
                    List<Error> errors = gson.fromJson(response.errorBody().string(), TypeToken.getParameterized(ArrayList.class, Error.class).getType());
                    throw new Api.Exception(errors);
                }
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<Boolean> updateUserProfile(User user) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                return null;
            }
        }).subscribeOn(Schedulers.io());
    }

    public Observable<Employer> getUserProfile() {
        return Observable.fromCallable(new Callable<Employer>() {
            @Override
            public Employer call() throws Exception {
                return api.getUserProfile(sessionManager.getAuthenticationToken()).blockingSingle();
            }
        }).subscribeOn(Schedulers.io());
    }
}
