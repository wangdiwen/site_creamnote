#!/bin/sh

user=`whoami`
if [ "$user" != "root" ];then
	echo 'permission deny, use root!'
	exit 1
fi

if [ $# -ne 1 ];then
	echo './clear_by_month.sh [2013-07]'
	exit 1
fi

ls . | grep "$1" | xargs shred -z -u
if [ $? -eq 0 ];then
	echo 'success'
else
	echo 'failed'
	exit 1
fi
