package com.example.julian.jobsearch;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.util.regex.Pattern;

public class SignupActivity extends AppCompatActivity {
    private EditText firstname;
    private EditText surname;
    private EditText email, username;
    private EditText password;
    private Button next;
    private static final String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
    private static final String REQUIRED_MSG = "Required field";
    private static final String TOO_SHORT = "Password too short";
    private static final String INVALID = "Invalid input";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);

        final EditText firstname =  (EditText)findViewById(R.id.firstname);
        final EditText surname = (EditText)findViewById(R.id.surname);
        final EditText email = (EditText)findViewById(R.id.email);
        final EditText username = (EditText)findViewById(R.id.username);
        final EditText password = (EditText)findViewById(R.id.password);

        next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                checkEmpty(firstname);
                checkEmpty(surname);
                checkEmpty(email);
                checkEmpty(username);
                checkEmpty(password);
                checkPassLength(password);
                checkforInt(firstname);
                checkforInt(surname);
                if(checkEmpty(firstname)&&checkEmpty(surname)&&checkEmpty(email)&&checkEmpty(username)&&checkEmpty(password)&&checkPassLength(password)
                        &&checkforInt(firstname)&&checkforInt(surname)&&isEmailValid(email)){
                    Intent intent3 = new Intent(SignupActivity.this, Homepage.class);
                    startActivity(intent3);
                }
            }
        });

    }

    Intent intent = getIntent();

    public boolean checkEmpty(EditText edittext){
        String getinputText = edittext.getText().toString();
        if(TextUtils.isEmpty(getinputText)){
            Toast.makeText(this, "Required", Toast.LENGTH_LONG).show();
            return false;
        }else{return true;}
    }

    public boolean checkPassLength(EditText edittext){
        String getpass = edittext.getText().toString();
        if(getpass.length() <=5){
            edittext.setError(TOO_SHORT);
            return false;
        }else{return true;}
    }

    public boolean checkforInt(EditText edittext){
        String getName = edittext.getText().toString();
        if(TextUtils.isDigitsOnly(getName)){
            edittext.setError(INVALID);
            return false;
        }else{return true;}
    }

    public boolean isEmailValid(EditText edittext){
        String email = edittext.getText().toString();
        if (email.matches(emailPattern))
        {
            return true;
        }
        else
        {
            Toast.makeText(getApplicationContext(),"Invalid email address", Toast.LENGTH_SHORT).show();
            return false;
        }
    }
}
