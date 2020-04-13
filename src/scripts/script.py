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


def createAudio(audioType, filenameSuffix, **audioText):

    threadLocalData2 = threading.local()
    threadLocalData2.audioType = audioType
    threadLocalData2.filenameSuffix = filenameSuffix
    threadLocalData2.audioText = audioText

    doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio1']) + '\' -s 155 -p 60 -v mb-us2  -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio1' +threadLocalData2.filenameSuffix+ '.wav')
    doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio2']) + '\' -s 165 -p 40 -v mb-us2  -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio2' +threadLocalData2.filenameSuffix+ '.wav')
    doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio3']) + '\' -s 165 -p 40 -v mb-us2  -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audio3' +threadLocalData2.filenameSuffix+ '.wav')
    if 'regularCaptcha' in threadLocalData2.audioType:
        doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 120 -p 40 -v mb-us2  -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +threadLocalData2.filenameSuffix+ '.wav')
    elif 'mathCaptcha' in threadLocalData2.audioType:
        doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 145 -p 40 -v mb-us2  -w /var/www/dev.173.255.195.42/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +threadLocalData2.filenameSuffix+ '.wav')

def threadJob():

    threadLocalData1 = threading.local()
    threadLocalData1.osAudioStoragePath = None
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

        #threadLocalData1.item['osBasePath'] = threadLocalData1.item['osBasePath']
        threadLocalData1.osBasePath = os.path.normpath(threadLocalData1.item['osBasePath'])
        threadLocalData1.osAudioDirectory = os.path.normpath(threadLocalData1.item['osAudioDirectory'])
        threadLocalData1.osAudioStoragePath = os.path.normpath(threadLocalData1.osBasePath + '/' + threadLocalData1.osAudioDirectory)
        #
        # print(threadLocalData1.osAudioStoragePath)
        # sys.exit()

        threadLocalData1.captchaData = threadLocalData1.item['captchaData']
        threadLocalData1.filenameSuffix = threadLocalData1.item['filenameSuffix']

        mathCaptcha = {
            'audio1' :'What is',
            'audio2' :'Please enter the answer to this problem',
            'audio3' :'Into the response field',
            'audio4' : ''
        }
        captchaData = {
            'audio1' : 'Please enter,.',
            'audio2' : 'Again, Please enter this capcha,.',
            'audio3' : 'Into the response field',
            'audio4' : ''
        }

        # Note: The captchaData variable 'should' never contain white space characters
        threadLocalData1.spacedData = ' '.join(threadLocalData1.captchaData)
        if '=' in threadLocalData1.spacedData:
            O_ZERO = re.compile('(([0-9])\s([0-9]))+')
            threadLocalData1.spacedData = O_ZERO.sub('\g<2>\g<3>', threadLocalData1.spacedData)
            O_ONE = re.compile('(\s=)')
            threadLocalData1.spacedData = O_ONE.sub('', threadLocalData1.spacedData)

            threadLocalData1.audioType = 'mathCaptcha'
            threadLocalData1.audioText = mathCaptcha
        else:
            threadLocalData1.audioType = 'regularCaptcha'
            threadLocalData1.audioText = captchaData

        threadLocalData1.audioText['audio4'] = threadLocalData1.spacedData

        createAudio(threadLocalData1.audioType, threadLocalData1.filenameSuffix, **threadLocalData1.audioText)

        threadLocalData1.s = "file "+ os.path.normpath(threadLocalData1.osBasePath + '/vendor/tejas/tejascaptcha/src/scripts/audio/audio1' + threadLocalData1.filenameSuffix + '.wav')+"\n"\
        "file " + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +  threadLocalData1.filenameSuffix +  '.wav')+"\n"\
        "file " + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audio/audio2' +  threadLocalData1.filenameSuffix +  '.wav')+"\n"\
        "file " + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audio/audioData' +  threadLocalData1.filenameSuffix +  '.wav')+"\n"\
        "file " + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audio/audio3' +  threadLocalData1.filenameSuffix +  '.wav')

        threadLocalData1.f = open(os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +  threadLocalData1.filenameSuffix) , 'w+')
        threadLocalData1.f.write(threadLocalData1.s)
        threadLocalData1.f.close()

        doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +  threadLocalData1.filenameSuffix)+ ' -ar 44100 -ac 2 -ab 192k -f mp3 ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/final' +  threadLocalData1.filenameSuffix + '.mp3'))
        doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +  threadLocalData1.filenameSuffix)+ ' -ar 44100 -ac 2 -ab 192k -f ogg ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/final' +  threadLocalData1.filenameSuffix + '.ogg'))
        doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +  threadLocalData1.filenameSuffix)+ ' ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/final' +  threadLocalData1.filenameSuffix +  '.wav'))

        threadLocalData1.s = os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audiosequence/konkat' +  threadLocalData1.filenameSuffix)
        doCommand('rm '+threadLocalData1.s)

         # subprocess restrictions for shell scripting, don't want shell=true as it is being deprecated
        threadLocalData1.l = ['audio1' +threadLocalData1.filenameSuffix, 'audio2' +threadLocalData1.filenameSuffix, 'audio3' +threadLocalData1.filenameSuffix, 'audioData' +threadLocalData1.filenameSuffix]
        for threadLocalData1.u in threadLocalData1.l:
            threadLocalData1.s = os.path.normpath(threadLocalData1.osBasePath +  '/vendor/tejas/tejascaptcha/src/scripts/audio/' +  threadLocalData1.u +  '.wav')
            doCommand('rm '+threadLocalData1.s)

        jobQueue.task_done()



