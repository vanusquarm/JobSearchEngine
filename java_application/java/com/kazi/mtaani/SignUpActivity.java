package com.kazi.mtaani;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
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
import com.mobsandgeeks.saripaar.annotation.Length;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;
import com.mobsandgeeks.saripaar.annotation.Password;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

import cz.msebera.android.httpclient.Header;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class SignUpActivity extends AppCompatActivity implements Validator.ValidationListener {

    @NotEmpty
    EditText edtFullName;
    @NotEmpty
    @Email
    EditText edtEmail;
    @NotEmpty
    @Password (message = "Invalid password min 6 character needed")
    EditText edtPassword;
    @Length(max = 14, min = 6, message = "Enter valid Phone Number")
    EditText edtMobile;

    @NotEmpty
    EditText edtCompanyName;
    @NotEmpty
    @Email
    EditText edtCompanyEmail;
    @Length(max = 14, min = 6, message = "Enter valid Phone Number")
    EditText edtCompanyPhone;
    @NotEmpty
    EditText edtCompanyAddress;
    @NotEmpty
    EditText edtCompanyDesc;
    @NotEmpty
    EditText edtCompanyWorkDay;
    @NotEmpty
    EditText edtCompanyWorkTime;
    @NotEmpty
    EditText edtCompanyWebsite;

    Button btnSignUp;
    String strFullname, strEmail, strPassword, strMobi, strMessage;
    private Validator validator;
    TextView txtLogin;
    RadioButton rbProvider, rbIndividual, rbCompany, rbSeeker;
    ProgressDialog pDialog;
    LinearLayout lytJobProvider, lytCompany;
    RadioGroup rgJobUserType, rgCompanyType;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_up);
        IsRTL.ifSupported(this);
        pDialog = new ProgressDialog(this);
        lytJobProvider = findViewById(R.id.lytProvider);
        lytCompany = findViewById(R.id.lytCompany);
        rgJobUserType = findViewById(R.id.rgJobUserType);
        rgCompanyType = findViewById(R.id.radioGrp);

        edtFullName = findViewById(R.id.edt_name);
        edtEmail = findViewById(R.id.edt_email);
        edtPassword = findViewById(R.id.edt_password);
        edtMobile = findViewById(R.id.edt_contact_no);

        edtCompanyName = findViewById(R.id.edt_company_name);
        edtCompanyEmail = findViewById(R.id.edt_company_email);
        edtCompanyPhone = findViewById(R.id.edt_company_mobile);
        edtCompanyAddress = findViewById(R.id.edt_company_address);
        edtCompanyDesc = findViewById(R.id.edt_company_desc);
        edtCompanyWorkDay = findViewById(R.id.edt_company_work_day);
        edtCompanyWorkTime = findViewById(R.id.edt_company_work_time);
        edtCompanyWebsite = findViewById(R.id.edt_company_website);

        btnSignUp = findViewById(R.id.button_sign_up);
        txtLogin = findViewById(R.id.text_sign_in);

        rbProvider = findViewById(R.id.rbJobProvider);
        rbSeeker = findViewById(R.id.rbJobSeeker);
        rbIndividual = findViewById(R.id.rdIndividual);
        rbCompany = findViewById(R.id.rdCompany);

        btnSignUp.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                validator.validate();
            }
        });

        txtLogin.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                Intent intent = new Intent(getApplicationContext(), SignInActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });

        rgJobUserType.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup radioGroup, int i) {
                if (i != -1) {
                    switch (i) {
                        case R.id.rbJobSeeker:
                            lytJobProvider.setVisibility(View.GONE);
                            break;
                        case R.id.rbJobProvider:
                            lytJobProvider.setVisibility(View.VISIBLE);
                            break;
                    }
                }
            }
        });

        rgCompanyType.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup radioGroup, int i) {
                if (i != -1) {
                    switch (i) {
                        case R.id.rdIndividual:
                            lytCompany.setVisibility(View.GONE);
                            break;
                        case R.id.rdCompany:
                            lytCompany.setVisibility(View.VISIBLE);
                            break;
                    }
                }
            }
        });


        validator = new Validator(this);
        validator.setValidationListener(this);
    }

    @Override
    public void onValidationSucceeded() {
        if (NetworkUtils.isConnected(SignUpActivity.this)) {
            putSignUp();
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

    public void putSignUp() {
        strFullname = edtFullName.getText().toString();
        strEmail = edtEmail.getText().toString();
        strPassword = edtPassword.getText().toString();
        strMobi = edtMobile.getText().toString();

        String companyName = edtCompanyName.getText().toString();
        String companyEmail = edtCompanyEmail.getText().toString();
        String companyPhone = edtCompanyPhone.getText().toString();
        String companyAddress = edtCompanyAddress.getText().toString();
        String companyDesc = edtCompanyDesc.getText().toString();
        String companyWorkDay = edtCompanyWorkDay.getText().toString();
        String companyWorkTime = edtCompanyWorkTime.getText().toString();
        String companyWebsite = edtCompanyWebsite.getText().toString();

        String strType;
        if (rbProvider.isChecked()) {
            strType = "2";
        } else {
            strType = "1";
        }

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "user_register");
        jsObj.addProperty("name", strFullname);
        jsObj.addProperty("email", strEmail);
        jsObj.addProperty("password", strPassword);
        jsObj.addProperty("phone", strMobi);
        jsObj.addProperty("user_type", strType);
        if (strType.equals("1")) {
            jsObj.addProperty("register_as", Constant.INDIVIDUAL);
        } else {
            jsObj.addProperty("register_as", rbIndividual.isChecked() ? Constant.INDIVIDUAL : Constant.COMPANY);
        }
        jsObj.addProperty("company_name", companyName);
        jsObj.addProperty("company_email", companyEmail);
        jsObj.addProperty("mobile_no", companyPhone);
        jsObj.addProperty("company_address", companyAddress);
        jsObj.addProperty("company_desc", companyDesc);
        jsObj.addProperty("company_work_day", companyWorkDay);
        jsObj.addProperty("company_work_time", companyWorkTime);
        jsObj.addProperty("company_website", companyWebsite);

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
                        strMessage = objJson.getString(Constant.MSG);
                        Constant.GET_SUCCESS_MSG = objJson.getInt(Constant.SUCCESS);
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
            edtEmail.setText("");
            edtEmail.requestFocus();
            showToast(strMessage);
        } else {
            showToast(strMessage);
            Intent intent = new Intent(getApplicationContext(), SignInActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        }
    }

    public void showToast(String msg) {
        Toast.makeText(SignUpActivity.this, msg, Toast.LENGTH_LONG).show();
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
