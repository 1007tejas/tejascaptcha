SHELL := /bin/bash

clean: ../../composer.json
	@cd ../../ && composer remove tejas/tejascaptcha && composer clearcache

install: clean
	./requireproj.sh 1
	./filesdirs.sh

update: ../../composer.json
	./requireproj.sh 2
	./filesdirs.sh

test:
	./testdir.sh
