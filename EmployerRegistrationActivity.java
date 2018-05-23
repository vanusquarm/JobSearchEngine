package com.julian.jobsearch.ui.auth.registration;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.Employer;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.data.repository.UserRepository;

import javax.inject.Inject;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.Disposable;

public class EmployerRegistrationActivity extends AppCompatActivity {

    //    Strings defined
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

    String[] compType = {"Accounting/Audit", "Agriculture", "Architecture/Building", "Banking", "Consulting", "Creatives(Art,Design,etc)",
            "Educational", "Engineering", "Healthcare", "Information Technology", "Legal/Law", "Manufacturing/Production", "Marketing", "NGO",
            "Oil&Gas/Mining", "Other", "Real Estate", "Sales", "Telecommunications", "Vocational"};
    private CompositeDisposable compositeDisposable;
    private AutoCompleteTextView acTextView;
    private EditText emp_comp_name;
    private EditText email;
    private EditText username;
    private EditText pass;
    private EditText contact;
    private EditText conf_pass;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_employer_registration);

        compositeDisposable = new CompositeDisposable();
        ((JobSearchApp) getApplication()).getAppComponent().inject(this);

        ArrayAdapter<String> adapter2 = new ArrayAdapter<String>(this, android.R.layout.select_dialog_singlechoice, compType);

        acTextView = findViewById(R.id.comp_type);
        acTextView.setThreshold(1);
        acTextView.setAdapter(adapter2);


        emp_comp_name = findViewById(R.id.emp_comp_name);
        email = findViewById(R.id.email2);
        username = findViewById(R.id.username3);
        pass = findViewById(R.id.contact2);
        contact = findViewById(R.id.field);
        conf_pass = findViewById(R.id.conf_pass);
        Button next2 = findViewById(R.id.next2);

        next2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //all checks on each entry by user

                if (checkEmpty(emp_comp_name) && checkEmptyauto(acTextView) && checkEmpty(email) && checkEmpty(pass) && checkEmpty(conf_pass) && checkEmpty(contact)) {
                    if (checkforIntauto(acTextView) && isEmailValid(email) && checkPassLength(pass) && checkPassword_match(pass, conf_pass)) {
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

    //check if autocompleteview is empty
    public boolean checkEmptyauto(AutoCompleteTextView auto) {
        String getinputText = auto.getText().toString();
        if (TextUtils.isEmpty(getinputText)) {
            auto.setError(REQUIRED_MSG);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        } else {
            return true;
        }
    }

    //check password length
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

    //check for integer in textview
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

    //check for int in autocomplete view
    public boolean checkforIntauto(AutoCompleteTextView auto) {
        String getName = auto.getText().toString();
        if (getName.matches(".*\\d+.*")) {
            auto.setError(INVALID);
            Toast.makeText(getApplicationContext(), "Please check and correct your entries", Toast.LENGTH_SHORT).show();
            return false;
        } else {
            return true;
        }
    }

    //check for valid email
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

    //move to next page after all checks
    public void finalcheck() {
        String companyName = emp_comp_name.getText().toString();
        String username = this.username.getText().toString();
        String password = pass.getText().toString();
        Employer employer = new Employer(email.getText().toString(),
                companyName,
                "-",
                username,
                password,
                contact.getText().toString(),
                companyName,
                acTextView.getText().toString());

        ProgressDialog progressDialog = ProgressDialog.show(this, "", "Registering new employer...");
        Disposable disposable = userRepository.registerNewEmployer(employer)
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
                                    .setMessage("An error occured while registering employer.")
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

