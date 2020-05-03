#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash
VERSION = $(<versiontxt)

move:
	@cp -r -u ./scripts ../../tejascaptcha_scripts

remove: move
	@cd ../../tejascaptcha_scripts && make -f Makefile remove
	@echo "Removed tejas/tejascaptcha - OK"
	@echo ""

update: move
	@cd ../../tejascaptcha_scripts && make -f Makefile update
	@echo "Updated tejas/tejascaptcha - OK"
	@echo ""

test: move
	@cd ../../tejascaptcha_scripts && make -f Makefile test
	@echo "Tested tejas/tejascaptcha - OK"
	@echo ""

install: move
	cd ../../tejascaptcha_scripts && make -f Makefile install
	@echo "Installed tejas/tejascaptcha: OK"
	@echo ""

version:
	@echo "tejas/tejascaptcha version: "$VERSION
	@echo ""
