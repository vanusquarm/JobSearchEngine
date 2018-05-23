package com.julian.jobsearch.ui.post;

import android.app.ProgressDialog;
import android.os.Bundle;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.JobPost;
import com.julian.jobsearch.data.repository.JobPostRepository;

import javax.inject.Inject;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.Disposable;
import io.reactivex.functions.Consumer;

public class PostActivity extends AppCompatActivity implements View.OnClickListener {
    String[] education = {"University level", "High School Graduate", "Basic School","First Degree","Masters Degree","None"};
    String[] hours = {"Full time", "Part time"};
    @Inject
    JobPostRepository jobPostRepository;
    private EditText job_title, job_descrip, closes;
    private AutoCompleteTextView edu_req, hours1;
    // private RecycleAdapter mAdapter;
    private Button post;
    private AutoCompleteTextView educationalRequirementsTextView;
    private AutoCompleteTextView hoursTextView;
    private CompositeDisposable compositeDisposable;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_post);

        compositeDisposable = new CompositeDisposable();
        ((JobSearchApp) getApplication()).getAppComponent().inject(this);

        job_title = findViewById(R.id.surname2);
        job_descrip = findViewById(R.id.email2);
        closes = findViewById(R.id.field);
        post = findViewById(R.id.post);

        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.select_dialog_singlechoice, education);
        ArrayAdapter<String> adapter1 = new ArrayAdapter<String>(this, android.R.layout.select_dialog_singlechoice, hours);

        educationalRequirementsTextView = findViewById(R.id.username);
        educationalRequirementsTextView.setThreshold(1);
        educationalRequirementsTextView.setAdapter(adapter);

        hoursTextView = findViewById(R.id.contact2);
        hoursTextView.setThreshold(1);
        hoursTextView.setAdapter(adapter1);

        post.setOnClickListener(this);
    }

    @Override
    protected void onStop() {
        super.onStop();
        compositeDisposable.clear();
    }

    @Override
    public void onClick(View v) {
        if (areFieldsValid()) {
            ProgressDialog progressDialog = ProgressDialog.show(this, "", "Posting Job");
            JobPost jobPost = new JobPost(
                    job_title.getText().toString(),
                    job_descrip.getText().toString(),
                    educationalRequirementsTextView.getText().toString(),
                    hoursTextView.getText().toString(),
                    closes.getText().toString());
            Disposable disposable = jobPostRepository.postJob(jobPost)
                    .observeOn(AndroidSchedulers.mainThread())
                    .subscribe(new Consumer<Boolean>() {
                        @Override
                        public void accept(Boolean aBoolean) throws Exception {
                            progressDialog.dismiss();
                            finish();
                        }
                    }, new Consumer<Throwable>() {
                        @Override
                        public void accept(Throwable throwable) throws Exception {
                            progressDialog.dismiss();
                            showError();
                        }
                    });

            compositeDisposable.add(disposable);
        }
    }

    private void showError() {
        Snackbar.make(post, "There was an error submitting your post", Snackbar.LENGTH_LONG).show();
    }

    private boolean areFieldsValid() {
        job_title.setError(null);
        job_descrip.setError(null);
        educationalRequirementsTextView.setError(null);
        hoursTextView.setError(null);
        closes.setError(null);

        if (TextUtils.isEmpty(job_title.getText())) {
            job_title.setError("Please provide a job title");
            return false;
        }

        if (TextUtils.isEmpty(job_descrip.getText())) {
            job_descrip.setError("Please provide a job description");
            return false;
        }

        if (TextUtils.isEmpty(educationalRequirementsTextView.getText())) {
            educationalRequirementsTextView.setError("Please provide educational requirements");
            return false;
        }

        if (TextUtils.isEmpty(hoursTextView.getText())) {
            hoursTextView.setError("Please specify hours");
            return false;
        }

        if (TextUtils.isEmpty(closes.getText())) {
            closes.setError("Please provide a closing date");
            return false;
        }

        return true;
    }
}
