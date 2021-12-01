package com.example.util;

import android.app.ProgressDialog;
import android.content.Context;
import android.widget.Toast;

import com.kazi.mtaani.MyApplication;
import com.kazi.mtaani.R;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import cz.msebera.android.httpclient.Header;

public class SaveJob {

    private ProgressDialog pDialog;
    private Context mContext;

    public SaveJob(Context context) {
        this.mContext = context;
        pDialog = new ProgressDialog(mContext);
    }

    public void userSave(final String jobId, final SaveClickListener saveClickListener) {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "saved_job_add");
        jsObj.addProperty("saved_job_id", jobId);
        jsObj.addProperty("saved_user_id", MyApplication.getInstance().getUserId());
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
                    if (jsonArray.length() > 0) {
                        for (int i = 0; i < jsonArray.length(); i++) {
                            objJson = jsonArray.getJSONObject(i);
                            Toast.makeText(mContext, objJson.getString(Constant.MSG), Toast.LENGTH_SHORT).show();
                            saveClickListener.onItemClick(objJson.getString(Constant.SUCCESS).equals("1"), objJson.getString(Constant.MSG));
                            Events.SaveJob saveJob = new Events.SaveJob();
                            saveJob.setJobId(jobId);
                            saveJob.setSave(objJson.getString(Constant.SUCCESS).equals("1"));
                            GlobalBus.getBus().post(saveJob);
                        }
                    }
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

    private void showProgressDialog() {
        pDialog.setMessage(mContext.getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(false);
        pDialog.show();
    }

    private void dismissProgressDialog() {
        if (pDialog != null && pDialog.isShowing())
            pDialog.dismiss();
    }
}