def usage():
    print('''
Currently accepting 5 arguements and 4 are required [-b, --osBasePath], [-d, --osAudioDirectory], [-c, --captchaData],  [-s, --filenameSuffix] and [-h, --help]
-b --osBasePath (required) the servers absolute path where the Laravel framework is running.
-d --osAudioDirectory (required) the servers path to the audio storage directory.
-c --captchadata (required) is the captcha's textual content.
-s --filenameSuffix (required) is a string for a random filename.
-h --help prints this message.

'''
)

def main():

    threadLocalData0 = threading.local()
    threadLocalData0.osBasePath = None
    threadLocalData0.osAudioDirectory = None
    threadLocalData0.captchaData = None
    threadLocalData0.filenameSuffix = None
    threadLocalData0.opts = None
    threadLocalData0.args = None
    threadLocalData0.getopt = getopt
    threadLocalData0.a = None
    threadLocalData0.i = None
    threadLocalData0.o = None
    threadLocalData0.t = None

    max_worker_threads = 100

    try:    #$text = "-b $this->osBasePath -d $this->osAudioDirectory -c $this->tts -s $this->audioFileSuffix";
        threadLocalData0.opts, threadLocalData0.args = threadLocalData0.getopt.getopt(sys.argv[1:], "hb:d:c:s:", ["help", "osBasePath=", "osAudioDirectory=", "captchaData=", "filenameSuffix="])
    except threadLocalData0.getopt.GetoptError as err:
        # print help information and exit:
        usage()
        sys.exit(2)

        print(threadLocalData0.opts)
        sys.exit()

    for threadLocalData0.o, threadLocalData0.a in threadLocalData0.opts:
        if threadLocalData0.o in ("-b", "--osBasePath"):
            threadLocalData0.osBasePath = threadLocalData0.a
        elif threadLocalData0.o in ("-d", "--osAudioDirectory"):
            threadLocalData0.osAudioDirectory = threadLocalData0.a
        elif threadLocalData0.o in ("-c", "--captchaData"):
            threadLocalData0.captchaData = threadLocalData0.a
        elif threadLocalData0.o in ("-s", "--filenameSuffix"):
            threadLocalData0.filenameSuffix = threadLocalData0.a
        elif threadLocalData0.o in ("-h", "--help"):
            usage()
            sys.exit()
        else:
            assert False, threadLocalData0.o + " unhandled option"

    if threadLocalData0.osBasePath == None or threadLocalData0.osAudioDirectory == None or threadLocalData0.captchaData == None or threadLocalData0.filenameSuffix == None:
        if threadLocalData0.osBasePath == None:
            threadLocalData0.osBasePath = 'None'
        if threadLocalData0.osAudioDirectory == None:
            threadLocalData0.osAudioDirectory = 'None'
        if threadLocalData0.captchaData == None:
            threadLocalData0.captchaData = 'None'
        if threadLocalData0.filenameSuffix == None:
            threadLocalData0.filenameSuffix ='None'

        print('Missing required arguement. b='+threadLocalData0.osBasePath+' d='+threadLocalData0.osAudioDirectory+' c='+threadLocalData0.captchaData+' s='+threadLocalData0.filenameSuffix)
        usage()
        sys.exit()

    if not jobQueue.full():
        jobQueue.put({'osBasePath':threadLocalData0.osBasePath, 'osAudioDirectory':threadLocalData0.osAudioDirectory, 'captchaData':threadLocalData0.captchaData, 'filenameSuffix':threadLocalData0.filenameSuffix})

    threadLock.acquire()
    if len(threads) < max_worker_threads:
        tj = threading.Thread(target=threadJob)
        tj.start()
        threads.append(tj)
    threadLock.release()

    # block until all tasks are done
    jobQueue.join()

    # stop workers
    # for i in range(max_worker_threads):
    #     jobQueue.put(None)

    for t in threads:
        t.join()

if __name__ == "__main__":
    main()
