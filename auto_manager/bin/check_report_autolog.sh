#!/bin/sh

# Author: wangdiwen
# Date  : 2013-12-07
# Note  : This bash script is for exec the auto manager task,
#         It will invoke the php program to exec the auto task model.

# use the root to exec this script
user=`whoami`
[ ! "$user" = "diwen" ] && { echo "Permission deny, use diwen to exec it! ..."; exit 1; }

# switch to www to use php program
/alidata/server/php/bin/php -f /alidata/www/creamnote/auto_manager/bin/report_autolog.php
