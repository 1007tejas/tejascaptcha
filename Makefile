#!/bin/sh

#TEJAS := ( shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )


move:
	(shell cp -u ./tejascaptchaMakefile ../../tejascaptchaMakefile)

clean: move
	(shell cd ../../ && make -f tejascaptchaMakefile clean)

update: move
	(shell cd ../../ && make -f tejascaptchaMakefile update)

test: move
	(shell cd ../../ && make -f tejascaptchaMakefile test)

install: move
	(shell cd ../../ && make -f tejascaptchaMakefile install)
