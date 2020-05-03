#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash
THISCWD := $(shell pwd)
VERSION = $(<versiontxt)

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

install: move
	cd ../../ && make -f tejascaptchaMakefile install
	@echo "Installed tejas/tejascaptcha: OK"
	@echo ""

version:
	@echo "tejas/tejascaptcha version: "$VERSION
	@echo ""

.ONESHELL:
