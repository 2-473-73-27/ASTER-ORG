#!/bin/bash

# Ensure Python is installed
if ! command -v python3 &> /dev/null
then
    echo "Python3 could not be found. Please install Python."
    exit
fi

# Install dependencies if needed
echo "Checking and installing dependencies..."
pip install flask

# Run the Flask application
echo "Starting AstraSMS Portal..."
python3 app.py
