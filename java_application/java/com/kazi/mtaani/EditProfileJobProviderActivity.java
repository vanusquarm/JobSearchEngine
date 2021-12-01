package com.kazi.mtaani;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;
import androidx.core.widget.NestedScrollView;

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
import com.nguyenhoanglam.imagepicker.model.Config;
import com.nguyenhoanglam.imagepicker.model.Image;
import com.nguyenhoanglam.imagepicker.ui.imagepicker.ImagePicker;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.List;

import cz.msebera.android.httpclient.Header;
import de.hdodenhof.circleimageview.CircleImageView;
import io.blackbox_vision.datetimepickeredittext.view.DatePickerEditText;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;


public class EditProfileJobProviderActivity extends AppCompatActivity implements Validator.ValidationListener {

    @NotEmpty
    EditText edtFullName;
    @NotEmpty
    @Email
    EditText edtEmail;
    EditText edtPassword;
    @NotEmpty
    @Length(max = 14, min = 6, message = "Enter valid Phone Number")
    EditText edtMobile;
    @NotEmpty
    EditText edtCity;
    @NotEmpty
    EditText edtAddress;
    @NotEmpty
    DatePickerEditText edtDate;
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

    private Validator validator;

    MyApplication MyApp;

    String strFullName, strEmail, strPassword, strMobi, strCity, strAddress, strUserImage, strMessage;

    Toolbar toolbar;
    NestedScrollView mScrollView;
    ProgressBar mProgressBar;
    private LinearLayout lyt_not_found, lytIndividual, lytCompany;

    TextView txtName, textUploadLogo;
    ImageView imgChoose;

