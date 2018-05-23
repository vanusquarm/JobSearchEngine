package com.julian.jobsearch.data.model;

/**
 *
 */

public class Employer extends User {
    private String companyName;
    private String companyType;

    public Employer(String email, String firstName, String lastName, String username, String password, String telephone, String companyName, String companyType) {
        super(email, firstName, lastName, username, password, telephone);
        this.companyName = companyName;
        this.companyType = companyType;
    }

    public String getCompanyName() {
        return companyName;
    }

    public String getCompanyType() {
        return companyType;
    }
}
