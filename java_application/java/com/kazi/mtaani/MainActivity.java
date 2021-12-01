package com.kazi.mtaani;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import com.example.fragment.AppliedJobFragment;
import com.example.fragment.CategoryFragment;
import com.example.fragment.FilterSearchFragment;
import com.example.fragment.HomeFragment;
import com.example.fragment.LatestFragment;
import com.example.fragment.SavedJobFragment;
import com.example.fragment.SettingFragment;
import com.example.util.BannerAds;
import com.example.util.Constant;
import com.example.util.IsRTL;
import com.google.android.material.navigation.NavigationView;
import com.ixidev.gdpr.GDPRChecker;
import com.squareup.picasso.Picasso;

import de.hdodenhof.circleimageview.CircleImageView;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;


public class MainActivity extends AppCompatActivity {

    private DrawerLayout drawerLayout;
    private FragmentManager fragmentManager;
    NavigationView navigationView;
    Toolbar toolbar;
    MyApplication MyApp;
    boolean doubleBackToExitPressedOnce = false;
    String versionName;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        IsRTL.ifSupported(this);
        toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        MyApp = MyApplication.getInstance();
        fragmentManager = getSupportFragmentManager();
        navigationView = findViewById(R.id.navigation_view);
        drawerLayout = findViewById(R.id.drawer_layout);

