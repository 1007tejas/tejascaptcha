#!/bin/bash

#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
THISCWD := $(shell pwd)

move:
	@cp -u ./tejascaptchaMakefile ../../tejascaptchaMakefile

clean: move
	@cd ../../ && make -f tejascaptchaMakefile clean
	@echo Cleaned tejas/tejascaptcha - OK

update: move
	@cd ../../ && make -f tejascaptchaMakefile update
	@echo Updated tejas/tejascaptcha - OK

test: move
	@cd ../../ && make -f tejascaptchaMakefile test
	@echo Tested tejas/tejascaptcha - OK

install: move
	@cd ../../ && make -f tejascaptchaMakefile install
	@echo Installed tejas/tejascaptcha: OK
	@rm $(THISCWD)/../../tejascaptchaMakefile
	@echo To resync the directory contents type:
	@echo \"cd ./ && ls -las\"
	@echo
# @echo tejas/tejascaptcha is already installed, try "make update"
# @echo
# @echo If you really need a clean install of tejas/tejascaptcha
# @echo cd to the root of your project and run:
# @echo
# @echo composer remove tejas/tejascaptcha
# @echo composer clearcache
# @echo composer require tejas/tejascaptcha
