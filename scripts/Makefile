SHELL := /bin/bash

remove: ../../composer.json
	@cd ../../ && composer remove tejas/tejascaptcha && composer clearcache

install:
	@$$SHELL ./requireproj.sh 1
	@$$SHELL ./filesdirs.sh

update: ../../composer.json
	@$$SHELL ./requireproj.sh 2
	@$$SHELL ./filesdirs.sh

test:
	@$$SHELL ./testdir.sh
