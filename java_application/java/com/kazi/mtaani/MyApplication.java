package com.kazi.mtaani;


import android.app.Application;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;

import androidx.multidex.MultiDex;

import com.facebook.ads.AudienceNetworkAds;
import com.onesignal.OSNotificationOpenResult;
import com.onesignal.OneSignal;

import org.json.JSONObject;

import io.github.inflationx.calligraphy3.CalligraphyConfig;
import io.github.inflationx.calligraphy3.CalligraphyInterceptor;
import io.github.inflationx.viewpump.ViewPump;


public class MyApplication extends Application {

    private static MyApplication mInstance;
    public SharedPreferences preferences;
    public String prefName = "jobsapp";

    public MyApplication() {
        mInstance = this;
    }

    @Override
    public void onCreate() {
        super.onCreate();
        ViewPump.init(ViewPump.builder()
                .addInterceptor(new CalligraphyInterceptor(
                        new CalligraphyConfig.Builder()
                                .setDefaultFontPath("fonts/custom.otf")
                                .setFontAttrId(R.attr.fontPath)
                                .build()))
                .build());

        OneSignal.startInit(this)
                .setNotificationOpenedHandler(new ExampleNotificationOpenedHandler())
                .inFocusDisplaying(OneSignal.OSInFocusDisplayOption.Notification)
                .init();

        AudienceNetworkAds.initialize(this);
    }

    public static synchronized MyApplication getInstance() {
        return mInstance;
    }

    @Override
    protected void attachBaseContext(Context base) {
        super.attachBaseContext(base);
        MultiDex.install(this);
    }


    public void saveIsLogin(boolean flag) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putBoolean("IsLoggedIn", flag);
        editor.apply();
    }

    public boolean getIsLogin() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getBoolean("IsLoggedIn", false);
    }

    public void saveIsFirstTime(boolean flag) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putBoolean("IsFirstTime", flag);
        editor.apply();
    }

    public boolean getIsFirstTime() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getBoolean("IsFirstTime", false);
    }

    public void saveIsJobProvider(boolean flag) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putBoolean("IsJobProvider", flag);
        editor.apply();
    }

    public boolean getIsJobProvider() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getBoolean("IsJobProvider", false);
    }

    public void saveLogin(String user_id, String user_name, String email) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString("user_id", user_id);
        editor.putString("user_name", user_name);
        editor.putString("email", email);
        editor.apply();
    }

    public void saveUserImage(String userImage) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putString("image", userImage);
        editor.apply();
    }

    public String getUserId() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getString("user_id", "");
    }

    public String getUserName() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getString("user_name", "");
    }

    public String getUserEmail() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getString("email", "");
    }

    public String getUserImage() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getString("image", "");
    }

    public void saveIsNotification(boolean flag) {
        preferences = this.getSharedPreferences(prefName, 0);
        SharedPreferences.Editor editor = preferences.edit();
        editor.putBoolean("IsNotification", flag);
        editor.apply();
    }

    public boolean getNotification() {
        preferences = this.getSharedPreferences(prefName, 0);
        return preferences.getBoolean("IsNotification", true);
    }

    private class ExampleNotificationOpenedHandler implements OneSignal.NotificationOpenedHandler {
        @Override
        public void notificationOpened(OSNotificationOpenResult result) {
            JSONObject data = result.notification.payload.additionalData;
            String customKey;
            String isExternalLink;
            if (data != null) {
                customKey = data.optString("job_id", null);
                isExternalLink = data.optString("external_link", null);
                if (customKey != null) {
                    if (!customKey.equals("0")) {
                        Intent intent = new Intent(MyApplication.this, JobDetailsActivity.class);
                        intent.putExtra("Id", customKey);
                        intent.putExtra("isNotification", true);
                        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                        startActivity(intent);
                    } else {
                        if (!isExternalLink.equals("false")) {
                            Intent intent = new Intent(Intent.ACTION_VIEW,
                                    Uri.parse(isExternalLink));
                            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                            startActivity(intent);
                        } else {
                            Intent intent = new Intent(MyApplication.this, SplashActivity.class);
                            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                            startActivity(intent);
                        }
                    }
                }
            }
        }
    }
}
