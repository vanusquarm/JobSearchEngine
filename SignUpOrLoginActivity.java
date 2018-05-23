package com.julian.jobsearch.ui.auth;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

import com.julian.jobsearch.R;
import com.julian.jobsearch.ui.auth.registration.UserTypeSelectionActivity;


public class SignUpOrLoginActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_up_or_login);
    }

    public void sendLoginChoice(View view) {
        Intent intent1 = new Intent(this, LoginActivity.class);
        startActivity(intent1);
    }

    public void sendSignupChoice(View view) {
        Intent intent2 = new Intent(this, UserTypeSelectionActivity.class);
        startActivity(intent2);
    }
}
