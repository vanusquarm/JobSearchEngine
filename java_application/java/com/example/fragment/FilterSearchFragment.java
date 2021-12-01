package com.example.fragment;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.RadioGroup;
import android.widget.Spinner;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.DialogFragment;

import com.kazi.mtaani.FilterSearchResultActivity;
import com.kazi.mtaani.R;
import com.example.adapter.NothingSelectedSpinnerAdapter;
import com.example.item.ItemCategory;
import com.example.item.ItemCity;
import com.example.util.API;
import com.example.util.Constant;
import com.example.util.NetworkUtils;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import cz.msebera.android.httpclient.Header;

public class FilterSearchFragment extends DialogFragment {

    ProgressBar progressBar;
    CardView cardView;
    Spinner spCategory, spCity, spCompanyName;
    EditText edtText;
    Button btnSubmit;
    TextView textClose;
    ArrayList<ItemCategory> mListCategory;
    ArrayList<ItemCity> mListCity;
    ArrayList<String> mCategoryName, mCityName, mCompanyName;
    RadioGroup radioGroup;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setStyle(DialogFragment.STYLE_NORMAL, R.style.Theme_AppCompat_Translucent);
        setCancelable(false);
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.filter_search_dialog, container, false);
        progressBar = rootView.findViewById(R.id.progressBar);
        spCategory = rootView.findViewById(R.id.spCategory);
        spCompanyName = rootView.findViewById(R.id.spCompanyName);
        spCity = rootView.findViewById(R.id.spCity);
        cardView = rootView.findViewById(R.id.card_view);
        edtText = rootView.findViewById(R.id.edt_name);
        btnSubmit = rootView.findViewById(R.id.btn_submit);
        textClose = rootView.findViewById(R.id.txtClose);
        radioGroup = rootView.findViewById(R.id.radioGrp);

        mListCategory = new ArrayList<>();
        mListCity = new ArrayList<>();
        mCategoryName = new ArrayList<>();
        mCityName = new ArrayList<>();
        mCompanyName = new ArrayList<>();

        textClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dismiss();
            }
        });


        btnSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String searchText = edtText.getText().toString();
                String jobType = "";
                int radioSelected = radioGroup.getCheckedRadioButtonId();
                if (radioSelected != -1) {
                    switch (radioSelected) {
                        case R.id.rdFullTime:
                            jobType = Constant.JOB_TYPE_FULL;
                            break;
                        case R.id.rdHalfTime:
                            jobType = Constant.JOB_TYPE_HALF;
                            break;
                        case R.id.rdHour:
                            jobType = Constant.JOB_TYPE_HOURLY;
                            break;
                    }
                } else {
                    jobType = "";
                }
                dismiss();
                Intent intent = new Intent(requireActivity(), FilterSearchResultActivity.class);
                intent.putExtra("categoryId", spCategory.getSelectedItemPosition() == 0 ? "" : String.valueOf(mListCategory.get(spCategory.getSelectedItemPosition() - 1).getCategoryId()));
                intent.putExtra("cityId", spCity.getSelectedItemPosition() == 0 ? "" : mListCity.get(spCity.getSelectedItemPosition() - 1).getCityId());
                intent.putExtra("companyName", spCompanyName.getSelectedItemPosition() == 0 ? "" : mCompanyName.get(spCompanyName.getSelectedItemPosition() - 1));
                intent.putExtra("searchText", searchText);
                intent.putExtra("jobType", jobType);
                startActivity(intent);

            }
        });


        if (NetworkUtils.isConnected(getActivity())) {
            getList();
        }

        return rootView;
    }

    private void getList() {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_list");
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                cardView.setVisibility(View.GONE);
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                cardView.setVisibility(View.VISIBLE);
                progressBar.setVisibility(View.GONE);

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONObject jobAppJson = mainJson.getJSONObject(Constant.ARRAY_NAME);

                    JSONArray cityArray = jobAppJson.getJSONArray("city_list");
                    if (cityArray.length() > 0) {
                        for (int i = 0; i < cityArray.length(); i++) {
                            JSONObject objJson = cityArray.getJSONObject(i);
                            ItemCity objItem = new ItemCity();
                            objItem.setCityId(objJson.getString(Constant.CITY_ID));
                            objItem.setCityName(objJson.getString(Constant.CITY_NAME));
                            mCityName.add(objJson.getString(Constant.CITY_NAME));
                            mListCity.add(objItem);
                        }
                    }


                    JSONArray categoryArray = jobAppJson.getJSONArray("cat_list");
                    if (categoryArray.length() > 0) {
                        for (int i = 0; i < categoryArray.length(); i++) {
                            JSONObject objJson = categoryArray.getJSONObject(i);
                            ItemCategory objItem = new ItemCategory();
                            objItem.setCategoryId(objJson.getInt(Constant.CATEGORY_CID));
                            objItem.setCategoryName(objJson.getString(Constant.CATEGORY_NAME));
                            objItem.setCategoryImage(objJson.getString(Constant.CATEGORY_IMAGE));
                            mCategoryName.add(objJson.getString(Constant.CATEGORY_NAME));
                            mListCategory.add(objItem);
                        }
                    }

                    JSONArray companyArray = jobAppJson.getJSONArray("company_list");
                    if (companyArray.length() > 0) {
                        for (int i = 0; i < companyArray.length(); i++) {
                            JSONObject objJson = companyArray.getJSONObject(i);
                            mCompanyName.add(objJson.getString("job_company_name"));
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }

                ArrayAdapter<String> categoryAdapter = new ArrayAdapter<>(requireActivity(),
                        android.R.layout.simple_list_item_1, mCategoryName);
                categoryAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spCategory.setAdapter(new NothingSelectedSpinnerAdapter(categoryAdapter, R.layout.sp_category, requireActivity()));

                ArrayAdapter<String> cityAdapter = new ArrayAdapter<>(requireActivity(),
                        android.R.layout.simple_list_item_1, mCityName);
                cityAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spCity.setAdapter(new NothingSelectedSpinnerAdapter(cityAdapter, R.layout.sp_city, requireActivity()));

                ArrayAdapter<String> companyAdapter = new ArrayAdapter<>(requireActivity(),
                        android.R.layout.simple_list_item_1, mCompanyName);
                companyAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                spCompanyName.setAdapter(new NothingSelectedSpinnerAdapter(companyAdapter, R.layout.sp_company, requireActivity()));
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {

            }

        });
    }
}
