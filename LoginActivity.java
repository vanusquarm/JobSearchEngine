package com.julian.jobsearch.ui.auth;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.ui.home.HomeActivity;

import javax.inject.Inject;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.Disposable;

public class LoginActivity extends AppCompatActivity {
    Intent intent = getIntent();
    @Inject
    SessionManager sessionManager;
    private EditText username;
    private EditText password;
    private Button login;
    private int counter = 100;
    private CompositeDisposable compositeDisposable;
    private EditText password1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        compositeDisposable = new CompositeDisposable();

        ((JobSearchApp) getApplication()).getAppComponent().inject(this);

        final EditText username = findViewById(R.id.username);
        password1 = findViewById(R.id.password);
        Button login = findViewById(R.id.login2);
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                validate(username.getText().toString(), password1.getText().toString());
            }
        });
    }

    private void validate(String username, String password) {
//        if ((username.equals("Admin")) && (password.equals("water"))) {
////            Intent intent = new Intent(this, Homepage.class);
////            startActivity(intent);
//        } else {
//
//        }
        Disposable disposable = sessionManager.login(username, password)
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(aBoolean -> {
                    password1.getText().clear();
                    Intent intent = new Intent(this, HomeActivity.class);
                    startActivity(intent);
                }, throwable -> {
                    Snackbar.make(password1, "Invalid username or password", Snackbar.LENGTH_LONG).show();
                });

        compositeDisposable.add(disposable);
    }

    @Override
    protected void onStop() {
        super.onStop();
        compositeDisposable.clear();
    }
}

