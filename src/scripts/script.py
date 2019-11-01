# !/usr/bin/python3

import os, getopt, re, sys, shlex, subprocess
import queue, threading

jobQueue = queue.Queue(3000)
threads = []
threadLock = threading.Lock()

def doCommand(command_line):

    threadLocalData3 = threading.local()
    threadLocalData3.command_line = command_line
    threadLocalData3.args = None
    threadLocalData3.response = None

    threadLocalData3.args = shlex.split(threadLocalData3.command_line)
    # print(threadLocalData3.args)
    threadLocalData3.response = subprocess.run(threadLocalData3.args, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    # print(threadLocalData3.response.stderr)
    # print(threadLocalData3.response.stdout)
    # print(threadLocalData3.response.returncode)


def createAudio(audioType, fnsuffix, **audioText):

    threadLocalData2 = threading.local()
    threadLocalData2.audioType = audioType
    threadLocalData2.fnsuffix = fnsuffix
    threadLocalData2.audioText = audioText

    doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio1']) + '\' -s 175 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio1' +threadLocalData2.fnsuffix+ '.wav')
    doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio2']) + '\' -s 175 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio2' +threadLocalData2.fnsuffix+ '.wav')
    doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio3']) + '\' -s 185 -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio3' +threadLocalData2.fnsuffix+ '.wav')
    if 'regularCaptcha' in threadLocalData2.audioType:
        doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 110  -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +threadLocalData2.fnsuffix+ '.wav')
    elif 'mathCaptcha' in threadLocalData2.audioType:
        doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 140  -p 99 -v mb-us2 -phonout -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +threadLocalData2.fnsuffix+ '.wav')

