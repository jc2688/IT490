#!/bin/bash
#check if it is already online
if systemctl is-active --quiet mysql; then
        echo "MySQL server is already online."
        exit 0
fi
#Start MySQL Server
sudo service mysql start

#check if it has turned on now
if systemctl is-active --quiet mysql; then
        echo "MySQL server has been started"
        exit 0
else
        echo "MySQL failed to start"
        exit 1
fi
