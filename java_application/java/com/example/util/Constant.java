package com.example.util;

import com.kazi.mtaani.BuildConfig;

import java.io.Serializable;

public class Constant implements Serializable {

    private static final long serialVersionUID = 1L;

    public static String SERVER_URL = BuildConfig.SERVER_URL;

    public static final String IMAGE_PATH = SERVER_URL + "images/";

    public static final String API_URL = SERVER_URL + "api.php";

    public static final String INDIVIDUAL = "individual";
    public static final String COMPANY = "company";
    public static final String JOB_TYPE_HOURLY = "Hourly";
    public static final String JOB_TYPE_FULL = "Full Time";
    public static final String JOB_TYPE_HALF = "Half Time";
    public static final String MALE = "Male";
    public static final String FEMALE = "Female";

    public static final String ARRAY_NAME = "JOBS_APP";

    public static final String CATEGORY_NAME = "category_name";
    public static final String CATEGORY_CID = "cid";
    public static final String CATEGORY_IMAGE = "category_image";

    public static final String CITY_ID = "c_id";
    public static final String CITY_NAME = "city_name";


    public static final String JOB_ID = "id";
    public static final String JOB_NAME = "job_name";
    public static final String JOB_DESIGNATION = "job_designation";
    public static final String JOB_DESC = "job_desc";
    public static final String JOB_SALARY = "job_salary";
    public static final String JOB_COMPANY_NAME = "job_company_name";
    public static final String JOB_SITE = "job_company_website";
    public static final String JOB_PHONE_NO = "job_phone_number";
    public static final String JOB_MAIL = "job_mail";
    public static final String JOB_VACANCY = "job_vacancy";
    public static final String JOB_ADDRESS = "job_address";
    public static final String JOB_QUALIFICATION = "job_qualification";
    public static final String JOB_SKILL = "job_skill";
    public static final String JOB_IMAGE = "job_image";
    public static final String JOB_DATE = "job_date";
    public static final String JOB_APPLY = "job_apply_total";
    public static final String JOB_ALREADY_SAVED = "already_saved";
    public static final String JOB_APPLIED_DATE = "apply_date";
    public static final String JOB_APPLIED_SEEN = "seen";
    public static final String JOB_TYPE = "job_type";
    public static final String JOB_WORK_DAY = "job_work_day";
    public static final String JOB_WORK_TIME = "job_work_time";
    public static final String JOB_EXP = "job_experince";
    public static final String JOB_FAVOURITE = "is_favourite";

    public static final String APP_NAME = "app_name";
    public static final String APP_IMAGE = "app_logo";
    public static final String APP_VERSION = "app_version";
    public static final String APP_AUTHOR = "app_author";
    public static final String APP_CONTACT = "app_contact";
    public static final String APP_EMAIL = "app_email";
    public static final String APP_WEBSITE = "app_website";
    public static final String APP_DESC = "app_description";
    public static final String APP_PRIVACY_POLICY = "app_privacy_policy";

    public static final String USER_NAME = "name";
    public static final String USER_ID = "user_id";
    public static final String USER_EMAIL = "email";
    public static final String USER_PHONE = "phone";
    public static final String USER_CITY = "city";
    public static final String USER_ADDRESS = "address";
    public static final String USER_TYPE = "user_type";
    public static final String USER_IMAGE = "user_image";
    public static final String USER_RESUME = "user_resume";
    public static final String USER_TOTAL_APPLIED_JOB = "total_apply_job";
    public static final String USER_TOTAL_SAVED_JOB = "total_saved_job";
    public static final String USER_CURRENT_COMPANY = "current_company_name";
    public static final String USER_EXPERIENCE = "experiences";
    public static final String USER_SKILLS = "skills";
    public static final String USER_GENDER = "gender";
    public static final String USER_DOB = "date_of_birth";

    public static int GET_SUCCESS_MSG;
    public static final String MSG = "msg";
    public static final String SUCCESS = "success";
    public static final String STATUS = "status";

    public static int AD_COUNT = 0;
    public static int AD_COUNT_SHOW;

    public static boolean isBanner = false, isInterstitial = false;
    public static boolean isAdMobBanner = true, isAdMobInterstitial = true;
    public static String bannerId, interstitialId, adMobPublisherId;

    public static boolean isAppUpdate = false, isAppUpdateCancel = false;
    public static String appUpdateVersion, appUpdateUrl, appUpdateDesc;
}
