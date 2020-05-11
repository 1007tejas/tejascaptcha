SHELL := /bin/bash
VERSION := $$(<version)

remove: ../../composer.json
	@$$SHELL removeproj.sh
	if [ "X"$($?) = "X0" ] ; then
		@$$SHELL filesdirs.sh 2
	fi

install: ../../composer.json
	@$$SHELL requireproj.sh 1
	if [ "X"$($?) = "X0" ] ; then
		@$$SHELL filesdirs.sh 1
	fi

update: ../../composer.json
	@$$SHELL requireproj.sh 2
	if [ "X"$($?) = "X0" ] ; then
		@$$SHELL filesdirs.sh 1
	fi

test:
	@$$SHELL testdir.sh

show_version:
	printf "The tejas/tejascaptcha version is: "$(VERSION)
