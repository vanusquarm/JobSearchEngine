package com.julian.jobsearch.data.remote;

import com.julian.jobsearch.data.model.Employer;

import java.util.concurrent.Callable;

import javax.inject.Inject;
import javax.inject.Singleton;

import io.reactivex.Observable;
import io.reactivex.schedulers.Schedulers;
import retrofit2.Response;

/**
 *
 */

@Singleton
public class SessionManager {
    private Employer loggedInUser;
    private Api api;
    private String authenticationToken;

    @Inject
    public SessionManager(Api api) {
        this.api = api;
    }

    public Observable<Boolean> login(String username, String password) {
        return Observable.fromCallable(new Callable<Boolean>() {
            @Override
            public Boolean call() throws Exception {
                Response<Void> response = null;

                response = api.login(username, password).blockingSingle();
                if (response.isSuccessful()) {
                    authenticationToken = response.headers().get("Authorization");
                    loggedInUser = api.getUserProfile(authenticationToken).blockingSingle();
                } else {
                    throw new RuntimeException("Invalid username or password");
                }

                return response.isSuccessful();
            }
        }).subscribeOn(Schedulers.io());
    }

    public Employer getLoggedInUser() {
        return loggedInUser;
    }

    public String getAuthenticationToken() {
        return authenticationToken;
    }
}
