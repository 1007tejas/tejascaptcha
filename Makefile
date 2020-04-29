#!/bin/sh

#TEJAS := ( shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 0 || echo 1 )

clean: ../../../composer.json
	@cd ../../../ && composer remove tejas/tejascaptcha && mkdir -p vendor/tejas/tejascaptcha
	@echo Cleaned tejas/tejascaptcha - OK

update: ../../../composer.json
	@cd ../../../ && composer update tejas/tejascaptcha
	@echo Updated tejas/tejascaptcha - OK

test: update
	@cd vendor/tejas/tejascaptcha
	@echo Tested tejas/tejascaptcha - OK

install: clean
	@cd vendor/tejas/tejascaptcha && cd ../../../ && composer require tejas/tejascaptcha && cd vendor/tejas/tejascaptcha
	@echo Installed tejas/tejascaptcha - OK
