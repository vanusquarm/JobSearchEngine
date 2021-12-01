package com.kazi.mtaani;


import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
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

import com.example.fragment.JobProviderFragment;
import com.example.fragment.SettingFragment;
import com.example.util.BannerAds;
import com.example.util.IsRTL;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.navigation.NavigationView;
import com.squareup.picasso.Picasso;

import de.hdodenhof.circleimageview.CircleImageView;
import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class JobProviderMainActivity extends AppCompatActivity {
    private DrawerLayout drawerLayout;
    private FragmentManager fragmentManager;
    NavigationView navigationView;
    Toolbar toolbar;
    MyApplication MyApp;
    FloatingActionButton addJob;
    boolean doubleBackToExitPressedOnce = false;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_provider_main);
        IsRTL.ifSupported(this);
        toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        MyApp = MyApplication.getInstance();
        fragmentManager = getSupportFragmentManager();
        navigationView = findViewById(R.id.navigation_view);
        drawerLayout = findViewById(R.id.drawer_layout);
        addJob = findViewById(R.id.fab);

        LinearLayout mAdViewLayout = findViewById(R.id.adView);
        BannerAds.ShowBannerAds(this, mAdViewLayout);

        JobProviderFragment homeFragment = new JobProviderFragment();
        fragmentManager.beginTransaction().replace(R.id.Container, homeFragment).commit();


        addJob.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(JobProviderMainActivity.this, AddJobActivity.class);
                startActivity(intent);
            }
        });

        navigationView.setNavigationItemSelectedListener(new NavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                drawerLayout.closeDrawers();
                switch (item.getItemId()) {
                    case R.id.menu_go_job:
                        JobProviderFragment homeFragment = new JobProviderFragment();
                        loadFrag(homeFragment, getString(R.string.job_list), fragmentManager);
                        return true;
                    case R.id.menu_go_profile:
                        Intent profile = new Intent(JobProviderMainActivity.this, EditProfileJobProviderActivity.class);
                        profile.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(profile);
                        return true;
                    case R.id.menu_go_setting:
                        SettingFragment settingFragment = new SettingFragment();
                        loadFrag(settingFragment, getString(R.string.menu_setting), fragmentManager);
                        return true;
                    case R.id.menu_go_logout:
                        Logout();
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

        if (!MyApp.getIsLogin()) {
            navigationView.getMenu().findItem(R.id.menu_go_profile).setVisible(false);
            navigationView.getMenu().findItem(R.id.menu_go_logout).setVisible(false);
        }
        setHeader();
        drawerLayout.addDrawerListener(actionBarDrawerToggle);
        actionBarDrawerToggle.syncState();
        toolbar.setNavigationIcon(R.drawable.ic_side_nav);
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

    private void Logout() {
        new AlertDialog.Builder(JobProviderMainActivity.this)
                .setTitle(getString(R.string.menu_logout))
                .setMessage(getString(R.string.logout_msg))
                .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        MyApp.saveIsLogin(false);
                        MyApp.saveIsJobProvider(false);
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

    private void setHeader() {
        if (MyApp.getIsLogin()) {
            View header = navigationView.getHeaderView(0);
            TextView txtHeaderName = header.findViewById(R.id.header_name);
            TextView txtHeaderEmail = header.findViewById(R.id.header_email);
            final CircleImageView imageUser = header.findViewById(R.id.header_image);
            txtHeaderName.setText(MyApp.getUserName());
            txtHeaderEmail.setText(MyApp.getUserEmail());

            if (!MyApp.getUserImage().isEmpty()) {
                Picasso.get().load(MyApp.getUserImage()).into(imageUser);
            }
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
}
