package com.julian.jobsearch.data.remote;

import com.julian.jobsearch.data.model.Employer;
import com.julian.jobsearch.data.model.JobPost;
import com.julian.jobsearch.data.model.User;
import com.julian.jobsearch.data.remote.response.Error;
import com.julian.jobsearch.data.remote.response.PageResponse;

import java.lang.annotation.Documented;
import java.lang.annotation.Retention;
import java.lang.annotation.RetentionPolicy;
import java.util.List;

import javax.inject.Qualifier;

import io.reactivex.Observable;
import retrofit2.Response;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

/**
 *
 */

public interface Api {
    @POST("employers/register")
    Observable<Response<Void>> registerEmployer(@Body Employer employer);

    @POST("users/register")
    Observable<Response<Void>> registerUser(@Body User user);

    @FormUrlEncoded
    @POST("users/login")
    Observable<Response<Void>> login(@Field("username") String username, @Field("password") String password);

    @GET("posts")
    Observable<PageResponse<List<JobPost>>> getJobPosts(@Query("page") int page);

    @GET("posts?mine=true")
    Observable<PageResponse<List<JobPost>>> getMyJobPosts(@Query("page") int page, @Header("Authorization") String authorizationToken);

    @POST("posts")
    Observable<Response<Void>> postJob(@Body JobPost jobPost, @Header("Authorization") String authorizationToken);

    @DELETE("posts/{uuid}")
    Observable<Response<Void>> deleteJobPost(@Path("uuid") String jobPostUuid, @Header("Authorization") String authorizationToken);

    @GET("users/me")
    Observable<Employer> getUserProfile(@Header("Authorization") String authorizationToken);

    /**
     *
     */

    @Qualifier
    @Documented
    @Retention(RetentionPolicy.RUNTIME)
    @interface BaseUrl {
    }

    public class Exception extends RuntimeException {
        private List<Error> errors;

        public Exception(List<Error> errors) {
            this.errors = errors;
        }

        public List<Error> getErrors() {
            return errors;
        }
    }
}
