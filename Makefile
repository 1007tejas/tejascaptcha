#!/bin/sh

#TEJAS := ( shell cd ../../../ && composer show | grep -q "tejas/tejascaptcha" && echo 1 || echo 1 )


move:
	MOVED := (shell cp -u ./tejascaptchaMakefile ../../tejascaptchaMakefile)

clean: move
  make -f ../../tejascaptchaMakefile clean

update: move
  make -f ../../tejascaptchaMakefile update

test: move
  make -f ../../tejascaptchaMakefile test

install: move
  make -f ../../tejascaptchaMakefile install
