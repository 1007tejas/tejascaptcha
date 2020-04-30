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
