#!/bin/sh

# Author: wangdiwen
# Date  : 2013-10
# Note  : This bash script is for check 'wx_categoty_area' db table

# use the root to exec this script
user=`whoami`
[ ! "$user" = "root" ] && { echo "Permission deny, use root to exec it!..."; exit 1; }

# switch to www to use php program
su - www -c "/alidata/server/php/bin/php -f /alidata/www/creamnote/auto_manager/bin/init_area_json.php"
