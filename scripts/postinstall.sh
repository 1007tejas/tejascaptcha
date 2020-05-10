#!/bin/bash

ORIGMAKEFILELOC="../"
NEWMAKEFILELOC="../../../../tejascaptcha"

if [ -f $ORIGMAKEFILELOC"Makefile" ] ; then
[ -d NEWMAKEFILELOC ] || mkdir $NEWMAKEFILELOC
\mv $ORIGMAKEFILELOC"/Makefile" $NEWMAKEFILELOC"/";
printf "\n\tThe tejascaptcha Makefile has been copied to tejascaptcha directory
\tin the root of your project.\n
\tyou may run 'cd tejascaptcha && make [update, install, remove, test or version]'.\n";
fi
