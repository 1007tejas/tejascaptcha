#TEJAS := $(shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )
SHELL := /bin/bash
VERSION = $$(<versiontxt)
BASE_DIR = $$(shell pwd)

move:
	if [ -d "../../tejascaptcha_scripts"] ; then rm -r ../../tejascaptcha_scripts; fi
	@cp -r ./scripts ../../tejascaptcha_scripts

remove: move
	$(MAKE) -C ../../tejascaptcha_scripts && make -f Makefile remove
	@echo "Removed tejas/tejascaptcha - OK"
	@echo ""

update: move
	$(MAKE) -C ../../tejascaptcha_scripts && make -f Makefile update
	@echo "Updated tejas/tejascaptcha - OK"
	@echo ""

test: move
	$(MAKE) -C ../../tejascaptcha_scripts && make -f Makefile test
	@echo "Tested tejas/tejascaptcha - OK"
	@echo ""

install: move
	$(MAKE) -C ../../tejascaptcha_scripts && make -f Makefile install
	@echo "Installed tejas/tejascaptcha: OK"
	@echo ""

version:
	@echo "tejas/tejascaptcha version: "$(VERSION)
	@echo ""

wakeup:
	CURDIR = $(BASE_DIR)
