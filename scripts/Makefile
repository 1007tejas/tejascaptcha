SHELL := /bin/bash
VERSION := $$(<version)

remove:
	@$$SHELL removeproj.sh && filesdirs.sh 2

install:
	@$$SHELL requireproj.sh 1 && filesdirs.sh 1

update:
	@$$SHELL requireproj.sh 2 && filesdirs.sh 1

test:
	@$$SHELL testdir.sh

show_version:
	printf "The tejas/tejascaptcha version is: "$(VERSION)
