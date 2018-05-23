package com.julian.jobsearch.ui.home.profile;


import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.Snackbar;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.julian.jobsearch.JobSearchApp;
import com.julian.jobsearch.R;
import com.julian.jobsearch.data.model.Employer;
import com.julian.jobsearch.data.remote.SessionManager;
import com.julian.jobsearch.ui.edit.EditEmployerActivity;
import com.julian.jobsearch.ui.edit.EditUserActivity;
import com.julian.jobsearch.ui.edit.editempActivity;

import javax.inject.Inject;

/**
 *
 */
public class ProfileFragment extends Fragment {
    @Inject
    SessionManager sessionManager;
    private TextView nameTextView;
    private TextView professionTextView;
    private TextView usernameTextView;
    private TextView emailTextView;
    private TextView contactTextView;

    public ProfileFragment() {
        // Required empty public constructor
    }

    public static ProfileFragment newInstance() {
        ProfileFragment fragment = new ProfileFragment();
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        ((JobSearchApp) getActivity().getApplication()).getAppComponent().inject(this);

    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_profile, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        nameTextView = view.findViewById(R.id.name_text_view);
        professionTextView = view.findViewById(R.id.profession_text_view);
        usernameTextView = view.findViewById(R.id.username_text_view);
        emailTextView = view.findViewById(R.id.email_text_view);
        contactTextView = view.findViewById(R.id.contact_text_view);

        try {
            Employer loggedInUser = sessionManager.getLoggedInUser();
            nameTextView.setText(getString(R.string.interpolated_name, loggedInUser.getFirstName(), loggedInUser.getLastName()));
            professionTextView.setText(loggedInUser.getCompanyType());
            usernameTextView.setText(loggedInUser.getUsername());
            emailTextView.setText(loggedInUser.getEmail());
            contactTextView.setText(loggedInUser.getTelephone());
        } catch (Exception e) {
            e.printStackTrace();
            Snackbar.make(nameTextView, "An error occured while trying to display user profile", Snackbar.LENGTH_LONG).show();
        }
//
//        public void goToUserEditProf(View view){
//            Employer loggedInUser =sessionManager.getLoggedInUser();
//            if (loggedInUser != null && loggedInUser.getCompanyName() == null){
//                Intent intent = new Intent(getActivity(), EditUserActivity.class);
//                startActivity(intent);
//            }else{
//                Intent intent = new Intent(getActivity(), editempActivity.class);
//                startActivity(intent);
//            }
//        }


    }



}
