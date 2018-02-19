package com.example.julian.jobsearch;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;

import static android.provider.AlarmClock.EXTRA_MESSAGE;

public class MainActivity extends AppCompatActivity {
    public static final String EXTRA_MESSAGE = "com.example.myfirstapp.MESSAGE";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }

    public void sendLoginChoice(View view){
        Intent intent1 = new Intent(this, LoginActivity.class);
        startActivity(intent1);
    }

    public void sendSignupChoice(View view){
        Intent intent2 = new Intent(this, PageType.class);
        startActivity(intent2);
    }
}
