#!/bin/bash

echo "Proceed with removing tejas/tejascaptcha?"
echo ""
select YN in "Yes" "No"
do
case $YN in
Yes)  cd ../../ && composer remove tejas/tejascaptcha && composer clearcache && \rm tejascaptcha
break
;;
No) exit 0
break
;;
*) printf "Invalid entry. Enter '1' for Yes or '2' for No\n1) Yes\n2) No\n"
;;
esac
done
