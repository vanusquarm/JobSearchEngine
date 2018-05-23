package com.julian.jobsearch.data.model;

import android.arch.persistence.room.Entity;
import android.arch.persistence.room.Ignore;
import android.arch.persistence.room.PrimaryKey;
import android.support.annotation.NonNull;

import java.util.UUID;

/**
 *
 */

@Entity
public class JobPost {
    @PrimaryKey
    @NonNull
    private String uuid;
    private String title;
    private String description;
    private String educationalRequirements;
    private String hours;
    private String offerClosingDate;
    private String employerContact;
    private String employerUuid;

    public JobPost(@NonNull String uuid, String title, String description, String educationalRequirements, String hours, String offerClosingDate, String employerContact, String employerUuid) {
        this.uuid = uuid;
        this.title = title;
        this.description = description;
        this.educationalRequirements = educationalRequirements;
        this.hours = hours;
        this.offerClosingDate = offerClosingDate;
        this.employerContact = employerContact;
        this.employerUuid = employerUuid;
    }

    @Ignore
    public JobPost(String title, String description, String educationalRequirements, String hours, String offerClosingDate) {
        this.uuid = UUID.randomUUID().toString();
        this.title = title;
        this.description = description;
        this.educationalRequirements = educationalRequirements;
        this.hours = hours;
        this.offerClosingDate = offerClosingDate;
    }

    public String getEmployerContact() {
        return employerContact;
    }

    @NonNull
    public String getUuid() {
        return uuid;
    }

    public String getTitle() {
        return title;
    }

    public String getDescription() {
        return description;
    }

    public String getEducationalRequirements() {
        return educationalRequirements;
    }

    public String getHours() {
        return hours;
    }

    public String getOfferClosingDate() {
        return offerClosingDate;
    }

    public String getEmployerUuid() {
        return employerUuid;
    }
}
