SHELL := /bin/bash
VERSION := $$(<version)

remove:
	@$$SHELL removeproj.sh && @$$SHELL filesdirs.sh 2

install:
	@$$SHELL requireproj.sh 1 && @$$SHELL filesdirs.sh 1

update:
	@$$SHELL requireproj.sh 2 && @$$shellfilesdirs.sh 1

test:
	@$$SHELL testdir.sh

show_version:
	printf "The tejas/tejascaptcha version is: "$(VERSION)
