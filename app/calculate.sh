#!/bin/bash

# Define the JSON URL
JSON_URL="https://giatadrive.com/hotel-directory/json"

# Start the messenger consume command in the background
bin/console messenger:consume redis &

# Start the log watcher with the JSON URL argument in the background
bin/console app:process-giata-hotels "$JSON_URL" &

# Wait for all background jobs to finish
wait
