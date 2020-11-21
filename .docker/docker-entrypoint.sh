#!/bin/sh
# set -e

echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/www-data

touch /etc/supervisord.conf

echo "[supervisord]" >> /etc/supervisord.conf
echo "nodaemon=true" >> /etc/supervisord.conf
echo "" >> /etc/supervisord.conf
echo "[include]" >> /etc/supervisord.conf
echo "files = /etc/supervisor.d/*.conf" >> /etc/supervisord.conf


exec "$@"

# exec supervisord -n -c /etc/supervisord.conf
