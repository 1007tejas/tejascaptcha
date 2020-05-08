#!/bin/bash

MAKELOC := "../tejas/tejascaptcha/"

if [ -f $(MAKELOC)"Makefile" ] ; then
cp $(MAKELOC)Makefile ../../../tejas_Makefile;
printf $(MAKELOC)"Makefile"
printf "\n\tThe tejas_Makefile has been copied to the root of your project.
\tYou may run 'make -f tejas_Makefile [update, install, remove, test or version]'.\n";
printf "\n\tIf the tejas_Makefile is the only Makefile in the projects root directory
\tyou may rename it to 'Makefile'. This reduces the 'make' programs invocation to:
\t'make: [update, install, remove, test or version]'\n\n"
fi