def threadJob():

    threadLocalData1 = threading.local()
    threadLocalData1.spacedData = None
    threadLocalData1.audioType = None
    threadLocalData1.audioText = None
    threadLocalData1.item = None
    threadLocalData1.f = None
    threadLocalData1.l = None
    threadLocalData1.s = None
    threadLocalData1.u = None


    while True:
        if not jobQueue.empty():
            threadLocalData1.item = jobQueue.get()
            # if threadLocalData1.item is None:
        else:
            break

        threadLocalData1.data = threadLocalData1.item['data']
        threadLocalData1.fnsuffix = threadLocalData1.item['fnsuffix']

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
        threadLocalData1.spacedData = ' '.join(threadLocalData1.data)
        if '=' in threadLocalData1.spacedData:
            O_ZERO = re.compile('(([0-9])\s([0-9]))+')
            threadLocalData1.spacedData = O_ZERO.sub('\g<2>\g<3>', threadLocalData1.spacedData)
            O_ONE = re.compile('(\s=)')
            threadLocalData1.spacedData = O_ONE.sub('', threadLocalData1.spacedData)

            threadLocalData1.audioType = 'mathCaptcha'
            threadLocalData1.audioText = mathCaptcha
        else:
            threadLocalData1.audioType = 'regularCaptcha'
            threadLocalData1.audioText = captcha

        threadLocalData1.audioText['audio4'] = threadLocalData1.spacedData

        createAudio(threadLocalData1.audioType, threadLocalData1.fnsuffix, **threadLocalData1.audioText)

        threadLocalData1.s = "file '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio1"+threadLocalData1.fnsuffix+".wav'\n"\
        "file '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData"+threadLocalData1.fnsuffix+".wav'\n"\
        "file '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio2"+threadLocalData1.fnsuffix+".wav'\n"\
        "file '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData"+threadLocalData1.fnsuffix+".wav'\n"\
        "file '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio3"+threadLocalData1.fnsuffix+".wav'"

        threadLocalData1.f = open('/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +threadLocalData1.fnsuffix , 'w+')
        threadLocalData1.f.write(threadLocalData1.s)
        threadLocalData1.f.close()

        doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +threadLocalData1.fnsuffix+ ' -ar 44100 -ac 2 -ab 192k -f mp3 /var/www/dev.173.255.195.42/resources/audio/final' +threadLocalData1.fnsuffix+ '.mp3')
        doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +threadLocalData1.fnsuffix+ ' -ar 44100 -ac 2 -ab 192k -f ogg /var/www/dev.173.255.195.42/resources/audio/final' +threadLocalData1.fnsuffix+ '.ogg')
        doCommand('ffmpeg -y -safe 0  -f concat -i /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +threadLocalData1.fnsuffix+ ' /var/www/dev.173.255.195.42/resources/audio/final' +threadLocalData1.fnsuffix+ '.wav')

        threadLocalData1.s = '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +threadLocalData1.fnsuffix
        doCommand('rm '+threadLocalData1.s)

         # subprocess restrictions for shell scripting, don't want shell=true as it is being deprecated
        threadLocalData1.l = ['audio1' +threadLocalData1.fnsuffix, 'audio2' +threadLocalData1.fnsuffix, 'audio3' +threadLocalData1.fnsuffix, 'audioData' +threadLocalData1.fnsuffix]
        for threadLocalData1.u in threadLocalData1.l:
            s = '/var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/' +threadLocalData1.u+ '.wav'
            doCommand('rm '+threadLocalData1.s)

        jobQueue.task_done()



def usage():
    print('''
Currently accepting 3 arguements and 2 are required [-d, --data], [-f, --fnsuffix] and [-h, --help]
-d --data (required) is the captcha content.
-f --fnsuffix (required) is a string for a random filename.
-h --help prints this message.

'''
)

def main():

    threadLocalData0 = threading.local()
    threadLocalData0.jobParameters = None
    threadLocalData0.data = None
    threadLocalData0.fnsuffix = None
    threadLocalData0.opts = None
    threadLocalData0.args = None
    threadLocalData0.getopt = getopt
    threadLocalData0.a = None
    threadLocalData0.i = None
    threadLocalData0.o = None
    threadLocalData0.t = None

    max_worker_threads = 100

    try:
        threadLocalData0.opts, threadLocalData0.args = threadLocalData0.getopt.getopt(sys.argv[1:], "hd:f:", ["help", "data=", "fnsuffix="])
    except threadLocalData0.getopt.GetoptError as err:
        # print help information and exit:
        print(threadLocalData0.err)  # will print something like "option -a not recognized"
        usage()
        sys.exit(2)

    for threadLocalData0.o, threadLocalData0.a in threadLocalData0.opts:
        if threadLocalData0.o in ("-d", "--data"):
            threadLocalData0.data = threadLocalData0.a
        elif threadLocalData0.o in ("-f", "--fnsuffix"):
            threadLocalData0.fnsuffix = threadLocalData0.a
        elif threadLocalData0.o in ("-h", "--help"):
            usage()
            sys.exit()
        else:
            assert False, threadLocalData0.o + " unhandled option"

    if threadLocalData0.data == None or threadLocalData0.fnsuffix == None:
        if threadLocalData0.data == None:
            threadLocalData0.data = 'None'
        if threadLocalData0.fnsuffix == None:
            threadLocalData0.fnsuffix ='None'

        print('Missing required arguement. d='+threadLocalData0.data+' f='+threadLocalData0.fnsuffix)
        usage()
        sys.exit()

    if not jobQueue.full():
        jobQueue.put({'data':threadLocalData0.data, 'fnsuffix':threadLocalData0.fnsuffix})

    threadLock.acquire()
    if len(threads) < max_worker_threads:
        tj = threading.Thread(target=threadJob)
        tj.start()
        threads.append(tj)
    threadLock.release()

    # block until all tasks are done
    jobQueue.join();

    # stop workers
    # for i in range(max_worker_threads):
    #     jobQueue.put(None)

    for t in threads:
        t.join()

if __name__ == "__main__":
    main()
