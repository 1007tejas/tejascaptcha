#!/bin/bash

if [ "X1" = "X"$1 ] ; then
NAME="install"
NAMING="installing"
elif [ "X2" = "X"$1 ] ; then
NAME="update"
NAMING="updating"
else
printf "Bad args" && exit 0
fi

echo "Proceed with "$NAMING" tejas/tejascaptcha?"
echo ""
select YN in "Yes" "No"
do
case $YN in
Yes)  cd ../../ && composer remove tejas/tejascaptcha && composer clearcache && composer require tejas/tejascaptcha
break
;;
No) exit 0
break
;;
*) printf "Invalid entry. Enter '1' for Yes or '2' for No\n1) Yes\n2) No\n"
;;
esac
done
