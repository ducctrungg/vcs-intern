#!/bin/bash

check_etc_newfile () {
  list_etc=$1
  list_modified=$2
  list_add=$(comm -13 "$list_etc" "$list_modified" 2>/dev/null)

  for new_file in $list_add; do
    echo "File: $new_file"
    if [[ $(file -b --mime-type $new_file) == "text/plain" ]]; then
      echo "First ten line:"
      head -n 10 "$new_file" | awk '{print "\t" $0}'
    fi
  done
}

check_etc_changefile () {
  list_etc=$1
  list_modified=$2
  list_changed=$(comm -12 "$list_etc" "$list_modified" 2>/dev/null)
  
  for changed_file in $list_changed; do
    echo "File: $changed_file"
  done
}

check_etc_delfile () {
  list_etc=$1
  tmp_etc_file="/tmp/tmp_etc.txt"
  find /etc -type f 2>/dev/null | sort > "$tmp_etc_file" 
  list_del=$(comm -23 "$list_etc" "$tmp_etc_file" 2>/dev/null)

  for del_file in $list_del; do
    echo "File: $del_file"
  done

  # This line to update list /etc file
  # mv "$current_temp_file" "$prev_temp_file"
}

list_etc_file="/tmp/etc_file.txt"

if [[ ! -e "$list_etc_file" ]] ; then 
  find /etc -type f 2>/dev/null | sort > "$list_etc_file"
  echo "First run, exit process"
  exit 0
fi 

# List file have been modified
list_etc_modified="/tmp/list_etc_modified.txt"
find /etc -type f -newer "$list_etc_file" 2>/dev/null | sort > "$list_etc_modified"

echo "[Log checketc - $(date +"%T %d-%m-%Y")]"
echo "=== List file have been added ==="
check_etc_newfile "$list_etc_file" "$list_etc_modified"
echo "=== List file have been changed ==="
check_etc_changefile "$list_etc_file" "$list_etc_modified"
echo "=== List file have been deleted ==="
check_etc_delfile "$list_etc_file"
