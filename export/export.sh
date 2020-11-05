#!/bin/bash

collections=(audits blacklists categories dictricts failed_jobs files logs migrations notes orders password_resets permission_groups permission plans posts provinces reports roles settings subscriptions tracking_posts user_subscriptions users wards whitelist)

for collect in $( eval echo ${collections[*]})
do
    mongoexport --db estate -c $collect --out e/newdbexport.json --forceTableScan
done
