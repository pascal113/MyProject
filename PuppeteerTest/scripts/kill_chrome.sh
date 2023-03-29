#!/bin/bash
echo "kille alle chrome prozesse"
for pid in $(ps -aux | grep "[n]ode proxyserver" | awk '{print $2}'); do kill -9 $pid ;done
killall chrome
sleep 1s
killall chrome
sleep 1s
killall chrome

