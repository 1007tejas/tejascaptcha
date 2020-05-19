SHELL := /bin/bash
VERSION := $$(<version)

install:
	@$$SHELL requireproj.sh 1 && $$SHELL filesdirs.sh 1 && exit 0

update:
	@$$SHELL requireproj.sh 2 && $$SHELL filesdirs.sh 1 && exit 0

test:
	@$$SHELL testdir.sh && exit 0

show_version:
	@printf "The tejas/tejascaptcha version is: "$(VERSION)"\n" && exit 0
