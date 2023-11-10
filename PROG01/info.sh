#!/bin/bash

# Device name
echo "Device name: $(uname -n)"
  
if ! command -v lsb_release >/dev/null 2>&1   # Check if lsb_release command been installed
then  # if no then use /etc/os-release
  source /etc/os-release
  echo "Distribution: $NAME"
  echo "OS Version: $VERSION"
  exit 1
else 
  echo "Distribution: $(lsb_release -sd)"
  echo "OS Version: $(lsb_release -sr)"
fi

# CPU name
CPU_NAME=$(lscpu | grep "Model name:" | cut -d ":" -f 2 | tr -s [:blank:])
echo "CPU Name:$CPU_NAME"

# CPU architecture
echo "$(lscpu | grep "Architecture:" | tr -s [:blank:])"

# CPU speed
CPU_SPEED=$(grep Hz /proc/cpuinfo | head -n 1 | cut -d ":" -f 2 | tr -s [:blank:])
echo "CPU Speed:$CPU_SPEED"

# Memory total
MEM_TOTAL=$(free --mega | awk '/Mem/ {print $2}')
echo "Memory Total: ${MEM_TOTAL}MB"

# Disk free total
DISK_FREE=$(df --block-size=MB -T | awk '/ext4/ {print $5}')
echo "Disk Free Total: $DISK_FREE"

# List all device ip
echo "List IP Devices: "
ip -br -4 addr show | awk '{print "\t" $1 "\t\t" $3}'

# List all users
echo "List all user: "
awk -F: '{ print "\t" $1 }' /etc/passwd | sort

# List all process with root privilege
echo "List all process with root privilege: "
ps -eo user,command -U root -u root --sort command --no-headers | awk '{print "\t" $2}'

# List all open ports
echo "List all open ports: "
ss -tuln --no-header | awk '{print "\t" $5}'

# List all directory that others users have write privilege
echo "List all directory with others write permission"
# Remove head -n 5 if you want to print all
echo "$(find / -type d -perm -o+wx 2>/dev/null | head -n 5 | awk '{print "\t" $0}')" 

# List all package installed and its version
echo "List all package installed and its version"
# dpkg --get-selections | awk '{print $1}' | xargs dpkg-query -W -f='${binary:Package}\t${Version}\n'
dpkg-query -W | awk '{print "\t" $0}'