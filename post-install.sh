#!/bin/#!/usr/bin/env bash
$[ -f "Makefile"] ; then
	cp ./Makefile ../../../tejas_Makefile;
	printf "The tejascaptcha Makefile has been copied to the root of your project.\n
	You may run 'make -f tejas_Makefile [update, install, remove, test or version]'.\n";
	printf "\nIf the tejas_Makefile is the only Makefile in the projects root directory \n
	you may rename it to 'Makefile'. This reduces the 'make' programs invocation to: \n
	'make: [update, install, remove, test or version]'"
fi
