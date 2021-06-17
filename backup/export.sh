#!/bin/bash

collections=(audits blacklists categories districts failed_jobs files logs migrations notes orders password_resets permission_groups permissions plans posts provinces reports roles settings subscriptions tracking_posts user_subscriptions users wards whitelists sms_templates sms_histories keywords)

for collect in $( eval echo ${collections[*]})
do
    mongoexport --db estate -c $collect --out e/${collect}.json --forceTableScan
done
