#!/bin/bash

if [ "X1" = "X"$1 ] ; then
NAME="create"
NAMING="creating"
elif [ "X2" = "X"$1 ] ; then
NAME="delete"
NAMING="deleting"
else
printf "Bad args" && exit 0
fi

RESULT=1
echo "Proceed with "$NAMING" the tejas/tejascaptcha audio directory?"
echo ""

select YN in "Yes" "No"
do
case $YN
Yes)
if [ "Xcreate" = "X"$NAME ] ; then
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

else

if [ -d "../../storage/app/audio" ] ; then
RESULT=`\rm -r ../../storage/app/audio`
else
RESULT=
fi
if [ "X" = "X"$RESULT ] ; then
printf "Success! "$NAME"d the storage/app/audio directory.\n\n"
else
printf "Error! could not "$NAME" the storage/app/audio directory.\n\n"
fi

break
;;
No) YN="rubbish"
break
;;
*) printf "Invalid entry. Enter '1' for Yes or '2' for No\n1) Yes\n2) No\n"
;;
esac
done
echo ""
if [ Xrubbish != X$YN ] ; then
exit 1
fi
