SHELL := /bin/bash

remove: ../../composer.json
	@cd ../../ && composer remove tejas/tejascaptcha && composer clearcache

install:
	@$$SHELL ./requireproj.sh 1
	@$$SHELL ./filesdirs.sh
	@cd ../tejas/tejascaptcha && make -f Makefile wakeup

update: ../../composer.json
	@$$SHELL ./requireproj.sh 2
	@$$SHELL ./filesdirs.sh
	exit 0 > /dev/null 2

test:
	@$$SHELL ./testdir.sh
	exit 0 > /dev/null 2
