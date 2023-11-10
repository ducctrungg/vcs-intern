#!/bin/bash

current_sessions_file="/tmp/current_sessions_file.txt"
prev_5_minutes=$(date --date '-5 minutes' +"%T")

cmd_log=$(journalctl -u ssh -g opened -q --since="$prev_5_minutes" -o short-full | awk '{print $3 " " $2 " " $((NF-2))}' | tee "$current_sessions_file")
# Thu 2023-11-09 10:51:47 +07 kali sshd[143114]: pam_unix(sshd:session): session opened for user userB(uid=1010) by (uid=0)
# 10:51:47 2023-11-09 userB(uid=1010)

echo "$cmd_log"

# Check new sessions
if [[ -s "$current_sessions_file" ]]; then
    # Mail to root
    email_subject="SSH Login Alert"
    email_body=$(awk '{print "User %s login sucess at %s %s", $3, $1, $2}' "$current_sessions_file")
    
    echo -e "$email_body" | mail -s "$email_subject" root@localhost
else
    echo "No new SSH connection"
fi
