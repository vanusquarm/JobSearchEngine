package com.kazi.mtaani;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.adapter.JobAdapter;
import com.example.item.ItemJob;
import com.example.util.API;
import com.example.util.BannerAds;
import com.example.util.Constant;
import com.example.util.EndlessRecyclerViewScrollListener;
import com.example.util.Events;
import com.example.util.GlobalBus;
import com.example.util.IsRTL;
import com.example.util.NetworkUtils;
import com.example.util.RvOnClickListener;
import com.example.util.UserUtils;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.greenrobot.eventbus.Subscribe;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cz.msebera.android.httpclient.Header;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class SearchActivity extends AppCompatActivity {

    ArrayList<ItemJob> mListItem;
    public RecyclerView recyclerView;
    JobAdapter adapter;
    private ProgressBar progressBar;
    private LinearLayout lyt_not_found;
    boolean isFirst = true, isOver = false;
    private int pageIndex = 1;
    String searchText;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_category_item);
        GlobalBus.getBus().register(this);
        IsRTL.ifSupported(this);
        Toolbar toolbar = findViewById(R.id.toolbar);
        toolbar.setTitle(getString(R.string.menu_search));
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }

        LinearLayout mAdViewLayout = findViewById(R.id.adView);
        BannerAds.ShowBannerAds(this, mAdViewLayout);

        Intent intent = getIntent();
        searchText = intent.getStringExtra("searchText");

        mListItem = new ArrayList<>();
        lyt_not_found = findViewById(R.id.lyt_not_found);
        progressBar = findViewById(R.id.progressBar);
        recyclerView = findViewById(R.id.vertical_courses_list);
        recyclerView.setHasFixedSize(true);
        GridLayoutManager layoutManager = new GridLayoutManager(this, 1);
        recyclerView.setLayoutManager(layoutManager);

        layoutManager.setSpanSizeLookup(new GridLayoutManager.SpanSizeLookup() {
            @Override
            public int getSpanSize(int position) {
                switch (adapter.getItemViewType(position)) {
                    case 0:
                        return 1;
                    default:
                        return 1;
                }
            }
        });


        if (NetworkUtils.isConnected(SearchActivity.this)) {
            getSearchItem();
        } else {
            Toast.makeText(SearchActivity.this, getString(R.string.conne_msg1), Toast.LENGTH_SHORT).show();
        }

        recyclerView.addOnScrollListener(new EndlessRecyclerViewScrollListener(layoutManager) {
            @Override
            public void onLoadMore(int page, int totalItemsCount) {
                if (!isOver) {
                    new Handler().postDelayed(new Runnable() {
                        @Override
                        public void run() {
                            pageIndex++;
                            getSearchItem();
                        }
                    }, 1000);
                } else {
                    adapter.hideHeader();
                }
            }
        });
    }

    private void getSearchItem() {

        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "search_by_keyword");
        jsObj.addProperty("search_text", searchText);
        jsObj.addProperty("user_id", UserUtils.getUserId());
        jsObj.addProperty("page", pageIndex);
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                if (isFirst)
                    showProgress(true);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                if (isFirst)
                    showProgress(false);

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.ARRAY_NAME);
                    JSONObject objJson;
                    if (jsonArray.length() > 0) {
                        for (int i = 0; i < jsonArray.length(); i++) {
                            objJson = jsonArray.getJSONObject(i);
                            if (objJson.has(Constant.STATUS)) {
                                lyt_not_found.setVisibility(View.VISIBLE);
                            } else {
                                ItemJob objItem = new ItemJob();
                                objItem.setId(objJson.getString(Constant.JOB_ID));
                                objItem.setJobName(objJson.getString(Constant.JOB_NAME));
                                objItem.setJobAddress(objJson.getString(Constant.JOB_ADDRESS));
                                objItem.setJobType(objJson.getString(Constant.JOB_TYPE));
                                objItem.setJobImage(objJson.getString(Constant.JOB_IMAGE));
                                objItem.setJobFavourite(objJson.getBoolean(Constant.JOB_FAVOURITE));
                                mListItem.add(objItem);
                            }
                        }
                    } else {
                        isOver = true;
                        if (adapter != null) { // when there is no data in first time
                            adapter.hideHeader();
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                displayData();
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                showProgress(false);
                lyt_not_found.setVisibility(View.VISIBLE);
            }

        });
    }

    private void displayData() {
        if (mListItem.size() == 0) {
            lyt_not_found.setVisibility(View.VISIBLE);
        } else {

            lyt_not_found.setVisibility(View.GONE);
            if (isFirst) {
                isFirst = false;
                adapter = new JobAdapter(SearchActivity.this, mListItem);
                recyclerView.setAdapter(adapter);
            } else {
                adapter.notifyDataSetChanged();
            }

            adapter.setOnItemClickListener(new RvOnClickListener() {
                @Override
                public void onItemClick(int position) {
                    String jobId = mListItem.get(position).getId();
                    Intent intent = new Intent(SearchActivity.this, JobDetailsActivity.class);
                    intent.putExtra("Id", jobId);
                    startActivity(intent);
                }
            });

        }
    }

    private void showProgress(boolean show) {
        if (show) {
            progressBar.setVisibility(View.VISIBLE);
            recyclerView.setVisibility(View.GONE);
            lyt_not_found.setVisibility(View.GONE);
        } else {
            progressBar.setVisibility(View.GONE);
            recyclerView.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public boolean onSupportNavigateUp() {
        onBackPressed();
        return true;
    }

    @Subscribe
    public void getSaveJob(Events.SaveJob saveJob) {
        for (int i = 0; i < mListItem.size(); i++) {
            if (mListItem.get(i).getId().equals(saveJob.getJobId())) {
                mListItem.get(i).setJobFavourite(saveJob.isSave());
                adapter.notifyItemChanged(i);
            }
        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        GlobalBus.getBus().unregister(this);
    }
}