    private ArrayList<Image> featuredImages = new ArrayList<>();
    private ArrayList<Image> companyLogo = new ArrayList<>();
    boolean isFeatured = false, isCompanyLogo = false, isUserImageOrLogo = false;
    ImageView imgCompanyLogo;
    ProgressDialog pDialog;
    CircleImageView imageUser;
    Button btnSubmit;
    RadioButton rdMale, rdFemale;
    boolean isIndividual = false;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_profile_job_provider);
        IsRTL.ifSupported(this);
        toolbar = findViewById(R.id.toolbar);
        toolbar.setTitle(getString(R.string.menu_edit_profile));
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }

        MyApp = MyApplication.getInstance();
        lyt_not_found = findViewById(R.id.lyt_not_found);
        edtFullName = findViewById(R.id.edt_name);
        edtEmail = findViewById(R.id.edt_email);
        edtPassword = findViewById(R.id.edt_password);
        edtMobile = findViewById(R.id.edt_phone);
        edtCity = findViewById(R.id.edt_city);
        edtAddress = findViewById(R.id.edt_address);
        mScrollView = findViewById(R.id.nestedScrollView);
        mProgressBar = findViewById(R.id.progressBar1);
        pDialog = new ProgressDialog(this);
        btnSubmit = findViewById(R.id.button_save);
        rdMale = findViewById(R.id.rdMale);
        rdFemale = findViewById(R.id.rdFeMale);
        textUploadLogo = findViewById(R.id.btnChooseFeatured);
        imgCompanyLogo = findViewById(R.id.imageFeatured);

        txtName = findViewById(R.id.textUserName);
        imgChoose = findViewById(R.id.imageUpload);
        imageUser = findViewById(R.id.image_profile);
        lytIndividual = findViewById(R.id.lytIndividual);
        lytCompany = findViewById(R.id.lytCompany);

        edtDate = findViewById(R.id.edt_date);
        edtDate.setManager(getSupportFragmentManager());

        edtCompanyName = findViewById(R.id.edt_company_name);
        edtCompanyEmail = findViewById(R.id.edt_company_email);
        edtCompanyPhone = findViewById(R.id.edt_company_mobile);
        edtCompanyAddress = findViewById(R.id.edt_company_address);
        edtCompanyDesc = findViewById(R.id.edt_company_desc);
        edtCompanyWorkDay = findViewById(R.id.edt_company_work_day);
        edtCompanyWorkTime = findViewById(R.id.edt_company_work_time);
        edtCompanyWebsite = findViewById(R.id.edt_company_website);


        if (NetworkUtils.isConnected(this)) {
            getUserProfile();
        } else {
            showToast(getString(R.string.conne_msg1));
            mScrollView.setVisibility(View.GONE);
            lyt_not_found.setVisibility(View.VISIBLE);
        }

        imgChoose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                chooseFeaturedImage(true);
            }
        });

        textUploadLogo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                chooseFeaturedImage(false);
            }
        });

        btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                validator.validate();
            }
        });

        validator = new Validator(this);
        validator.setValidationListener(this);
    }

    @Override
    public void onValidationSucceeded() {
        if (NetworkUtils.isConnected(EditProfileJobProviderActivity.this)) {
            strPassword = edtPassword.getText().toString();
            if (strPassword.length() >= 1 && strPassword.length() <= 5) {
                edtPassword.setError("Invalid Password");
            } else {
                editUserProfile();
            }
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

    private void getUserProfile() {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "user_profile");
        jsObj.addProperty("id", MyApp.getUserId());
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                mProgressBar.setVisibility(View.VISIBLE);
                mScrollView.setVisibility(View.GONE);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                mProgressBar.setVisibility(View.GONE);
                mScrollView.setVisibility(View.VISIBLE);
                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        edtFullName.setText(objJson.getString(Constant.USER_NAME));
                        edtEmail.setText(objJson.getString(Constant.USER_EMAIL));
                        edtMobile.setText(objJson.getString(Constant.USER_PHONE));

                        txtName.setText(objJson.getString(Constant.USER_NAME));

                        if (!objJson.getString(Constant.USER_CITY).isEmpty()) {
                            edtCity.setText(objJson.getString(Constant.USER_CITY));
                        }
                        if (!objJson.getString(Constant.USER_ADDRESS).isEmpty()) {
                            edtAddress.setText(objJson.getString(Constant.USER_ADDRESS));
                        }
                        if (!objJson.getString(Constant.USER_IMAGE).isEmpty()) {
                            Picasso.get().load(objJson.getString(Constant.USER_IMAGE)).into(imageUser);
                        }

                        isIndividual = objJson.getString("register_as").equals("individual");
                        if (isIndividual) {
                            if (!objJson.getString(Constant.USER_DOB).isEmpty()) {
                                edtDate.setText(objJson.getString(Constant.USER_DOB));
                            }

                            if (!objJson.getString(Constant.USER_GENDER).isEmpty()) {
                                if (objJson.getString(Constant.USER_GENDER).equals(Constant.MALE)) {
                                    rdMale.setChecked(true);
                                } else {
                                    rdFemale.setChecked(true);
                                }
                            }
                            lytCompany.setVisibility(View.GONE);
                        } else {
                            lytIndividual.setVisibility(View.GONE);
                        }

                        edtCompanyName.setText(objJson.getString("company_name"));
                        edtCompanyEmail.setText(objJson.getString("company_email"));
                        edtCompanyPhone.setText(objJson.getString("mobile_no"));
                        edtCompanyAddress.setText(objJson.getString("company_address"));
                        edtCompanyDesc.setText(objJson.getString("company_desc"));
                        edtCompanyWorkDay.setText(objJson.getString("company_work_day"));
                        edtCompanyWorkTime.setText(objJson.getString("company_work_time"));
                        edtCompanyWebsite.setText(objJson.getString("company_website"));

                        if (!objJson.getString("company_logo").isEmpty()) {
                            Picasso.get().load(objJson.getString("company_logo")).into(imgCompanyLogo);
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    mProgressBar.setVisibility(View.GONE);
                    mScrollView.setVisibility(View.GONE);
                    lyt_not_found.setVisibility(View.VISIBLE);
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                mProgressBar.setVisibility(View.GONE);
                mScrollView.setVisibility(View.GONE);
                lyt_not_found.setVisibility(View.VISIBLE);
            }

        });
    }

    public void showToast(String msg) {
        Toast.makeText(EditProfileJobProviderActivity.this, msg, Toast.LENGTH_SHORT).show();
    }


    public void setResult() {

        if (Constant.GET_SUCCESS_MSG == 0) {
            showToast(strMessage);
        } else {
            showToast(strMessage);
            MyApp.saveLogin(MyApp.getUserId(), strFullName, strEmail);
            MyApp.saveUserImage(strUserImage);
            ActivityCompat.finishAffinity(EditProfileJobProviderActivity.this);
            Intent intent = new Intent(getApplicationContext(), MyApp.getIsJobProvider() ? JobProviderMainActivity.class : MainActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        }
    }

    public void chooseFeaturedImage(boolean isUserImage) {
        isUserImageOrLogo = isUserImage;
        ImagePicker.with(this)
                .setFolderMode(true)
                .setFolderTitle("Folder")
                .setImageTitle("Tap to select")
                .setMaxSize(1)
                .setCameraOnly(false)
                .setShowCamera(false)
                .start();
    }


    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == Config.RC_PICK_IMAGES) {
            if (resultCode == Activity.RESULT_OK && data != null) {
                if (isUserImageOrLogo) {
                    featuredImages = data.getParcelableArrayListExtra(Config.EXTRA_IMAGES);
                    Uri uri = Uri.fromFile(new File(featuredImages.get(0).getPath()));
                    Picasso.get().load(uri).into(imageUser);
                    isFeatured = true;
                } else {
                    companyLogo = data.getParcelableArrayListExtra(Config.EXTRA_IMAGES);
                    Uri uri = Uri.fromFile(new File(companyLogo.get(0).getPath()));
                    Picasso.get().load(uri).into(imgCompanyLogo);
                    isCompanyLogo = true;
                }
            }
        }
    }

    public void showProgressDialog() {
        pDialog.setMessage(getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(true);
        pDialog.show();
    }

    public void dismissProgressDialog() {
        if (pDialog != null && pDialog.isShowing())
            pDialog.dismiss();
    }

    public void editUserProfile() {
        strFullName = edtFullName.getText().toString();
        strEmail = edtEmail.getText().toString();
        strPassword = edtPassword.getText().toString();
        strMobi = edtMobile.getText().toString();
        strCity = edtCity.getText().toString();
        strAddress = edtAddress.getText().toString();

        String dob = edtDate.getText().toString();
        String companyName = edtCompanyName.getText().toString();
        String companyEmail = edtCompanyEmail.getText().toString();
        String companyPhone = edtCompanyPhone.getText().toString();
        String companyAddress = edtCompanyAddress.getText().toString();
        String companyDesc = edtCompanyDesc.getText().toString();
        String companyWorkDay = edtCompanyWorkDay.getText().toString();
        String companyWorkTime = edtCompanyWorkTime.getText().toString();
        String companyWebsite = edtCompanyWebsite.getText().toString();

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "user_profile_update");
        jsObj.addProperty("name", strFullName);
        jsObj.addProperty("email", strEmail);
        jsObj.addProperty("password", strPassword);
        jsObj.addProperty("phone", strMobi);
        jsObj.addProperty("user_id", MyApp.getUserId());
        jsObj.addProperty("city", strCity);
        jsObj.addProperty("address", strAddress);
        jsObj.addProperty("current_company_name", "");
        jsObj.addProperty("experiences", "");
        jsObj.addProperty("skills", "");

        if (isIndividual) {
            jsObj.addProperty("register_as", Constant.INDIVIDUAL);
            jsObj.addProperty("date_of_birth", dob);
            jsObj.addProperty("gender", rdMale.isChecked() ? Constant.MALE : Constant.FEMALE);
        } else {
            jsObj.addProperty("register_as", Constant.COMPANY);
            jsObj.addProperty("date_of_birth", "");
            jsObj.addProperty("gender", "");
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
        if (isFeatured) {
            try {
                params.put("user_image", new File(featuredImages.get(0).getPath()));
            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
        }

        if (isCompanyLogo) {
            try {
                params.put("company_logo", new File(companyLogo.get(0).getPath()));
            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
        }

        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {

            @Override
            public void onStart() {
                super.onStart();
                showProgressDialog();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                Log.e("Response", new String(responseBody));
                dismissProgressDialog();
                String result = new String(responseBody);
                try {
                    JSONObject jsonObject = new JSONObject(result);
                    JSONArray jsonArray = jsonObject.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson = jsonArray.getJSONObject(0);
                    strMessage = objJson.getString(Constant.MSG);
                    Constant.GET_SUCCESS_MSG = objJson.getInt(Constant.SUCCESS);
                    strUserImage = objJson.getString(Constant.USER_IMAGE);
                    setResult();
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                dismissProgressDialog();
            }

        });
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}
