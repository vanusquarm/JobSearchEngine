package com.example.util;

public class Events {
    public static class SaveJob {
        private String jobId;
        private boolean isSave;

        public String getJobId() {
            return jobId;
        }

        public void setJobId(String jobId) {
            this.jobId = jobId;
        }

        public boolean isSave() {
            return isSave;
        }

        public void setSave(boolean save) {
            isSave = save;
        }
    }
}
