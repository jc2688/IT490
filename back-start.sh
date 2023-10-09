#!/bin/bash

if systemctl is-active --quiet nginx; then
        echo "Nginx is already running."
else
        sudo systemctl start nginx
        if [ $? -eq 0 ]; then
                echo "NginX has been started successfully."
        else
        echo "Failed to start Nginx."
        fi
fi
