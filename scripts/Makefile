SHELL := /bin/bash

remove: ../../composer.json
	$(MAKE) -C ../../ && composer remove tejas/tejascaptcha && composer clearcache

install:
	@$$SHELL ./requireproj.sh 1
	@$$SHELL ./filesdirs.sh
	$(MAKE) -C ../tejas/tejascaptcha && make -f Makefile wakeup

update: ../../composer.json
	@$$SHELL ./requireproj.sh 2
	@$$SHELL ./filesdirs.sh

test:
	@$$SHELL ./testdir.sh
