package com.kazi.mtaani;

import android.Manifest;
import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatEditText;
import androidx.appcompat.widget.Toolbar;
import androidx.cardview.widget.CardView;
import androidx.core.app.ActivityCompat;
import androidx.core.widget.NestedScrollView;

import com.example.util.API;
import com.example.util.Constant;
import com.example.util.IsRTL;
import com.example.util.NetworkUtils;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.karumi.dexter.Dexter;
import com.karumi.dexter.PermissionToken;
import com.karumi.dexter.listener.PermissionDeniedResponse;
import com.karumi.dexter.listener.PermissionGrantedResponse;
import com.karumi.dexter.listener.PermissionRequest;
import com.karumi.dexter.listener.single.PermissionListener;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;
import com.mobsandgeeks.saripaar.annotation.Email;
import com.mobsandgeeks.saripaar.annotation.Length;
import com.mobsandgeeks.saripaar.annotation.NotEmpty;
import com.nbsp.materialfilepicker.MaterialFilePicker;
import com.nbsp.materialfilepicker.ui.FilePickerActivity;
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
import java.util.regex.Pattern;

import cz.msebera.android.httpclient.Header;
import de.hdodenhof.circleimageview.CircleImageView;
import io.blackbox_vision.datetimepickeredittext.view.DatePickerEditText;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;


public class EditProfileActivity extends AppCompatActivity implements Validator.ValidationListener {

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
    EditText edtCompany;
    @NotEmpty
    EditText edtExperience;
    @NotEmpty
    DatePickerEditText edtDate;
    private Validator validator;

    MyApplication MyApp;

    String strFullName, strEmail, strPassword, strMobi, strCity, strAddress, strSkills = "", strCompany, strExperience, strUserImage, strMessage;

    Toolbar toolbar;
    NestedScrollView mScrollView;
    ProgressBar mProgressBar;
    private LinearLayout lyt_not_found;
    CardView lytSaveApply;

    TextView txtResumeName, txtSaveJob, txtAppliedJob, txtName, txtCurrentCompanyName, txtUserExp, txtUserSkills, txtSkillAdd, txtResumeLbl;
    ImageView imgChoose;

    public int FILE_PICKER_REQUEST_CODE = 3000;

