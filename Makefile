#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash

move:
	@if [ -d "../vendor/tejascaptcha_scripts" ] ; then rm -r ../vendor/tejascaptcha_scripts; fi
	@if [ -d "../vendor/tejas/tejascaptcha/scripts" ] ; then cp -r ../vendor/tejas/tejascaptcha/scripts ../vendor/tejascaptcha_scripts; fi

remove: move
	@echo ""
	-@cd ../ && composer remove tejas/tejascaptcha && composer clearcache && [ -d "vendor/tejascaptcha_scripts" ] && rm -r -f vendor/tejascaptcha_scripts  && [ -d "tejascaptcha" ] && rm -r -f tejascaptcha && cd ../ && exit 0

update: move
	@cd ../vendor/tejascaptcha_scripts && make update
	@echo ""

test: move
	@cd ../vendor/tejascaptcha_scripts && make test
	@echo ""

install: move
	@cd ../vendor/tejascaptcha_scripts && make install
	@echo ""

show_version: move
	@cd ../vendor/tejascaptcha_scripts && make show_version
	@echo ""
