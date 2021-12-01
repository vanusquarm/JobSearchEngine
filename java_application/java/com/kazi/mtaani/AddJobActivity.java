package com.kazi.mtaani;


import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.ScrollView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.app.ActivityCompat;

import com.example.item.ItemCategory;
import com.example.item.ItemCity;
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
import io.blackbox_vision.datetimepickeredittext.view.DatePickerEditText;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class AddJobActivity extends AppCompatActivity implements Validator.ValidationListener {

    @NotEmpty
    EditText edtTitle;
    @NotEmpty
    EditText edtDesignation;
    @NotEmpty
    EditText edtDescription;
    @NotEmpty
    EditText edtSalary;
    @NotEmpty
    EditText edtCompanyName;
    @NotEmpty
    EditText edtWebsite;
    @NotEmpty
    @Length(max = 14, min = 6, message = "Enter valid Phone Number")
    EditText edtPhone;
    @NotEmpty
    @Email
    EditText edtEmail;
    @NotEmpty
    EditText edtVacancy;
    @NotEmpty
    EditText edtAddress;
    @NotEmpty
    EditText edtQualification;
    @NotEmpty
    EditText edtSkills;
    @NotEmpty
    DatePickerEditText edtDate;
    @NotEmpty
    EditText edtJobWorkDay;
    @NotEmpty
    EditText edtJobWorkTime;
    @NotEmpty
    EditText edtJobExperience;

    private Validator validator;
    private ArrayList<Image> galleryImages = new ArrayList<>();
    boolean isFeatured = false;
    ProgressDialog pDialog;
    Button btnSave;
    ImageView imgChoose;
    TextView textChoose;
    MyApplication MyApp;
    Toolbar toolbar;
    ScrollView mScrollView;
    ProgressBar mProgressBar;
    Spinner spCategory, spCity, spJobType;
    ArrayList<ItemCategory> mCategoryList;
    ArrayList<String> mName, mCityName;
    ArrayList<ItemCity> mListCity;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_job);
        IsRTL.ifSupported(this);
        toolbar = findViewById(R.id.toolbar);
        toolbar.setTitle(getString(R.string.add_job));
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }

        mScrollView = findViewById(R.id.scrollView);
        mProgressBar = findViewById(R.id.progressBar1);

        pDialog = new ProgressDialog(this);
        edtDate = findViewById(R.id.edt_date);
        edtDate.setManager(getSupportFragmentManager());
        MyApp = MyApplication.getInstance();
        edtTitle = findViewById(R.id.edt_name);
        edtDesignation = findViewById(R.id.edt_designation);
        edtDescription = findViewById(R.id.edt_description);
        edtSalary = findViewById(R.id.edt_salary);
        edtCompanyName = findViewById(R.id.edt_company_name);
        edtWebsite = findViewById(R.id.edt_website);
        edtPhone = findViewById(R.id.edt_phone);
        edtEmail = findViewById(R.id.edt_email);
        edtVacancy = findViewById(R.id.edt_vacancy);
        edtAddress = findViewById(R.id.edt_address);
        edtQualification = findViewById(R.id.edt_qualification);
        edtSkills = findViewById(R.id.edt_skill);
        edtJobWorkDay = findViewById(R.id.edt_company_work_day);
        edtJobWorkTime = findViewById(R.id.edt_company_work_time);
        edtJobExperience = findViewById(R.id.edt_experience);
        btnSave = findViewById(R.id.button_save);
        imgChoose = findViewById(R.id.imageFeatured);
        textChoose = findViewById(R.id.btnChooseFeatured);
        spCategory = findViewById(R.id.spCategory);
        spCity = findViewById(R.id.spCity);
        spJobType = findViewById(R.id.spJobType);
        mCategoryList = new ArrayList<>();
        mListCity = new ArrayList<>();
        mName = new ArrayList<>();
        mCityName = new ArrayList<>();

        textChoose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                chooseGalleryImage();
            }
        });

        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                validator.validate();
            }
        });

        if (NetworkUtils.isConnected(AddJobActivity.this)) {
            getCategory();
        } else {
            Toast.makeText(AddJobActivity.this, getString(R.string.conne_msg1), Toast.LENGTH_SHORT).show();
        }

        validator = new Validator(this);
        validator.setValidationListener(this);
    }

    @Override
    public void onValidationSucceeded() {
        if (NetworkUtils.isConnected(AddJobActivity.this)) {
            if (isFeatured) {
                addJob();
            } else {
                Toast.makeText(AddJobActivity.this, getString(R.string.required), Toast.LENGTH_SHORT).show();
            }
        } else {
            Toast.makeText(AddJobActivity.this, getString(R.string.conne_msg1), Toast.LENGTH_SHORT).show();
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

    public void chooseGalleryImage() {
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
        if (requestCode == Config.RC_PICK_IMAGES && resultCode == RESULT_OK && data != null) {
            galleryImages = data.getParcelableArrayListExtra(Config.EXTRA_IMAGES);
            Uri uri = Uri.fromFile(new File(galleryImages.get(0).getPath()));
            Picasso.get().load(uri).into(imgChoose);
            isFeatured = true;
        }
    }

    private void getCategory() {

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_category");
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

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        ItemCategory objItem = new ItemCategory();
                        objItem.setCategoryId(objJson.getInt(Constant.CATEGORY_CID));
                        objItem.setCategoryName(objJson.getString(Constant.CATEGORY_NAME));
                        objItem.setCategoryImage(objJson.getString(Constant.CATEGORY_IMAGE));
                        mName.add(objJson.getString(Constant.CATEGORY_NAME));
                        mCategoryList.add(objItem);
                    }

                    ArrayAdapter<String> areaAdapter = new ArrayAdapter<>(AddJobActivity.this,
                            android.R.layout.simple_spinner_dropdown_item, mName);
                    spCategory.setAdapter(areaAdapter);

                    getCity();

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
            }

        });
    }

    private void getCity() {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_city");
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson;
                    if (jsonArray.length() > 0) {
                        for (int i = 0; i < jsonArray.length(); i++) {
                            objJson = jsonArray.getJSONObject(i);
                            ItemCity objItem = new ItemCity();
                            objItem.setCityId(objJson.getString(Constant.CITY_ID));
                            objItem.setCityName(objJson.getString(Constant.CITY_NAME));
                            mCityName.add(objJson.getString(Constant.CITY_NAME));
                            mListCity.add(objItem);
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }

                ArrayAdapter<String> typeAdapter = new ArrayAdapter<>(AddJobActivity.this,
                        android.R.layout.simple_spinner_dropdown_item, mCityName);
                spCity.setAdapter(typeAdapter);

                getCompanyDetails();

            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {

            }

        });
    }

    private void getCompanyDetails() {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_company_details");
        jsObj.addProperty("user_id", MyApp.getUserId());
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
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
                    if (jsonArray.length() > 0) {
                        for (int i = 0; i < jsonArray.length(); i++) {
                            objJson = jsonArray.getJSONObject(i);
                            edtCompanyName.setText(objJson.getString("company_name"));
                            edtEmail.setText(objJson.getString("company_email"));
                            edtPhone.setText(objJson.getString("mobile_no"));
                            edtAddress.setText(objJson.getString("company_address"));
                            edtWebsite.setText(objJson.getString("company_website"));
                            edtJobWorkDay.setText(objJson.getString("company_work_day"));
                            edtJobWorkTime.setText(objJson.getString("company_work_time"));
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {

            }

        });
    }

    private void addJob() {
        String title = edtTitle.getText().toString();
        String designation = edtDesignation.getText().toString();
        String description = edtDescription.getText().toString();
        String salary = edtSalary.getText().toString();
        String company = edtCompanyName.getText().toString();
        String website = edtWebsite.getText().toString();
        String phone = edtPhone.getText().toString();
        String email = edtEmail.getText().toString();
        String vacancy = edtVacancy.getText().toString();
        String address = edtAddress.getText().toString();
        String qualification = edtQualification.getText().toString();
        String skill = edtSkills.getText().toString();
        String date = edtDate.getText().toString();
        String workDay = edtJobWorkDay.getText().toString();
        String workTime = edtJobWorkTime.getText().toString();
        String experience = edtJobExperience.getText().toString();

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "job_add");
        jsObj.addProperty("cat_id", mCategoryList.get(spCategory.getSelectedItemPosition()).getCategoryId());
        jsObj.addProperty("city_id", mListCity.get(spCity.getSelectedItemPosition()).getCityId());
        jsObj.addProperty("user_id", MyApp.getUserId());
        jsObj.addProperty("job_name", title);
        jsObj.addProperty("job_designation", designation);
        jsObj.addProperty("job_desc", description);
        jsObj.addProperty("job_salary", salary);
        jsObj.addProperty("job_company_name", company);
        jsObj.addProperty("job_company_website", website);
        jsObj.addProperty("job_phone_number", phone);
        jsObj.addProperty("job_mail", email);
        jsObj.addProperty("job_vacancy", vacancy);
        jsObj.addProperty("job_address", address);
        jsObj.addProperty("job_qualification", qualification);
        jsObj.addProperty("job_skill", skill);
        jsObj.addProperty("job_date", date);
        jsObj.addProperty("job_work_day", workDay);
        jsObj.addProperty("job_work_time", workTime);
        jsObj.addProperty("job_experince", experience);
        jsObj.addProperty("job_type", getJobType());

        params.put("data", API.toBase64(jsObj.toString()));
        try {
            params.put("job_image", new File(galleryImages.get(0).getPath()));
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }

        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {

            @Override
            public void onStart() {
                super.onStart();
                showProgressDialog();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                dismissProgressDialog();
                ActivityCompat.finishAffinity(AddJobActivity.this);
                Intent intent = new Intent(getApplicationContext(), JobProviderMainActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
                finish();
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                dismissProgressDialog();
            }

        });
    }

    private String getJobType() {
        switch (spJobType.getSelectedItemPosition()) {
            case 0:
            default:
                return Constant.JOB_TYPE_FULL;
            case 1:
                return Constant.JOB_TYPE_HALF;
            case 2:
                return Constant.JOB_TYPE_HOURLY;

        }
    }

    public void showProgressDialog() {
        pDialog.setMessage(getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(true);
        pDialog.show();
    }

    public void dismissProgressDialog() {
        if (pDialog != null && pDialog.isShowing()) {
            pDialog.dismiss();
        }
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }
}
