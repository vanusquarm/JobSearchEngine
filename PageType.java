package com.example.julian.jobsearch;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;

public class PageType extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_page_type);
    }

    Intent intent = getIntent();

    public void sendPageType(View view){
        Intent intent = new Intent(this, SignupActivity.class);
        startActivity(intent);
    }
}
