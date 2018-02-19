package com.example.julian.jobsearch;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class LoginActivity extends AppCompatActivity {
    private EditText username;
    private EditText password;
    private Button login;
    private int counter = 100;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        final EditText username = (EditText)findViewById(R.id.username);
        final EditText password = (EditText)findViewById(R.id.password);
        Button login = (Button)findViewById(R.id.login2);
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                validate(username.getText().toString(), password.getText().toString());
            }
        });
    }

    Intent intent = getIntent();

    private void validate(String username, String password) {
        if ((username.equals("Admin")) && (password.equals("water"))) {
            Intent intent = new Intent(this, Homepage.class);
            startActivity(intent);
        } else {
            counter--;
            if (counter == 0) {
                login.setEnabled(false);
            }
        }
    }
}
