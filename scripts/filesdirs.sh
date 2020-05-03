@printf "\nAttempting to create the storage/app/audio directory\n"
RESULT=1
if [ -d "../../storage" -a ! -d "../../storage/app/audio" ] ; then
RESULT="$(mkdir -p ../../../storage/app/audio)"
fi
if [ "X" = "X"$RESULT ] ; then
@printf "Success!\n"
else
@printf "Could not create the audio directory.\n\n"
@printf "Make sure the correct owner and permissions are set on the storage/app directory.\n"
@printf "Currently they are: \t" && ls -las ../../storage | grep app
@printf "\nThe owner should be set to the web servers user, e.g. www-data for Apache2\n"
fi
