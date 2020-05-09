#!/bin/bash

ORIGMAKEFILELOC="../tejas/tejascaptcha"
NEWMAKEFILELOC="../../tejascaptcha"

if [ -f $ORIGMAKEFILELOC"/Makefile" ] ; then
mkdir $NEWMAKEFILELOC
cp $ORIGMAKEFILELOC"/Makefile" $NEWMAKEFILELOC"/";
printf "\n\tThe tejascaptcha Makefile has been copied to tejascaptcha directory
\tin the root of your project.\n
\tYou may run 'tejascaptcha/make [update, install, remove, test or version]'.\n";
fi
