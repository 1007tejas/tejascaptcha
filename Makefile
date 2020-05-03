SHELL := /bin/bash

#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
THISCWD := $(shell pwd)

define filesdirs =
	@printf "\nAttempting to create the storage/app/audio directory\n"
	RESULT=1
	if [ -d "../../../storage" -a ! -d "../../../storage/app/audio" ] ; then
	RESULT="$(mkdir -p ../../../storage/app/audio)"
	fi
	if [ "X" = "X"$RESULT ] ; then
	@printf "Success!\n"
	else
	@printf "Could not create the audio directory.\n\n"
	@printf "Make sure the correct owner and permissions are set on the storage/app directory.\n"
	@printf "Currently they are: \t" && ls -las ../../../storage | grep app
	fi
endef

define selyn =
	@printf "\nInstalling tejas/tejascaptcha will completely delete the current version of the package.\n"
	@printf "Upon successful completion of the install run 'cd .' to resync the new current directory contents\n"
	@printf "with the terminals shell.\n\n"
	@echo "Proceed with  installing tejas/tejascaptcha?"
	@echo ""
	@select YN in "Yes" "No"
	@do
	@case $YN in
	Yes) cd ../../ && make -f tejascaptchaMakefile install && echo "Installed tejas/tejascaptcha: OK" && cd .
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
	if [ Xrubbish = X$YN ] ; then
	@printf "You can 'make update' instead, it's non-destructive.\n\n"
	else
	@printf "Installing tejas/tejascaptcha has deleted and recreated the current directory.\n"
	@printf "Upon successful completion of the install run 'cd .' to resync the new current directory contents\n"
	@printf "with the terminals shell.\n\n"
	fi
endef

move:
	@cp -u ./tejascaptchaMakefile ../../tejascaptchaMakefile

clean: move
	@cd ../../ && make -f tejascaptchaMakefile clean
	@echo "Cleaned tejas/tejascaptcha - OK"
	@echo ""

update: move
	@cd ../../ && make -f tejascaptchaMakefile update
	@echo "Updated tejas/tejascaptcha - OK"
	@echo ""

test: move
	@cd ../../ && make -f tejascaptchaMakefile test
	@echo "Tested tejas/tejascaptcha - OK"
	@echo ""

install: move ; $(value selyn) ; $(value filesdirs)
	@echo ""

version:
	$echo "tejas/tejascaptcha version: 1.0.12.9"

.ONESHELL:
