SHELL := /bin/bash

remove: ../../composer.json
	@$$SHELL removeproj.sh
	@$$SHELL filesdirs.sh 2

install: ../../composer.json
	@$$SHELL requireproj.sh 1
	@$$SHELL filesdirs.sh 1

update: ../../composer.json
	@$$SHELL requireproj.sh 2
	@$$SHELL filesdirs.sh 1

test:
	@$$SHELL testdir.sh
