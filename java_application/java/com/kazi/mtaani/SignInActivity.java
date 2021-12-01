package com.kazi.mtaani;


import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.util.API;
import com.example.util.Constant;
import com.example.util.IsRTL;
import com.example.util.NetworkUtils;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;
import com.mobsandgeeks.saripaar.annotation.Email;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;
import com.mobsandgeeks.saripaar.annotation.Password;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

import cz.msebera.android.httpclient.Header;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class SignInActivity extends AppCompatActivity implements Validator.ValidationListener {

    String strEmail, strPassword, strMessage, strName, strUserId, strIsProvider, strUserImage;
    @NotEmpty
    @Email
    EditText edtEmail;

    @NotEmpty
    @Password (message = "Invalid password min 6 character needed")
    EditText edtPassword;
    private Validator validator;
    Button btnSingIn, btnSkip;
    MyApplication MyApp;
    TextView textForgot, textSignUp;
    ProgressDialog pDialog;
    boolean isFromOtherScreen = false;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);
        IsRTL.ifSupported(this);
        Intent intent = getIntent();
        if (intent.hasExtra("isOtherScreen")) {
            isFromOtherScreen = true;
        }


        pDialog = new ProgressDialog(this);
        MyApp = MyApplication.getInstance();
        edtEmail = findViewById(R.id.edt_email);
        edtPassword = findViewById(R.id.edt_password);
        btnSingIn = findViewById(R.id.button_log_in);
        btnSkip = findViewById(R.id.button_skip);

        textForgot = findViewById(R.id.text_forgot);
        textSignUp = findViewById(R.id.text_sign_up);

        btnSingIn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                validator.validate();
            }
        });

        MyApp.saveIsFirstTime(true);

        textSignUp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(SignInActivity.this, SignUpActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });

        btnSkip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(SignInActivity.this, MainActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
            }
        });

        textForgot.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(SignInActivity.this, ForgotPasswordActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });

        validator = new Validator(this);
        validator.setValidationListener(this);
    }

    @Override
    public void onValidationSucceeded() {
        if (NetworkUtils.isConnected(SignInActivity.this)) {
            putSignIn();
        } else {
            showToast(getString(R.string.conne_msg1));
        }
    }

    @Override
    public void onValidationFailed(List<ValidationError> errors) {
        for (ValidationError error : errors) {
            View view = error.getView();
            String message = error.getCollatedErrorMessage(this);
            if (view instanceof EditText) {
                ((EditText) view).setError(message);
            } else {
                Toast.makeText(this, message, Toast.LENGTH_LONG).show();
            }
        }
    }

    public void putSignIn() {
        strEmail = edtEmail.getText().toString();
        strPassword = edtPassword.getText().toString();

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "user_login");
        jsObj.addProperty("email", strEmail);
        jsObj.addProperty("password", strPassword);
        params.put("data", API.toBase64(jsObj.toString()));

        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {

            @Override
            public void onStart() {
                super.onStart();
                showProgressDialog();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                dismissProgressDialog();
                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        if (objJson.has(Constant.MSG)) {
                            strMessage = objJson.getString(Constant.MSG);
                            Constant.GET_SUCCESS_MSG = objJson.getInt(Constant.SUCCESS);
                        } else {
                            Constant.GET_SUCCESS_MSG = objJson.getInt(Constant.SUCCESS);
                            strName = objJson.getString(Constant.USER_NAME);
                            strUserId = objJson.getString(Constant.USER_ID);
                            strIsProvider = objJson.getString(Constant.USER_TYPE);
                            strUserImage = objJson.getString(Constant.USER_IMAGE);
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResult();
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                dismissProgressDialog();
            }

        });
    }

    public void setResult() {

        if (Constant.GET_SUCCESS_MSG == 0) {
            showToast("Opps. \n" + strMessage);

        } else {
            MyApp.saveIsLogin(true);
            MyApp.saveUserImage(strUserImage);
            MyApp.saveIsJobProvider(strIsProvider.equals("2"));
            MyApp.saveLogin(strUserId, strName, strEmail);

            if (MyApp.getIsJobProvider()) {
                Intent i = new Intent(SignInActivity.this, JobProviderMainActivity.class);
                startActivity(i);
                finish();
            } else {
                if (isFromOtherScreen) {
                    onBackPressed();
                } else {
                    Intent i = new Intent(SignInActivity.this, MainActivity.class);
                    startActivity(i);
                    finish();
                }
            }


        }
    }

    public void showToast(String msg) {
        Toast.makeText(SignInActivity.this, msg, Toast.LENGTH_LONG).show();
    }

    public void showProgressDialog() {
        pDialog.setMessage(getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(true);
        pDialog.show();
    }

    public void dismissProgressDialog() {
        pDialog.dismiss();
    }
}
