package com.example.item;

import java.io.Serializable;

public class ItemJob implements Serializable {

    private String id;
    private String jobName;
    private String jobDesignation;
    private String jobDesc;
    private String jobSalary;
    private String jobCompanyName;
    private String jobCompanyWebsite;
    private String jobPhoneNumber;
    private String jobMail;
    private String jobVacancy;
    private String jobAddress;
    private String jobQualification;
    private String jobSkill;
    private String jobImage;
    private String jobDate;
    private String jobApplyTotal;
    private String jobCategoryName;
    private String jobAppliedDate;
    private String jobType;
    private String jobWorkDay;
    private String jobWorkTime;
    private String jobExperience;
    private boolean isJobSeen = false;
    private boolean isJobFavourite = false;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public void setJobName(String jobName) {
        this.jobName = jobName;
    }

    public String getJobName() {
        return jobName;
    }

    public String getJobDesignation() {
        return jobDesignation;
    }

    public void setJobDesignation(String jobDesignation) {
        this.jobDesignation = jobDesignation;
    }

    public String getJobDesc() {
        return jobDesc;
    }

    public void setJobDesc(String jobDesc) {
        this.jobDesc = jobDesc;
    }

    public String getJobSalary() {
        return jobSalary;
    }

    public void setJobSalary(String jobSalary) {
        this.jobSalary = jobSalary;
    }

    public String getJobCompanyName() {
        return jobCompanyName;
    }

    public void setJobCompanyName(String jobCompanyName) {
        this.jobCompanyName = jobCompanyName;
    }

    public String getJobCompanyWebsite() {
        return jobCompanyWebsite;
    }

    public void setJobCompanyWebsite(String jobCompanyWebsite) {
        this.jobCompanyWebsite = jobCompanyWebsite;
    }

    public String getJobPhoneNumber() {
        return jobPhoneNumber;
    }

    public void setJobPhoneNumber(String jobPhoneNumber) {
        this.jobPhoneNumber = jobPhoneNumber;
    }

    public String getJobMail() {
        return jobMail;
    }

    public void setJobMail(String jobMail) {
        this.jobMail = jobMail;
    }

    public String getJobVacancy() {
        return jobVacancy;
    }

    public void setJobVacancy(String jobVacancy) {
        this.jobVacancy = jobVacancy;
    }

    public String getJobAddress() {
        return jobAddress;
    }

    public void setJobAddress(String jobAddress) {
        this.jobAddress = jobAddress;
    }

    public String getJobQualification() {
        return jobQualification;
    }

    public void setJobQualification(String jobQualification) {
        this.jobQualification = jobQualification;
    }

    public String getJobSkill() {
        return jobSkill;
    }

    public void setJobSkill(String jobSkill) {
        this.jobSkill = jobSkill;
    }

    public String getJobImage() {
        return jobImage;
    }

    public void setJobImage(String jobImage) {
        this.jobImage = jobImage;
    }

    public String getJobDate() {
        return jobDate;
    }

    public void setJobDate(String jobDate) {
        this.jobDate = jobDate;
    }

    public String getJobApplyTotal() {
        return jobApplyTotal;
    }

    public void setJobApplyTotal(String jobApplyTotal) {
        this.jobApplyTotal = jobApplyTotal;
    }

    public String getJobCategoryName() {
        return jobCategoryName;
    }

    public void setJobCategoryName(String jobCategoryName) {
        this.jobCategoryName = jobCategoryName;
    }

    public String getJobAppliedDate() {
        return jobAppliedDate;
    }

    public void setJobAppliedDate(String jobAppliedDate) {
        this.jobAppliedDate = jobAppliedDate;
    }

    public boolean isJobSeen() {
        return isJobSeen;
    }

    public void setJobSeen(boolean jobSeen) {
        isJobSeen = jobSeen;
    }

    public String getJobType() {
        return jobType;
    }

    public void setJobType(String jobType) {
        this.jobType = jobType;
    }

    public boolean isJobFavourite() {
        return isJobFavourite;
    }

    public void setJobFavourite(boolean jobFavourite) {
        isJobFavourite = jobFavourite;
    }

    public String getJobWorkDay() {
        return jobWorkDay;
    }

    public void setJobWorkDay(String jobWorkDay) {
        this.jobWorkDay = jobWorkDay;
    }

    public String getJobWorkTime() {
        return jobWorkTime;
    }

    public void setJobWorkTime(String jobWorkTime) {
        this.jobWorkTime = jobWorkTime;
    }

    public String getJobExperience() {
        return jobExperience;
    }

    public void setJobExperience(String jobExperience) {
        this.jobExperience = jobExperience;
    }
}
