# !/usr/bin/python3

import os, getopt, sys, shlex, subprocess

def usage():
    print('Currently accepting 2 arguements, [-d, --data] and [-h, --help]')

def main():
    try:
        opts, args = getopt.getopt(sys.argv[1:], "hd:", ["help", "data="])
    except getopt.GetoptError as err:
        # print help information and exit:
        print(err)  # will print something like "option -a not recognized"
        usage()
        sys.exit(2)
    data = None
    for o, a in opts:
        if o in ("-d", "--data"):
            data = a
        elif o in ("-h", "--help"):
            usage()
            sys.exit()
        else:
            assert False, "unhandled option"

    with open('/var/www/dev.173.255.195.42/resources/audio/final.mp3', 'w+') as f:
        print('What is?', file=f)
        f.close()


    command_line = 'ffmpeg -safe 0  -f concat -i audio/text/konkat -c copy /var/www/dev.173.255.195.42/resources/audio/final.mp3'
    args = shlex.split(command_line)
    print(args)
    p = subprocess.Popen(args) # Success!


if __name__ == "__main__":
    main()
