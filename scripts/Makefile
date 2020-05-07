SHELL := /bin/bash

remove: ../../composer.json
	@cd ../../ && composer remove tejas/tejascaptcha && composer clearcache

install:
	@$$SHELL ./requireproj.sh 1  > /dev/null 2>&1
	@$$SHELL ./filesdirs.sh > /dev/null 2>&1

update: ../../composer.json
	@$$SHELL ./requireproj.sh 2 > /dev/null 2>&1
	@$$SHELL ./filesdirs.sh > /dev/null 2>&1

test:
	@$$SHELL ./testdir.sh > /dev/null 2>&1