        try {
            versionName = getPackageManager()
                    .getPackageInfo(getPackageName(), 0).versionName;
        } catch (PackageManager.NameNotFoundException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        new GDPRChecker()
                .withContext(MainActivity.this)
                .withPrivacyUrl(getString(R.string.privacy_url)) // your privacy url
                .withPublisherIds(Constant.adMobPublisherId) // your admob account Publisher id
                .withTestMode("9424DF76F06983D1392E609FC074596C") // remove this on real project
                .check();

        LinearLayout mAdViewLayout = findViewById(R.id.adView);
        BannerAds.ShowBannerAds(this, mAdViewLayout);

        HomeFragment homeFragment = new HomeFragment();
        loadFrag(homeFragment, getString(R.string.menu_home), fragmentManager);

        navigationView.setNavigationItemSelectedListener(new NavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                drawerLayout.closeDrawers();
                switch (item.getItemId()) {
                    case R.id.menu_go_home:
                        HomeFragment homeFragment = new HomeFragment();
                        loadFrag(homeFragment, getString(R.string.menu_home), fragmentManager);
                        return true;
                    case R.id.menu_go_latest:
                        LatestFragment latestFragment = new LatestFragment();
                        Bundle bundle = new Bundle();
                        bundle.putBoolean("isLatest", true);
                        latestFragment.setArguments(bundle);
                        loadFrag(latestFragment, getString(R.string.latest), fragmentManager);
                        return true;
                    case R.id.menu_go_category:
                        CategoryFragment categoryFragment = new CategoryFragment();
                        loadFrag(categoryFragment, getString(R.string.menu_category), fragmentManager);
                        return true;
                    case R.id.menu_go_saved:
                        SavedJobFragment savedJobFragment = new SavedJobFragment();
                        loadFrag(savedJobFragment, getString(R.string.menu_favourite), fragmentManager);
                        return true;
                    case R.id.menu_go_applied:
                        AppliedJobFragment appliedJobFragment = new AppliedJobFragment();
                        loadFrag(appliedJobFragment, getString(R.string.menu_applied_job), fragmentManager);
                        return true;
                    case R.id.menu_go_setting:
                        SettingFragment settingFragment = new SettingFragment();
                        loadFrag(settingFragment, getString(R.string.menu_setting), fragmentManager);
                        return true;
                    case R.id.menu_go_profile:
                        Intent profile = new Intent(MainActivity.this, EditProfileActivity.class);
                        profile.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(profile);
                        return true;
                    case R.id.menu_go_logout:
                        Logout();
                        return true;
                    case R.id.menu_go_login:
                        Intent intent = new Intent(getApplicationContext(), SignInActivity.class);
                        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(intent);
                        finish();
                        return true;
                }
                return false;
            }
        });

        ActionBarDrawerToggle actionBarDrawerToggle = new ActionBarDrawerToggle(this, drawerLayout, toolbar, R.string.drawer_open, R.string.drawer_close) {
            @Override
            public void onDrawerClosed(View drawerView) {
                super.onDrawerClosed(drawerView);
            }

            @Override
            public void onDrawerOpened(View drawerView) {
                super.onDrawerOpened(drawerView);
            }
        };

        drawerLayout.addDrawerListener(actionBarDrawerToggle);
        actionBarDrawerToggle.syncState();
        toolbar.setNavigationIcon(R.drawable.ic_side_nav);

        if (!versionName.equals(Constant.appUpdateVersion) && Constant.isAppUpdate) {
            newUpdateDialog();
        }
    }

    public void loadFrag(Fragment f1, String name, FragmentManager fm) {
        for (int i = 0; i < fm.getBackStackEntryCount(); ++i) {
            fm.popBackStack();
        }
        FragmentTransaction ft = fm.beginTransaction();
        ft.replace(R.id.Container, f1, name);
        ft.commit();
        setToolbarTitle(name);
    }

    public void setToolbarTitle(String Title) {
        if (getSupportActionBar() != null) {
            getSupportActionBar().setTitle(Title);
        }
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.menu_main, menu);
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case R.id.search:
                FilterSearchFragment filterSearchFragment = new FilterSearchFragment();
                filterSearchFragment.show(fragmentManager, "Square");
                break;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }


    private void Logout() {
        new AlertDialog.Builder(MainActivity.this)
                .setTitle(getString(R.string.menu_logout))
                .setMessage(getString(R.string.logout_msg))
                .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        MyApp.saveIsLogin(false);
                        Intent intent = new Intent(getApplicationContext(), SignInActivity.class);
                        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(intent);
                        finish();
                    }
                })
                .setNegativeButton(android.R.string.no, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // do nothing
                    }
                })
                //  .setIcon(R.drawable.ic_logout)
                .show();
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (navigationView != null) {
            setHeader();
        }
    }

    private void setHeader() {

        if (MyApp.getIsLogin()) {
            navigationView.getMenu().findItem(R.id.menu_go_login).setVisible(false);
            navigationView.getMenu().findItem(R.id.menu_go_profile).setVisible(true);
            navigationView.getMenu().findItem(R.id.menu_go_logout).setVisible(true);
            navigationView.getMenu().findItem(R.id.menu_go_saved).setVisible(true);
            navigationView.getMenu().findItem(R.id.menu_go_applied).setVisible(true);

            View header = navigationView.getHeaderView(0);
            TextView txtHeaderName = header.findViewById(R.id.header_name);
            TextView txtHeaderEmail = header.findViewById(R.id.header_email);
            final CircleImageView imageUser = header.findViewById(R.id.header_image);
            txtHeaderName.setText(MyApp.getUserName());
            txtHeaderEmail.setText(MyApp.getUserEmail());
            Log.e("image", MyApp.getUserImage());
            if (!MyApp.getUserImage().isEmpty()) {
                Picasso.get().load(MyApp.getUserImage()).into(imageUser);
            }
        } else {
            navigationView.getMenu().findItem(R.id.menu_go_login).setVisible(true);
            navigationView.getMenu().findItem(R.id.menu_go_profile).setVisible(false);
            navigationView.getMenu().findItem(R.id.menu_go_logout).setVisible(false);
            navigationView.getMenu().findItem(R.id.menu_go_saved).setVisible(false);
            navigationView.getMenu().findItem(R.id.menu_go_applied).setVisible(false);
        }
    }

    @Override
    public void onBackPressed() {
        if (drawerLayout.isDrawerOpen(GravityCompat.START)) {
            drawerLayout.closeDrawer(GravityCompat.START);
        } else if (fragmentManager.getBackStackEntryCount() != 0) {
            String tag = fragmentManager.getFragments().get(fragmentManager.getBackStackEntryCount() - 1).getTag();
            setToolbarTitle(tag);
            super.onBackPressed();
        } else {
            if (doubleBackToExitPressedOnce) {
                super.onBackPressed();
                return;
            }

            this.doubleBackToExitPressedOnce = true;
            Toast.makeText(this, getString(R.string.back_key), Toast.LENGTH_SHORT).show();

            new Handler().postDelayed(new Runnable() {
                @Override
                public void run() {
                    doubleBackToExitPressedOnce = false;
                }
            }, 2000);
        }
    }

    private void newUpdateDialog() {
        AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);
        builder.setTitle(getString(R.string.app_update_title));
        builder.setCancelable(false);
        builder.setMessage(Constant.appUpdateDesc);
        builder.setPositiveButton(getString(R.string.app_update_btn), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
                startActivity(new Intent(
                        Intent.ACTION_VIEW,
                        Uri.parse(Constant.appUpdateUrl)));
            }
        });
        if (Constant.isAppUpdateCancel) {
            builder.setNegativeButton(getString(R.string.app_cancel_btn), new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int which) {

                }
            });
        }
        builder.setIcon(R.mipmap.ic_launcher_app);
        builder.show();
    }
}
