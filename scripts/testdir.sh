#!/bin/bash

echo ""
TPATH=../../storage/app/audio
[ -d $TPATH ] || mkdir -p $TPATH
if [ ! -d $TPATH ] ; then
printf "The "$TPATH" directory does not exist.\n"
printf "Make sure the correct owner and permissions are set on the storage/app directory.\n"
printf "Currently they are: \t" && ls -las ../../storage | grep app
printf "After the owner and permissions are verified run 'make test' again."
else
printf "Make sure the correct owner and permissions are set on the storage/app/audio directory.\n"
printf "Currently they are: \t" && ls -las ../../storage/app | grep audio
printf "\nThe owner should be set to the web servers user, e.g. www-data for Apache2\n"
fi
