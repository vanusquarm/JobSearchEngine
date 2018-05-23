package com.julian.jobsearch.ui.auth.registration;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

import com.julian.jobsearch.R;

public class UserTypeSelectionActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_type_selection);
    }

    public void sendPageType(View view) {
        Intent intent = new Intent(this, UserRegistrationActivity.class);
        startActivity(intent);
    }

    public void sendPagetypeEmploy(View view) {
        Intent intent2 = new Intent(this, EmployerRegistrationActivity.class);
        startActivity(intent2);
    }
}
