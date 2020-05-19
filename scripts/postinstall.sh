#!/bin/bash

ORIGMAKEFILELOC="../"
NEWMAKEFILELOC="../../../../tejascaptcha"

if [ -f $ORIGMAKEFILELOC"Makefile" ] ; then
[ -d $NEWMAKEFILELOC ] || mkdir -p $NEWMAKEFILELOC
\cp $ORIGMAKEFILELOC"/Makefile" $NEWMAKEFILELOC"/";
printf "\n\tThe tejascaptcha Makefile has been copied to tejascaptcha directory in the root of your project.\n
\tFrom the root of your project you may run:
\t'make -C tejascaptcha [update, install, remove, test or show_version]'.\n\n";
else
printf "\n\tMakefile not found.
\tFrom the root of your project run this command.\n
\t'cd vendor/tejas/tejascaptcha/scripts && bash postinstall.sh && cd ../../../../ || cd ../../../../'\n\n";
fi
