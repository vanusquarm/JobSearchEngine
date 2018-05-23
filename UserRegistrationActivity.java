package com.julian.jobsearch.ui.auth.registration;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.User;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.data.repository.UserRepository;

import javax.inject.Inject;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.Disposable;

public class UserRegistrationActivity extends AppCompatActivity {
    private static final String emailPattern = "[a-zA-Z0-9._-]+@[a-z]+\\.+[a-z]+";
    private static final String REQUIRED_MSG = "Required field";
    private static final String TOO_SHORT = "Password too short";
    private static final String INVALID = "Invalid input";
    private static final String EMAIL_INVALID = "Invalid Email Address";
    private static final String NON_MATCH_PASS = "Passwords don't match";

    @Inject
    UserRepository userRepository;
    @Inject
    SessionManager sessionManager;
    private CompositeDisposable compositeDisposable;
    private EditText firstname;
    private EditText surname;
    private EditText email;
    private EditText username2;
    private EditText password2;
    private EditText confirm_pass;
    private EditText contact;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_registration);

        compositeDisposable = new CompositeDisposable();
        ((JobSearchApp) getApplication()).getAppComponent().inject(this);

        firstname = findViewById(R.id.firstname2);
        surname = findViewById(R.id.surname2);
        email = findViewById(R.id.email2);
        username2 = findViewById(R.id.username);
        password2 = findViewById(R.id.passer);
        confirm_pass = findViewById(R.id.field);
        contact = findViewById(R.id.contact2);
        Button next = findViewById(R.id.next);

        next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //check all user entries
                if (checkEmpty(firstname) && checkEmpty(surname) && checkEmpty(email) && checkEmpty(username2) && checkEmpty(password2) && checkEmpty(confirm_pass) && checkEmpty(contact)) {
                    if (checkforInt(firstname) && checkforInt(surname) && isEmailValid(email) && checkPassLength(password2) && checkPassword_match(password2, confirm_pass)) {
                        finalcheck();
                    } else {
                        Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
                    }
                }
            }
        });
    }

    //check if textview is empty
    public boolean checkEmpty(EditText edittext) {
        String getinputText = edittext.getText().toString();
        if (TextUtils.isEmpty(getinputText)) {
            edittext.setError(REQUIRED_MSG);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        } else {
            return true;
        }
    }

    // check length of password
    public boolean checkPassLength(EditText edittext) {
        String getpass = edittext.getText().toString();
        if (getpass.length() <= 5) {
            edittext.setError(TOO_SHORT);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        } else {
            return true;
        }
    }

    //check for int in textview
    public boolean checkforInt(EditText edittext) {
        String getName = edittext.getText().toString();
        // if(TextUtils.isDigitsOnly(getName)){
        if (getName.matches(".*\\d+.*")) {
            edittext.setError(INVALID);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        } else {
            return true;
        }
    }

    public boolean isEmailValid(EditText edittext) {
        String email = edittext.getText().toString();
        if (email.matches(emailPattern)) {
            return true;
        } else {
            edittext.setError(EMAIL_INVALID);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        }
    }

    //check if passwords match
    public boolean checkPassword_match(EditText edittext1, EditText edittext2) {
        String first_pass = edittext1.getText().toString();
        String second_pass = edittext2.getText().toString();
        if (first_pass.equals(second_pass)) {
            return true;
        } else {
            edittext1.setError(NON_MATCH_PASS);
            edittext2.setError(NON_MATCH_PASS);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        }
    }

    //move to next activity after all checks
    public void finalcheck() {
        String username = username2.getText().toString();
        String password = password2.getText().toString();
        User user = new User(
                email.getText().toString(),
                firstname.getText().toString(),
                surname.getText().toString(),
                username,
                password,
                contact.getText().toString()
        );
        ProgressDialog progressDialog = ProgressDialog.show(this, "", "Registering new user...");
        Disposable disposable = userRepository.registerNewUser(user)
                .map(aBoolean -> sessionManager.login(username, password).blockingSingle())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(aBoolean -> {
                            progressDialog.dismiss();
                            Intent intent2 = new Intent(this, SuccessfulRegistrationActivity.class);
                            startActivity(intent2);
                            finish();
                        }, throwable -> {
                            progressDialog.dismiss();
                            new AlertDialog.Builder(this)
                                    .setTitle("Error")
                                    .setMessage("An error occured while registering user.")
                                    .show();
                        }
                );

        compositeDisposable.add(disposable);
    }

    @Override
    protected void onStop() {
        super.onStop();
        compositeDisposable.clear();
    }
}
