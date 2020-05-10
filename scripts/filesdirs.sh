#!/bin/bash

RESULT=1
if [ -d "../../storage" ] ; then
printf "\nAttempting to create the storage/app/audio directory\n"
if [ ! -d "../../storage/app/audio" ] ; then
RESULT=`mkdir -p ../../storage/app/audio`
else
RESULT=
fi
fi
if [ "X" = "X"$RESULT ] ; then
printf "Success!\n"
printf "Make sure the correct owner and permissions are set on the storage/app/audio directory.\n"
printf "\nCurrent permissions and owner are: \t" && ls -las ../../storage/app | grep audio
printf "The owner should be set to the web servers user, e.g. www-data for Apache2\n\n"
else
printf "Could not create the audio directory.\n\n"
printf "Make sure the correct owner and permissions are set on the storage/app directory.\n"
printf "\nCurrent permissions and owner are: \t" && ls -las ../../storage | grep app
printf "The owner should be set to the user that created the Laravel app\n\n"
fi
