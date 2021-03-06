# !/usr/bin/python3

import os, getopt, re, sys, shlex, subprocess
import queue, threading

jobQueue = queue.Queue(50)
# High water mark is 45, enforced by TejasCaptchaSessionCleanup.php -> gc
# Max audio files in storage/app/audio is 45 * 3 = 135
# gc will garbage collect by deleting all audio files in the storage/app/audio
# directory when file count is greater than or equal to 135.
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


def createAudio(audioType, osFilename, osAudioDirectoryTemp, **audioText):

	threadLocalData2 = threading.local()
	threadLocalData2.audioType = audioType
	threadLocalData2.osFilename = osFilename
	threadLocalData2.audioText = audioText
	threadLocalData2.osAudioDirectoryTemp = os.path.normpath(osAudioDirectoryTemp)

	if not os.path.exists(threadLocalData2.osAudioDirectoryTemp):
		try:
			os.makedirs(threadLocalData2.osAudioDirectoryTemp)
		except OSError as e:
			if e.errno != errno.EEXIST:
				raise

	doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio1']) + '\' -s 155 -p 60 -v mb-us2  -w ' + threadLocalData2.osAudioDirectoryTemp + '/audio1' + threadLocalData2.osFilename+ '.wav')
	doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio2']) + '\' -s 165 -p 40 -v mb-us2  -w ' + threadLocalData2.osAudioDirectoryTemp + '/audio2' + threadLocalData2.osFilename+ '.wav')
	doCommand('espeak -z -m \'' + str(threadLocalData2.audioText['audio3']) + '\' -s 165 -p 40 -v mb-us2  -w ' + threadLocalData2.osAudioDirectoryTemp + '/audio3' + threadLocalData2.osFilename+ '.wav')
	if 'regularCaptcha' in threadLocalData2.audioType:
		doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 115 -p 40 -v mb-us2  -w ' + threadLocalData2.osAudioDirectoryTemp + '/audioData' + threadLocalData2.osFilename+ '.wav')
	elif 'mathCaptcha' in threadLocalData2.audioType:
		doCommand('espeak -m \'' + str(threadLocalData2.audioText['audio4']) + '\' -s 145 -p 40 -v mb-us2  -w ' + threadLocalData2.osAudioDirectoryTemp + '/audioData' + threadLocalData2.osFilename+ '.wav')


class ConsumerThread(threading.Thread):
	def __init__(self, group=None, target=None, name=None, args=(), kwargs=None, verbose=None):
		super(ConsumerThread,self).__init__()
		self.target = target
		self.name = name
		return

	def run(self):
		threadLocalData1 = threading.local()
		threadLocalData1.osBasePath = None
		threadLocalData1.osAudioDirectory = None
		threadLocalData1.osAudioStoragePath = None
		threadLocalData1.osAudioDirectoryTemp = None
		threadLocalData1.audioType = None
		threadLocalData1.audioText = None
		threadLocalData1.captchaData = None
		threadLocalData1.osFilenamePrefix = None
		threadLocalData1.osFilename = None
		threadLocalData1.spacedData = None
		threadLocalData1.item = None
		threadLocalData1.f = None
		threadLocalData1.l = None
		threadLocalData1.s = None
		threadLocalData1.u = None

		if not jobQueue.empty():
			threadLocalData1.item = jobQueue.get()
			# if threadLocalData1.item is None:

			threadLocalData1.osBasePath = os.path.normpath(threadLocalData1.item['osBasePath'])
			threadLocalData1.osAudioDirectory = os.path.normpath(threadLocalData1.item['osAudioDirectory'])
			threadLocalData1.osAudioStoragePath = os.path.normpath(threadLocalData1.osBasePath + '/' + threadLocalData1.osAudioDirectory)

			threadLocalData1.captchaData = threadLocalData1.item['captchaData']
			threadLocalData1.osFilenamePrefix = threadLocalData1.item['osFilenamePrefix']
			threadLocalData1.osFilename = threadLocalData1.item['osFilename']

			if not os.path.exists(threadLocalData1.osAudioStoragePath):
				try:
					os.makedirs(threadLocalData1.osAudioStoragePath)
				except OSError as e:
					if e.errno != errno.EEXIST:
						raise

			threadLocalData1.osAudioDirectoryTemp = os.path.normpath(threadLocalData1.osAudioStoragePath + '/audio' + threadLocalData1.osFilename)


			if not os.path.exists(threadLocalData1.osAudioDirectoryTemp):
				try:
					os.makedirs(threadLocalData1.osAudioDirectoryTemp)
				except OSError as e:
					if e.errno != errno.EEXIST:
						raise

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

			createAudio(threadLocalData1.audioType, threadLocalData1.osFilename, threadLocalData1.osAudioDirectoryTemp, **threadLocalData1.audioText)

			threadLocalData1.s = "file "+ os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/audio1' + threadLocalData1.osFilename + '.wav') + "\n"\
			"file " + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/audioData' + threadLocalData1.osFilename + '.wav') + "\n"\
			"file " + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/audio2' + threadLocalData1.osFilename + '.wav') + "\n"\
			"file " + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/audioData' + threadLocalData1.osFilename + '.wav') + "\n"\
			"file " + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/audio3' + threadLocalData1.osFilename + '.wav')

			threadLocalData1.f = open(os.path.normpath(threadLocalData1.osAudioDirectoryTemp +  '/konkat' +  threadLocalData1.osFilename) , 'w+')
			threadLocalData1.f.write(threadLocalData1.s)
			threadLocalData1.f.close()

			doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/konkat' + threadLocalData1.osFilename) + ' -ar 44100 -ac 2 -ab 192k -f mp3 ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/' + threadLocalData1.osFilenamePrefix + threadLocalData1.osFilename + '.mp3'))
			doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/konkat' + threadLocalData1.osFilename) + ' -ar 44100 -ac 2 -ab 192k -f ogg ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/' + threadLocalData1.osFilenamePrefix + threadLocalData1.osFilename + '.ogg'))
			doCommand('ffmpeg -y -safe 0 -f concat -i ' + os.path.normpath(threadLocalData1.osAudioDirectoryTemp + '/konkat' + threadLocalData1.osFilename) + ' ' + os.path.normpath(threadLocalData1.osAudioStoragePath + '/' + threadLocalData1.osFilenamePrefix +  threadLocalData1.osFilename + '.wav'))

			doCommand('rm -r ' + threadLocalData1.osAudioDirectoryTemp)

			jobQueue.task_done()

		return


