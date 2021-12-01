package com.example.util;

import android.content.Context;
import android.os.Bundle;

import com.facebook.ads.Ad;
import com.facebook.ads.AdError;
import com.facebook.ads.CacheFlag;
import com.facebook.ads.InterstitialAdListener;
import com.google.ads.mediation.admob.AdMobAdapter;
import com.google.android.gms.ads.AdListener;
import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.InterstitialAd;
import com.ixidev.gdpr.GDPRChecker;

public class PopUpAds {
    public static void showInterstitialAds(Context context, final int adapterPosition, final RvOnClickListener clickListener) {
        if (Constant.isInterstitial) {
            Constant.AD_COUNT += 1;
            if (Constant.AD_COUNT == Constant.AD_COUNT_SHOW) {
                if (Constant.isAdMobInterstitial) {
                    final InterstitialAd mInterstitial = new InterstitialAd(context);
                    mInterstitial.setAdUnitId(Constant.interstitialId);
                    GDPRChecker.Request request = GDPRChecker.getRequest();
                    AdRequest.Builder builder = new AdRequest.Builder();
                    if (request == GDPRChecker.Request.NON_PERSONALIZED) {
                        Bundle extras = new Bundle();
                        extras.putString("npa", "1");
                        builder.addNetworkExtrasBundle(AdMobAdapter.class, extras);
                    }
                    mInterstitial.loadAd(builder.build());
                    Constant.AD_COUNT = 0;
                    mInterstitial.setAdListener(new AdListener() {
                        @Override
                        public void onAdLoaded() {
                            super.onAdLoaded();
                            mInterstitial.show();
                        }

                        @Override
                        public void onAdClosed() {
                            clickListener.onItemClick(adapterPosition);
                            super.onAdClosed();
                        }

                        @Override
                        public void onAdFailedToLoad(int i) {
                            clickListener.onItemClick(adapterPosition);
                            super.onAdFailedToLoad(i);
                        }
                    });
                } else {
                    Constant.AD_COUNT = 0;
                    final com.facebook.ads.InterstitialAd interstitialAd = new com.facebook.ads.InterstitialAd(context, Constant.interstitialId);
                    InterstitialAdListener interstitialAdListener = new InterstitialAdListener() {
                        @Override
                        public void onInterstitialDisplayed(Ad ad) {

                        }

                        @Override
                        public void onInterstitialDismissed(Ad ad) {
                            clickListener.onItemClick(adapterPosition);
                        }

                        @Override
                        public void onError(Ad ad, AdError adError) {
                            clickListener.onItemClick(adapterPosition);
                        }

                        @Override
                        public void onAdLoaded(Ad ad) {
                            interstitialAd.show();
                        }

                        @Override
                        public void onAdClicked(Ad ad) {

                        }

                        @Override
                        public void onLoggingImpression(Ad ad) {

                        }
                    };
                    com.facebook.ads.InterstitialAd.InterstitialLoadAdConfig loadAdConfig = interstitialAd.buildLoadAdConfig().withAdListener(interstitialAdListener).withCacheFlags(CacheFlag.ALL).build();
                    interstitialAd.loadAd(loadAdConfig);
                }
            } else {
                clickListener.onItemClick(adapterPosition);
            }
        } else {
            clickListener.onItemClick(adapterPosition);
        }
    }
}