    private ArrayList<Image> featuredImages = new ArrayList<>();
    String docPath;
    boolean isFeatured = false;
    boolean isResume = false;
    ProgressDialog pDialog;
    RelativeLayout lytResume;
    CircleImageView imageUser;
    Button btnSubmit;
    RadioButton rdMale, rdFemale;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_profile);
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
        lytResume = findViewById(R.id.lytResume);
        lytSaveApply = findViewById(R.id.lytSaveApply);
        edtFullName = findViewById(R.id.edt_name);
        edtEmail = findViewById(R.id.edt_email);
        edtPassword = findViewById(R.id.edt_password);
        edtMobile = findViewById(R.id.edt_phone);
        edtCity = findViewById(R.id.edt_city);
        edtAddress = findViewById(R.id.edt_address);
        edtExperience = findViewById(R.id.edt_experience);
        edtCompany = findViewById(R.id.edt_company);
        mScrollView = findViewById(R.id.nestedScrollView);
        mProgressBar = findViewById(R.id.progressBar1);
        pDialog = new ProgressDialog(this);
        txtResumeName = findViewById(R.id.textResume);
        btnSubmit = findViewById(R.id.button_save);
        rdMale = findViewById(R.id.rdMale);
        rdFemale = findViewById(R.id.rdFeMale);

        txtSaveJob = findViewById(R.id.textSavedJob);
        txtAppliedJob = findViewById(R.id.textAppliedJob);
        txtName = findViewById(R.id.textUserName);
        txtCurrentCompanyName = findViewById(R.id.textUserPosition);
        txtUserExp = findViewById(R.id.textUserExp);
        txtUserSkills = findViewById(R.id.textSkills);
        txtSkillAdd = findViewById(R.id.textSkillsAdd);
        txtResumeLbl = findViewById(R.id.textResumeLbl);

        imgChoose = findViewById(R.id.imageUpload);
        imageUser = findViewById(R.id.image_profile);

        edtDate = findViewById(R.id.edt_date);
        edtDate.setManager(getSupportFragmentManager());

        if (MyApp.getIsJobProvider()) {
            lytResume.setVisibility(View.GONE);
            lytSaveApply.setVisibility(View.GONE);
            txtResumeLbl.setVisibility(View.GONE);
        }

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
                chooseFeaturedImage();
            }
        });

        lytResume.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                requestStoragePermission();
            }
        });

        txtSkillAdd.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openSkillsDialog();
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
        if (NetworkUtils.isConnected(EditProfileActivity.this)) {
            strPassword = edtPassword.getText().toString();
            if (strPassword.length() >= 1 && strPassword.length() <= 5) {
                edtPassword.setError("Invalid password min 6 character needed");
            } else {
                if (isResume) {
                    editUserProfile();
                } else {
                    showToast(getString(R.string.resume_needed));
                }

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

    private void openSkillsDialog() {
        final Dialog dialog = new Dialog(EditProfileActivity.this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setCancelable(false);
        dialog.setContentView(R.layout.dialog_skills);

        final AppCompatEditText editText = dialog.findViewById(R.id.editText_Report);
        Button button = dialog.findViewById(R.id.button_submit);
        if (!strSkills.isEmpty()) {
            editText.setText(strSkills);
        }
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String userSkills = editText.getText().toString();
                if (!userSkills.isEmpty()) {
                    strSkills = userSkills;
                    txtUserSkills.setText(strSkills);
                    dialog.dismiss();
                } else {
                    showToast(getString(R.string.enter_skills_must));
                }
            }
        });

        dialog.show();
        Window window = dialog.getWindow();
        assert window != null;
        window.setLayout(WindowManager.LayoutParams.MATCH_PARENT, WindowManager.LayoutParams.WRAP_CONTENT);
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
                        txtSaveJob.setText(objJson.getString(Constant.USER_TOTAL_SAVED_JOB));
                        txtAppliedJob.setText(objJson.getString(Constant.USER_TOTAL_APPLIED_JOB));

                        if (!objJson.getString(Constant.USER_CURRENT_COMPANY).isEmpty()) {
                            txtCurrentCompanyName.setText(objJson.getString(Constant.USER_CURRENT_COMPANY));
                            edtCompany.setText(objJson.getString(Constant.USER_CURRENT_COMPANY));
                        }

                        if (!objJson.getString(Constant.USER_EXPERIENCE).isEmpty()) {
                            txtUserExp.setText(objJson.getString(Constant.USER_EXPERIENCE));
                            edtExperience.setText(objJson.getString(Constant.USER_EXPERIENCE));
                        }

                        if (!objJson.getString(Constant.USER_SKILLS).isEmpty()) {
                            txtUserSkills.setText(objJson.getString(Constant.USER_SKILLS));
                            strSkills = objJson.getString(Constant.USER_SKILLS);
                        }

                        if (!objJson.getString(Constant.USER_CITY).isEmpty()) {
                            edtCity.setText(objJson.getString(Constant.USER_CITY));
                        }
                        if (!objJson.getString(Constant.USER_ADDRESS).isEmpty()) {
                            edtAddress.setText(objJson.getString(Constant.USER_ADDRESS));
                        }
                        if (!objJson.getString(Constant.USER_IMAGE).isEmpty()) {
                            Picasso.get().load(objJson.getString(Constant.USER_IMAGE)).into(imageUser);
                        }
                        if (!objJson.getString(Constant.USER_RESUME).isEmpty()) {
                            String resumePath = objJson.getString(Constant.USER_RESUME);
                            txtResumeName.setText(resumePath.substring(resumePath.lastIndexOf("/") + 1));
                            isResume = true;
                        }

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
        Toast.makeText(EditProfileActivity.this, msg, Toast.LENGTH_SHORT).show();
    }


    public void setResult() {

        if (Constant.GET_SUCCESS_MSG == 0) {
            showToast(strMessage);
        } else {
            showToast(strMessage);
            MyApp.saveLogin(MyApp.getUserId(), strFullName, strEmail);
            if (isFeatured) {
                MyApp.saveUserImage(strUserImage);
            }
            ActivityCompat.finishAffinity(EditProfileActivity.this);
            Intent intent = new Intent(getApplicationContext(), MainActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        }
    }

    public void chooseFeaturedImage() {
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
                featuredImages = data.getParcelableArrayListExtra(Config.EXTRA_IMAGES);
                Uri uri = Uri.fromFile(new File(featuredImages.get(0).getPath()));
                Picasso.get().load(uri).into(imageUser);
                isFeatured = true;
            }
        } else if (requestCode == FILE_PICKER_REQUEST_CODE) {
            if (resultCode == Activity.RESULT_OK && data != null) {
                docPath = data.getStringExtra(FilePickerActivity.RESULT_FILE_PATH);
                txtResumeName.setText(docPath.substring(docPath.lastIndexOf("/") + 1));
                isResume = true;
            }
        }
    }

    private void requestStoragePermission() {
        Dexter.withActivity(this)
                .withPermission(Manifest.permission.WRITE_EXTERNAL_STORAGE)
                .withListener(new PermissionListener() {
                    @Override
                    public void onPermissionGranted(PermissionGrantedResponse response) {
                        openFilePicker();
                    }

                    @Override
                    public void onPermissionDenied(PermissionDeniedResponse response) {
                        // check for permanent denial of permission
                    }

                    @Override
                    public void onPermissionRationaleShouldBeShown(PermissionRequest permission, PermissionToken token) {
                        token.continuePermissionRequest();
                    }
                }).check();
    }

    private void openFilePicker() {
        new MaterialFilePicker()
                .withActivity(this)
                .withRequestCode(FILE_PICKER_REQUEST_CODE)
                .withHiddenFiles(false)
                .withFilter(Pattern.compile(".*\\.(pdf|docx|doc)$"))
                .withTitle("Tap to Open")
                .start();
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
        strCompany = edtCompany.getText().toString();
        strExperience = edtExperience.getText().toString();
        String dob = edtDate.getText().toString();

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
        jsObj.addProperty("current_company_name", strCompany);
        jsObj.addProperty("experiences", strExperience);
        jsObj.addProperty("skills", strSkills);
        jsObj.addProperty("date_of_birth", dob);
        jsObj.addProperty("gender", rdMale.isChecked() ? Constant.MALE : Constant.FEMALE);
        params.put("data", API.toBase64(jsObj.toString()));
        Log.e("data", API.toBase64(jsObj.toString()));
        if (isFeatured) {
            try {
                params.put("user_image", new File(featuredImages.get(0).getPath()));
            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
        }
        if (isResume) {
            if (docPath != null && !docPath.isEmpty()) {
                try {
                    params.put("user_resume", new File(docPath));
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }
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
