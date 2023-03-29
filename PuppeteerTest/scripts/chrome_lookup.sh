#!/bin/bash
_mydir="$(pwd)"
cd $_mydir
max_chrome_ps=100
chrome_process_count=`for pid in $(ps -aux | grep "[c]hrome" | awk '{print $2}'); do echo $pid ;done | wc -l`

echo "$chrome_process_count chrome prozesse..."

if [ "$chrome_process_count" -gt "$max_chrome_ps" ]; then
    ./kill_chrome.sh
fi
