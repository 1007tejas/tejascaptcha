SHELL := /bin/bash

#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
THISCWD := $(shell pwd)

define selyn =
	@echo ""
	@printf "\tInstalling tejas/tejascaptcha will completely delete the current version of the package.\n"
	@printf "\tUpon successful completion of the install run 'cd .' to resync the new current directory contents\n"
	@printf "\twith the terminals shell.\n\n"
	@echo "Proceed with  installing tejas/tejascaptcha?"
	@echo ""
	@select YN in "Yes" "No"
	@do
	@case $YN in
	"Yes") cd ../../ && make -f tejascaptchaMakefile install && echo "Installed tejas/tejascaptcha: OK" && cd .
	break
	;;
	"No") echo "You can try 'make update' instead"
	break
	;;
	*) echo "invalid entry - "$YN"."
	break
	;;
	@esac
	@done
	@echo ""
	@printf "\tInstalling tejas/tejascaptcha has deleted and recreated the current directory.\n"
	@printf "\tUpon successful completion of the install run 'cd .' to resync the new current directory contents\n"
	@printf "\twith the terminals shell.\n\n"
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

install: move ; $(value selyn)
	@echo ""

.ONESHELL:
