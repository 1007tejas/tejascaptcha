#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash
VERSION = $$(<versiontxt)
BASE_DIR = $$(shell pwd)

move:
	if [ -d "vendor/tejascaptcha_scripts" ] ; then rm -r vendor/tejascaptcha_scripts; fi
	if [ -d "vendor/tejas/tejascaptcha/scripts" ] ; then cp -r vendor/tejas/tejascaptcha/scripts vendor/tejascaptcha_scripts; fi

remove: move
	$(MAKE) -C vendor/tejascaptcha_scripts && make -f Makefile remove
	@echo "Removed tejas/tejascaptcha - OK"
	@echo ""

update: move
	$(MAKE) -C vendor/tejascaptcha_scripts && make -f Makefile update
	@echo "Updated tejas/tejascaptcha - OK"
	@echo ""

test: move
	$(MAKE) -C vendor/tejascaptcha_scripts && make -f Makefile test
	@echo "Tested tejas/tejascaptcha - OK"
	@echo ""

install: move
	$(MAKE) -C vendor/tejascaptcha_scripts && make -f Makefile install
	@echo "Installed tejas/tejascaptcha: OK"
	@echo ""

version:
	@echo "tejas/tejascaptcha version: "$(VERSION)
	@echo ""