def usage():
	print('''
Currently accepting 6 arguements and 5 are required:
   *[-b, --osBasePath], *[-d, --osAudioDirectory], *[-c, --captchaData],  *[-p, --osFilenamePrefix],  *[-n, --osFilename] and [-h, --help]
-b --osBasePath (required) the servers absolute path to the Laravel framework root directory.
-d --osAudioDirectory (required) path to the audio storage directory starting from the Laravel framework root directory.
-c --captchadata (required) is the captcha's textual content, excluding white space.
-p --osFilenamePrefix (required) is a constant string, it is prefixed to the osFilename.
-n --osFilename (required) is a constant string, it should be a randomly generated filename.
-h --help prints this message.

'''
)

class ProducerThread(threading.Thread):
	def __init__(self, group=None, target=None, name=None, args=(), kwargs=None, verbose=None):
		super(ProducerThread,self).__init__()
		self.target = target
		self.name = name

	def run(self):

		threadLocalData0 = threading.local()
		threadLocalData0.osBasePath = None
		threadLocalData0.osAudioDirectory = None
		threadLocalData0.captchaData = None
		threadLocalData0.osFilenamePrefix = None
		threadLocalData0.osFilename = None
		threadLocalData0.opts = None
		threadLocalData0.args = None
		threadLocalData0.getopt = getopt
		threadLocalData0.a = None
		threadLocalData0.i = None
		threadLocalData0.o = None
		threadLocalData0.t = None

		max_worker_threads = 100

		try:	#$text = "-b $this->osBasePath -d $this->osAudioDirectory -c $this->tts -s $this->audioFileSuffix";
			threadLocalData0.opts, threadLocalData0.args = threadLocalData0.getopt.getopt(sys.argv[1:], "hb:d:c:p:n:", ["help", "osBasePath=", "osAudioDirectory=", "captchaData=", "osFilenamePrefix=", "osFilename="])
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
			elif threadLocalData0.o in ("-p", "--osFilenamePrefix"):
				threadLocalData0.osFilenamePrefix = threadLocalData0.a
			elif threadLocalData0.o in ("-n", "--osFilename"):
				threadLocalData0.osFilename = threadLocalData0.a
			elif threadLocalData0.o in ("-h", "--help"):
				usage()
				sys.exit()
			else:
				assert False, threadLocalData0.o + " unhandled option"

		if threadLocalData0.osBasePath == None or threadLocalData0.osAudioDirectory == None or threadLocalData0.captchaData == None or threadLocalData0.osFilenamePrefix == None or threadLocalData0.osFilename == None:
			if threadLocalData0.osBasePath == None:
				threadLocalData0.osBasePath = 'None'
			if threadLocalData0.osAudioDirectory == None:
				threadLocalData0.osAudioDirectory = 'None'
			if threadLocalData0.captchaData == None:
				threadLocalData0.captchaData = 'None'
			if threadLocalData0.osFilenamePrefix == None:
				threadLocalData0.osFilenamePrefix ='None'
			if threadLocalData0.osFilename == None:
				threadLocalData0.osFilename ='None'

			print('Missing required arguement. b='+threadLocalData0.osBasePath+' d='+threadLocalData0.osAudioDirectory+' c='+threadLocalData0.captchaData+' n='+threadLocalData0.osFilename)
			usage()
			sys.exit()

		if not jobQueue.full():
			jobQueue.put({'osBasePath':threadLocalData0.osBasePath, 'osAudioDirectory':threadLocalData0.osAudioDirectory, 'captchaData':threadLocalData0.captchaData, 'osFilenamePrefix':threadLocalData0.osFilenamePrefix, 'osFilename':threadLocalData0.osFilename})

		return



if __name__ == "__main__":
	threadLocalData = threading.local()
	threadLocalData.p = ProducerThread(name='producer')
	threadLocalData.p.start()
	threadLocalData.p.join()

	threadLocalData.c = ConsumerThread(name='consumer')
	threadLocalData.c.start()
	threadLocalData.c.join()
