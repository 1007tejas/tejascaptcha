#!/bin/bash

if [ "X1" = "X"$1 ] ; then
NAME="install"
NAMING="installing"
elif [ "X2" = "X"$1 ] ; then
NAME="update"
NAMING="updating"
else
NAME="passed bad arg"
NAMING="passed bad arg"
fi
printf "\nDuring the "$NAME" composer will completely delete the current version of the package.\n"
printf "Upon successful completion of the "$NAME" run 'cd .' to resync the new current directory contents\n"
printf "with the terminals shell.\n\n"
echo "Proceed with "$NAMING" tejas/tejascaptcha?"
echo ""
select YN in "Yes" "No"
do
case $YN in
Yes)  cd ../../ && composer remove tejas/tejascaptcha && composer clearcache && composer require tejas/tejascaptcha
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
printf "\nDuring the "$NAME" tejas/tejascaptcha has deleted and recreated the current project directory.\n"
printf "Upon successful completion of the "$NAME" run 'cd .' to resync the new current directory contents\n"
printf "with the terminals shell.\n\n"
fi