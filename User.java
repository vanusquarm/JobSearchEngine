package com.julian.jobsearch.data.model;

/**
 *
 */

public class User {
    private String uuid;
    private String email;
    private String firstName;
    private String lastName;
    private String username;
    private String password;
    private String telephone;

    public User(String email, String firstName, String lastName, String username, String password, String telephone) {
        this.email = email;
        this.firstName = firstName;
        this.lastName = lastName;
        this.username = username;
        this.password = password;
        this.telephone = telephone;
    }

    public String getUuid() {
        return uuid;
    }

    public String getEmail() {
        return email;
    }

    public String getFirstName() {
        return firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getTelephone() {
        return telephone;
    }
}
