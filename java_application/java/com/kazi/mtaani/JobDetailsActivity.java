package com.kazi.mtaani;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.coordinatorlayout.widget.CoordinatorLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentStatePagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.example.db.DatabaseHelper;
import com.example.fragment.JobDetailsFragment;
import com.example.fragment.SimilarJobFragment;
import com.example.item.ItemJob;
import com.example.util.API;
import com.example.util.ApplyJob;
import com.example.util.BannerAds;
import com.example.util.Constant;
import com.example.util.IsRTL;
import com.example.util.NetworkUtils;
import com.example.util.SaveClickListener;
import com.example.util.SaveJob;
import com.example.util.UserUtils;
import com.google.android.material.tabs.TabLayout;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import cz.msebera.android.httpclient.Header;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class JobDetailsActivity extends AppCompatActivity {

    ProgressBar mProgressBar;
    LinearLayout lyt_not_found;
    ItemJob objBean;
    TextView jobTitle, companyTitle, jobDate, jobDesignation, jobAddress, jobVacancy, jobPhone, jobMail, jobWebsite;
    ImageView image;
    String Id;
    DatabaseHelper databaseHelper;
    Button btnSave;
    LinearLayout mAdViewLayout;
    Button btnApplyJob;
    MyApplication MyApp;
    boolean isFromNotification = false;
    CoordinatorLayout lytParent;
    TabLayout tabLayout;
    ViewPager viewPager;
    boolean isJobSaved = false;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        IsRTL.ifSupported(this);
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
        objBean = new ItemJob();
        Intent i = getIntent();
        Id = i.getStringExtra("Id");
        if (i.hasExtra("isNotification")) {
            isFromNotification = true;
        }

        setTitle(getString(R.string.tool_job_details));
        databaseHelper = new DatabaseHelper(getApplicationContext());
        MyApp = MyApplication.getInstance();
        mProgressBar = findViewById(R.id.progressBar1);
        lyt_not_found = findViewById(R.id.lyt_not_found);

        mAdViewLayout = findViewById(R.id.adView);


        image = findViewById(R.id.image);
        jobTitle = findViewById(R.id.text_job_title);
        companyTitle = findViewById(R.id.text_job_company);
        jobDate = findViewById(R.id.text_job_date);
        jobDesignation = findViewById(R.id.text_job_designation);
        jobAddress = findViewById(R.id.text_job_address);
        jobPhone = findViewById(R.id.text_phone);
        jobWebsite = findViewById(R.id.text_website);
        jobMail = findViewById(R.id.text_email);
        jobVacancy = findViewById(R.id.text_vacancy);
        lytParent = findViewById(R.id.lytParent);
        btnSave = findViewById(R.id.btn_save_job);
        btnApplyJob = findViewById(R.id.btn_apply_job);
        viewPager = findViewById(R.id.viewpager);
        tabLayout = findViewById(R.id.tabs);
        tabLayout.setupWithViewPager(viewPager);

        if (NetworkUtils.isConnected(JobDetailsActivity.this)) {
            getDetails();
        } else {
            showToast(getString(R.string.conne_msg1));
        }

        jobMail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openEmail();
            }
        });

        jobWebsite.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openWebsite();
            }
        });

        jobPhone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialNumber();
            }
        });


        btnSave.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (MyApp.getIsLogin()) {
                    if (NetworkUtils.isConnected(JobDetailsActivity.this)) {
                        SaveClickListener saveClickListener = new SaveClickListener() {
                            @Override
                            public void onItemClick(boolean isSave, String message) {
                                isJobSaved = isSave;
                                if (isJobSaved) {
                                    btnSave.setText(getString(R.string.save_job_already));
                                } else {
                                    btnSave.setText(getString(R.string.save_job));
                                }
                            }
                        };
                        new SaveJob(JobDetailsActivity.this).userSave(Id, saveClickListener);
                    } else {
                        showToast(getString(R.string.conne_msg1));
                    }
                } else {
                    Toast.makeText(JobDetailsActivity.this, getString(R.string.need_login), Toast.LENGTH_SHORT).show();

                    Intent intentLogin = new Intent(JobDetailsActivity.this, SignInActivity.class);
                    intentLogin.putExtra("isOtherScreen", true);
                    startActivity(intentLogin);
                }
            }
        });

        btnApplyJob.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (MyApp.getIsLogin()) {
                    if (NetworkUtils.isConnected(JobDetailsActivity.this)) {
                        new ApplyJob(JobDetailsActivity.this).userApply(Id);
                    } else {
                        showToast(getString(R.string.conne_msg1));
                    }
                } else {
                    Toast.makeText(JobDetailsActivity.this, getString(R.string.need_login), Toast.LENGTH_SHORT).show();

                    Intent intentLogin = new Intent(JobDetailsActivity.this, SignInActivity.class);
                    intentLogin.putExtra("isOtherScreen", true);
                    startActivity(intentLogin);
                }
            }
        });

        BannerAds.ShowBannerAds(JobDetailsActivity.this, mAdViewLayout);

    }

    private void getDetails() {

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_single_job");
        jsObj.addProperty("job_id", Id);
        jsObj.addProperty("user_id", UserUtils.getUserId());
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                mProgressBar.setVisibility(View.VISIBLE);
                lytParent.setVisibility(View.GONE);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                mProgressBar.setVisibility(View.GONE);
                lytParent.setVisibility(View.VISIBLE);

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    isJobSaved = mainJson.getBoolean(Constant.JOB_ALREADY_SAVED);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    if (jsonArray.length() > 0) {
                        JSONObject objJson;
                        for (int i = 0; i < jsonArray.length(); i++) {
                            objJson = jsonArray.getJSONObject(i);
                            if (objJson.has(Constant.STATUS)) {
                                lyt_not_found.setVisibility(View.VISIBLE);
                            } else {
                                objBean.setId(objJson.getString(Constant.JOB_ID));
                                objBean.setJobName(objJson.getString(Constant.JOB_NAME));
                                objBean.setJobCompanyName(objJson.getString(Constant.JOB_COMPANY_NAME));
                                objBean.setJobDate(objJson.getString(Constant.JOB_DATE));
                                objBean.setJobDesignation(objJson.getString(Constant.JOB_DESIGNATION));
                                objBean.setJobAddress(objJson.getString(Constant.JOB_ADDRESS));
                                objBean.setJobImage(objJson.getString(Constant.JOB_IMAGE));
                                objBean.setJobVacancy(objJson.getString(Constant.JOB_VACANCY));
                                objBean.setJobPhoneNumber(objJson.getString(Constant.JOB_PHONE_NO));
                                objBean.setJobMail(objJson.getString(Constant.JOB_MAIL));
                                objBean.setJobCompanyWebsite(objJson.getString(Constant.JOB_SITE));
                                objBean.setJobDesc(objJson.getString(Constant.JOB_DESC));
                                objBean.setJobSkill(objJson.getString(Constant.JOB_SKILL));
                                objBean.setJobQualification(objJson.getString(Constant.JOB_QUALIFICATION));
                                objBean.setJobSalary(objJson.getString(Constant.JOB_SALARY));
                                objBean.setJobWorkDay(objJson.getString(Constant.JOB_WORK_DAY));
                                objBean.setJobWorkTime(objJson.getString(Constant.JOB_WORK_TIME));
                                objBean.setJobExperience(objJson.getString(Constant.JOB_EXP));
                                objBean.setJobType(objJson.getString(Constant.JOB_TYPE));
                            }
                        }
                        setResult();

                    } else {
                        mProgressBar.setVisibility(View.GONE);
                        lytParent.setVisibility(View.GONE);
                        lyt_not_found.setVisibility(View.VISIBLE);
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                mProgressBar.setVisibility(View.GONE);
                lytParent.setVisibility(View.GONE);
                lyt_not_found.setVisibility(View.VISIBLE);
            }
        });
    }

    private void setResult() {
        firstFavourite();
        jobTitle.setText(objBean.getJobName());
        companyTitle.setText(objBean.getJobCompanyName());
        jobDate.setText(objBean.getJobDate());
        jobDesignation.setText(objBean.getJobDesignation());
        jobAddress.setText(objBean.getJobAddress());
        jobPhone.setText(objBean.getJobPhoneNumber());
        jobWebsite.setText(objBean.getJobCompanyWebsite());
        jobMail.setText(objBean.getJobMail());
        jobVacancy.setText(getString(R.string.job_vacancy_lbl_details, objBean.getJobVacancy()));
        Picasso.get().load(objBean.getJobImage()).into(image);

        setupViewPager(viewPager);
        tabLayout.setupWithViewPager(viewPager);

    }

    private void setupViewPager(final ViewPager viewPager) {
        final ViewPagerAdapter adapter = new ViewPagerAdapter(getSupportFragmentManager());
        adapter.addFragment(JobDetailsFragment.newInstance(objBean), getString(R.string.tab_job_details));
        adapter.addFragment(SimilarJobFragment.newInstance(Id), getString(R.string.tab_job_similar));
        viewPager.setAdapter(adapter);
        viewPager.setOffscreenPageLimit(1);
    }

    class ViewPagerAdapter extends FragmentStatePagerAdapter {
        private final List<Fragment> mFragmentList = new ArrayList<>();
        private final List<String> mFragmentTitleList = new ArrayList<>();

        private ViewPagerAdapter(FragmentManager manager) {
            super(manager);
        }

        @Override
        public Fragment getItem(int position) {
            return mFragmentList.get(position);
        }

        @Override
        public int getItemPosition(@NonNull Object object) {
            return FragmentStatePagerAdapter.POSITION_NONE;
        }

        @Override
        public int getCount() {
            return mFragmentList.size();
        }

        private void addFragment(Fragment fragment, String title) {
            mFragmentList.add(fragment);
            mFragmentTitleList.add(title);
        }

        @Override
        public CharSequence getPageTitle(int position) {
            return mFragmentTitleList.get(position);
        }
    }

    public void showToast(String msg) {
        Toast.makeText(JobDetailsActivity.this, msg, Toast.LENGTH_SHORT).show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_share, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
            case R.id.menu_edit:
                Intent sendIntent = new Intent();
                sendIntent.setAction(Intent.ACTION_SEND);
                sendIntent.putExtra(Intent.EXTRA_TEXT,
                        objBean.getJobName() + "\n" +
                                getString(R.string.job_company_lbl) + objBean.getJobCompanyName() + "\n" +
                                getString(R.string.job_designation_lbl) + objBean.getJobDesignation() + "\n" +
                                getString(R.string.job_phone_lbl) + objBean.getJobPhoneNumber() + "\n" +
                                getString(R.string.job_email_lbl) + objBean.getJobMail() + "\n" +
                                getString(R.string.job_website_lbl) + objBean.getJobCompanyWebsite() + "\n" +
                                getString(R.string.job_address_lbl) + objBean.getJobAddress() + "\n\n" +
                                "Download Application here https://play.google.com/store/apps/details?id=" + getPackageName());
                sendIntent.setType("text/plain");
                startActivity(sendIntent);
                break;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }

    private void openWebsite() {
        startActivity(new Intent(
                Intent.ACTION_VIEW,
                Uri.parse(addHttp(objBean.getJobCompanyWebsite()))));
    }

    private void openEmail() {
        Intent emailIntent = new Intent(Intent.ACTION_SENDTO, Uri.fromParts(
                "mailto", objBean.getJobMail(), null));
        emailIntent
                .putExtra(Intent.EXTRA_SUBJECT, "Apply for the post " + objBean.getJobDesignation());
        startActivity(Intent.createChooser(emailIntent, "Send suggestion..."));
    }

    protected String addHttp(String string1) {
        // TODO Auto-generated method stub
        if (string1.startsWith("http://"))
            return String.valueOf(string1);
        else
            return "http://" + String.valueOf(string1);
    }

    private void dialNumber() {
        Intent intent = new Intent(Intent.ACTION_DIAL, Uri.fromParts("tel", objBean.getJobPhoneNumber(), null));
        startActivity(intent);
    }

    private void firstFavourite() {
        if (isJobSaved) {
            btnSave.setText(getString(R.string.save_job_already));
        } else {
            btnSave.setText(getString(R.string.save_job));
        }
    }

    @Override
    public void onBackPressed() {
        if (isFromNotification) {
            Intent intent = new Intent(JobDetailsActivity.this, MainActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        } else {
            super.onBackPressed();
        }
    }
}
