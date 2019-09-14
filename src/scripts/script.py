# !/usr/bin/python3

import getopt, sys

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

    print(data)

if __name__ == "__main__":
    main()
