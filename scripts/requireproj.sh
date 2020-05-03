if [ "X"1 -eq "X"$1 ] ; then
NAME="install"
NAMING="installing"
elif [ "X"2 -eq "X"$1 ] ; then
NAME="update"
NAMEING="updating"
else
NAME="-passed bad arg-"
NAMING="-passed bad arg-"
fi
@printf "\n$NAMING tejas/tejascaptcha will completely delete the current version of the package.\n"
@printf "Upon successful completion of the $NAME run 'cd .' to resync the new current directory contents\n"
@printf "with the terminals shell.\n\n"
@echo "Proceed with  $NAMING tejas/tejascaptcha?"
@echo ""
@select YN in "Yes" "No"
@do
@case $YN in
Yes)  cd ../../ && composer require tejas/tejascaptcha && cd .
break
;;
No) YN="rubbish"
break
;;
*) printf "Invalid entry. Enter '1' for Yes or '2' for No\n1) Yes\n2) No\n"
;;
@esac
@done
@echo ""
if [ Xrubbish != X$YN ] ; then
@printf "$NAMING tejas/tejascaptcha has deleted and recreated the current project directory.\n"
@printf "Upon successful completion of the $NAME run 'cd .' to resync the new current directory contents\n"
@printf "with the terminals shell.\n\n"
fi
