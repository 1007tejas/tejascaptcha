#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash

move:
	@if [ -d "../vendor/tejascaptcha_scripts" ] ; then rm -r ../vendor/tejascaptcha_scripts; fi
	@if [ -d "../vendor/tejas/tejascaptcha/scripts" ] ; then cp -r ../vendor/tejas/tejascaptcha/scripts ../vendor/tejascaptcha_scripts; fi

remove: move
	@cd ../vendor/tejascaptcha_scripts && make remove
	@echo ""

update: move
	@cd ../vendor/tejascaptcha_scripts && make update
	@echo ""

test:
	@cd ../vendor/tejascaptcha_scripts && make test
	@echo ""

install: move
	@cd ../vendor/tejascaptcha_scripts && make install
	@echo ""

version: move
	@cd ../vendor/tejascaptcha_scripts && make version
	@echo ""
