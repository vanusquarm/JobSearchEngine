package com.example.fragment;

import android.content.Intent;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.widget.NestedScrollView;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.kazi.mtaani.JobDetailsActivity;
import com.kazi.mtaani.MainActivity;
import com.kazi.mtaani.R;
import com.kazi.mtaani.SearchActivity;
import com.example.adapter.HomeCategoryAdapter;
import com.example.adapter.HomeJobAdapter;
import com.example.item.ItemCategory;
import com.example.item.ItemJob;
import com.example.util.API;
import com.example.util.Constant;
import com.example.util.Events;
import com.example.util.GlobalBus;
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

public class HomeFragment extends Fragment {

    ProgressBar mProgressBar;
    LinearLayout lyt_not_found;
    NestedScrollView nestedScrollView;
    TextView categoryViewAll, recentViewAll, latestViewAll;
    RecyclerView rvCategory, rvRecentJob, rvLatestJob;
    ArrayList<ItemCategory> categoryList;
    ArrayList<ItemJob> jobRecentList, jobLatestList;
    LinearLayout lytCategory, lytRecent, lytLatest;
    HomeCategoryAdapter categoryAdapter;
    HomeJobAdapter recentJobAdapter, latestAdapter;
    EditText edtSearch;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_home, container, false);
        GlobalBus.getBus().register(this);
        categoryList = new ArrayList<>();
        jobRecentList = new ArrayList<>();
        jobLatestList = new ArrayList<>();

        mProgressBar = rootView.findViewById(R.id.progressBar1);
        lyt_not_found = rootView.findViewById(R.id.lyt_not_found);
        nestedScrollView = rootView.findViewById(R.id.nestedScrollView);
        categoryViewAll = rootView.findViewById(R.id.textCategoryViewAll);
        latestViewAll = rootView.findViewById(R.id.textLatestViewAll);
        recentViewAll = rootView.findViewById(R.id.textRecentViewAll);

        lytCategory = rootView.findViewById(R.id.lytHomeTVCategory);
        lytRecent = rootView.findViewById(R.id.lytHomeRecent);
        lytLatest = rootView.findViewById(R.id.lytHomeLatest);

        rvCategory = rootView.findViewById(R.id.rv_category);
        rvRecentJob = rootView.findViewById(R.id.rv_recent);
        rvLatestJob = rootView.findViewById(R.id.rv_latest);
        edtSearch = rootView.findViewById(R.id.edt_search);

        rvCategory.setHasFixedSize(true);
        rvCategory.setLayoutManager(new LinearLayoutManager(getActivity(), LinearLayoutManager.HORIZONTAL, false));
        rvCategory.setFocusable(false);
        rvCategory.setNestedScrollingEnabled(false);

        rvRecentJob.setHasFixedSize(true);
        rvRecentJob.setLayoutManager(new LinearLayoutManager(getActivity(), LinearLayoutManager.VERTICAL, false));
        rvRecentJob.setFocusable(false);
        rvRecentJob.setNestedScrollingEnabled(false);

        rvLatestJob.setHasFixedSize(true);
        rvLatestJob.setLayoutManager(new LinearLayoutManager(getActivity(), LinearLayoutManager.VERTICAL, false));
        rvLatestJob.setFocusable(false);
        rvLatestJob.setNestedScrollingEnabled(false);

        categoryViewAll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String categoryName = getString(R.string.menu_category);
                FragmentManager fm = getFragmentManager();
                CategoryFragment channelFragment = new CategoryFragment();
                assert fm != null;
                FragmentTransaction ft = fm.beginTransaction();
                ft.hide(HomeFragment.this);
                ft.add(R.id.Container, channelFragment, categoryName);
                ft.addToBackStack(categoryName);
                ft.commit();
                ((MainActivity) requireActivity()).setToolbarTitle(categoryName);
            }
        });

        recentViewAll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String categoryName = getString(R.string.recent);
                Bundle bundle = new Bundle();
                bundle.putBoolean("isLatest", false);

                FragmentManager fm = getFragmentManager();
                LatestFragment latestFragment = new LatestFragment();
                latestFragment.setArguments(bundle);
                assert fm != null;
                FragmentTransaction ft = fm.beginTransaction();
                ft.hide(HomeFragment.this);
                ft.add(R.id.Container, latestFragment, categoryName);
                ft.addToBackStack(categoryName);
                ft.commit();
                ((MainActivity) requireActivity()).setToolbarTitle(categoryName);
            }
        });

        latestViewAll.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String categoryName = getString(R.string.latest);
                Bundle bundle = new Bundle();
                bundle.putBoolean("isLatest", true);

                FragmentManager fm = getFragmentManager();
                LatestFragment latestFragment = new LatestFragment();
                latestFragment.setArguments(bundle);
                assert fm != null;
                FragmentTransaction ft = fm.beginTransaction();
                ft.hide(HomeFragment.this);
                ft.add(R.id.Container, latestFragment, categoryName);
                ft.addToBackStack(categoryName);
                ft.commit();
                ((MainActivity) requireActivity()).setToolbarTitle(categoryName);
            }
        });

        edtSearch.setOnEditorActionListener(new TextView.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView v, int actionId, KeyEvent event) {
                if (actionId == EditorInfo.IME_ACTION_SEARCH) {
                    String searchText = edtSearch.getText().toString();
                    if (!searchText.isEmpty()) {
                        Intent intent = new Intent(getActivity(), SearchActivity.class);
                        intent.putExtra("searchText", searchText);
                        startActivity(intent);
                        edtSearch.getText().clear();
                    }
                }
                return false;
            }
        });


        if (NetworkUtils.isConnected(getActivity())) {
            getHome();
        } else {
            Toast.makeText(getActivity(), getString(R.string.conne_msg1), Toast.LENGTH_SHORT).show();
        }

        return rootView;
    }

    private void getHome() {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        final JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_home");
        jsObj.addProperty("user_id", UserUtils.getUserId());
        params.put("data", API.toBase64(jsObj.toString()));

        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                mProgressBar.setVisibility(View.VISIBLE);
                nestedScrollView.setVisibility(View.GONE);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                mProgressBar.setVisibility(View.GONE);
                nestedScrollView.setVisibility(View.VISIBLE);

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONObject jobAppJson = mainJson.getJSONObject(Constant.ARRAY_NAME);


                    JSONArray categoryArray = jobAppJson.getJSONArray("cat_list");
                    for (int i = 0; i < categoryArray.length(); i++) {
                        JSONObject jsonObject = categoryArray.getJSONObject(i);
                        ItemCategory itemCategory = new ItemCategory();
                        itemCategory.setCategoryId(jsonObject.getInt(Constant.CATEGORY_CID));
                        itemCategory.setCategoryName(jsonObject.getString(Constant.CATEGORY_NAME));
                        itemCategory.setCategoryImage(jsonObject.getString(Constant.CATEGORY_IMAGE));
                        categoryList.add(itemCategory);
                    }

                    JSONArray jobArray = jobAppJson.getJSONArray("recent_job");
                    for (int i = 0; i < jobArray.length(); i++) {
                        JSONObject jsonObject = jobArray.getJSONObject(i);
                        ItemJob objItem = new ItemJob();
                        objItem.setId(jsonObject.getString(Constant.JOB_ID));
                        objItem.setJobName(jsonObject.getString(Constant.JOB_NAME));
                        objItem.setJobAddress(jsonObject.getString(Constant.JOB_ADDRESS));
                        objItem.setJobType(jsonObject.getString(Constant.JOB_TYPE));
                        objItem.setJobImage(jsonObject.getString(Constant.JOB_IMAGE));
                        objItem.setJobFavourite(jsonObject.getBoolean(Constant.JOB_FAVOURITE));
                        jobRecentList.add(objItem);
                    }

                    JSONArray jobArrayLatest = jobAppJson.getJSONArray("latest_job");
                    for (int i = 0; i < jobArrayLatest.length(); i++) {
                        JSONObject jsonObject = jobArrayLatest.getJSONObject(i);
                        ItemJob objItem = new ItemJob();
                        objItem.setId(jsonObject.getString(Constant.JOB_ID));
                        objItem.setJobName(jsonObject.getString(Constant.JOB_NAME));
                        objItem.setJobAddress(jsonObject.getString(Constant.JOB_ADDRESS));
                        objItem.setJobType(jsonObject.getString(Constant.JOB_TYPE));
                        objItem.setJobImage(jsonObject.getString(Constant.JOB_IMAGE));
                        objItem.setJobFavourite(jsonObject.getBoolean(Constant.JOB_FAVOURITE));
                        jobLatestList.add(objItem);
                    }

                    displayData();

                } catch (JSONException e) {
                    e.printStackTrace();
                    nestedScrollView.setVisibility(View.GONE);
                    lyt_not_found.setVisibility(View.VISIBLE);
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                mProgressBar.setVisibility(View.GONE);
                nestedScrollView.setVisibility(View.GONE);
                lyt_not_found.setVisibility(View.VISIBLE);
            }
        });
    }

    private void displayData() {
        if (!categoryList.isEmpty()) {
            categoryAdapter = new HomeCategoryAdapter(getActivity(), categoryList);
            rvCategory.setAdapter(categoryAdapter);

            categoryAdapter.setOnItemClickListener(new RvOnClickListener() {
                @Override
                public void onItemClick(int position) {
                    String categoryName = categoryList.get(position).getCategoryName();
                    String categoryId = String.valueOf(categoryList.get(position).getCategoryId());
                    Bundle bundle = new Bundle();
                    bundle.putString("categoryId", categoryId);

                    FragmentManager fm = getFragmentManager();
                    CategoryItemFragment channelFragment = new CategoryItemFragment();
                    channelFragment.setArguments(bundle);
                    assert fm != null;
                    FragmentTransaction ft = fm.beginTransaction();
                    ft.hide(HomeFragment.this);
                    ft.add(R.id.Container, channelFragment, categoryName);
                    ft.addToBackStack(categoryName);
                    ft.commit();
                    ((MainActivity) requireActivity()).setToolbarTitle(categoryName);
                }
            });

        } else {
            lytCategory.setVisibility(View.GONE);
        }

        if (!jobRecentList.isEmpty()) {
            recentJobAdapter = new HomeJobAdapter(getActivity(), jobRecentList);
            rvRecentJob.setAdapter(recentJobAdapter);

            recentJobAdapter.setOnItemClickListener(new RvOnClickListener() {
                @Override
                public void onItemClick(int position) {
                    String jobId = jobRecentList.get(position).getId();
                    Intent intent = new Intent(getActivity(), JobDetailsActivity.class);
                    intent.putExtra("Id", jobId);
                    startActivity(intent);
                }
            });

        } else {
            lytRecent.setVisibility(View.GONE);
        }

        if (!jobLatestList.isEmpty()) {
            latestAdapter = new HomeJobAdapter(getActivity(), jobLatestList);
            rvLatestJob.setAdapter(latestAdapter);

            latestAdapter.setOnItemClickListener(new RvOnClickListener() {
                @Override
                public void onItemClick(int position) {
                    String jobId = jobLatestList.get(position).getId();
                    Intent intent = new Intent(getActivity(), JobDetailsActivity.class);
                    intent.putExtra("Id", jobId);
                    startActivity(intent);
                }
            });
        } else {
            lytLatest.setVisibility(View.GONE);
        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        GlobalBus.getBus().unregister(this);
    }

    @Subscribe
    public void getSaveJob(Events.SaveJob saveJob) {
        for (int i = 0; i < jobLatestList.size(); i++) {
            if (jobLatestList.get(i).getId().equals(saveJob.getJobId())) {
                jobLatestList.get(i).setJobFavourite(saveJob.isSave());
                latestAdapter.notifyItemChanged(i);
            }
        }

        for (int i = 0; i < jobRecentList.size(); i++) {
            if (jobRecentList.get(i).getId().equals(saveJob.getJobId())) {
                jobRecentList.get(i).setJobFavourite(saveJob.isSave());
                recentJobAdapter.notifyItemChanged(i);
            }
        }
    }
}
