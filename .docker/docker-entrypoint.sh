#!/bin/sh
# set -e

echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/www-data

exec "$@"

# exec supervisord -n -c /etc/supervisord.conf
