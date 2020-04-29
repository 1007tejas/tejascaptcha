#!/bin/sh

#TEJAS := ( shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )


move:
	cp -u ./tejascaptchaMakefile ../../tejascaptchaMakefile

clean: move
	cd ../../ && make -f tejascaptchaMakefile clean

update: move
	cd ../../ && make -f tejascaptchaMakefile update

test: move
	cd ../../ && make -f tejascaptchaMakefile test

install: move
	cd ../../ && make -f tejascaptchaMakefile install
