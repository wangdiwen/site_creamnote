#!/bin/sh

# Author: wangdiwen
# Date  : 2013-12-07
# Note  : This bash script is for exec the auto manager task,
#         It will invoke the php program to exec the auto task model.

# use the root to exec this script
user=`whoami`
[ ! "$user" = "root" ] && { echo "Permission deny, use root to exec it! ..."; exit 1; }

# switch to www to use php program
su - www -c "/alidata/server/php/bin/php -f /alidata/www/creamnote/auto_manager/bin/update_user_email.php"
