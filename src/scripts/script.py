# !/usr/bin/python3

import os, getopt, re, sys, shlex, subprocess

def doCommand(command_line):
    args = shlex.split(command_line)
    # print(args)
    response = subprocess.run(args, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    # print(response.stderr)
    # print(response.stdout)
    # print(response.returncode)
    # response.check_returncode()  espeak -v mb-de2 -phonout=mypho.pho "hello"

def createAudio(audioType, **audioText):
    doCommand('espeak -m \'' + str(audioText['audio1']) + '\' -s 175 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio1.wav')
    doCommand('espeak -m \'' + str(audioText['audio2']) + '\' -s 175 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio2.wav')
    doCommand('espeak -z -m \'' + str(audioText['audio3']) + '\' -s 185 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio3.wav')
    if 'regularCaptcha' in audioType:
        doCommand('espeak -m \'' + str(audioText['audio4']) + '\' -s 110  -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData.wav')
    elif 'mathCaptcha' in audioType:
        doCommand('espeak -m \'' + str(audioText['audio4']) + '\' -s 140  -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData.wav')


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

    # with open('/var/www/dev.173.255.195.42/resources/audio/final.mp3', 'w+') as f:
    #     print('What is?', file=f)
    #     f.close()
    mathCaptcha = {
        'audio1' :'What isz',
        'audio2' :'Please enter the answer to this problem',
        'audio3' :'Into the response field',
        'audio4' : ''
    }
    captcha = {
        'audio1' : 'Please enter',
        'audio2' : 'Again, Please enter this captcha',
        'audio3' : 'Into the response field',
        'audio4' : ''
    }

    # Note: The data variable 'should' never contain white space characters
    spacedData = ' '.join(data)
    if '=' in spacedData:
        O_ZERO = re.compile('(([0-9])\s([0-9]))+')
        spacedData = O_ZERO.sub('\g<2>\g<3>', spacedData)
        O_ONE = re.compile('(\s=)')
        spacedData = O_ONE.sub('', spacedData)

        audioType = 'mathCaptcha'
        audioText = mathCaptcha
    else:
        audioType = 'regularCaptcha'
        audioText = captcha

    audioText['audio4'] = spacedData

    createAudio(audioType, **audioText)
    doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/konkat -ar 44100 -ac 2 -ab 192k -f mp3 /var/www/dev.173.255.195.42/resources/audio/final.mp3')
    doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/konkat -ar 44100 -ac 2 -ab 192k -f ogg /var/www/dev.173.255.195.42/resources/audio/final.ogg')
    doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/konkat /var/www/dev.173.255.195.42/resources/audio/final.wav')



if __name__ == "__main__":
    main()
