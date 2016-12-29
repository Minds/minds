@echo off

:: Clone the main repo
git pull

:: Setup the other repos
git clone https://github.com/Minds/front front
git clone https://github.com/Minds/engine engine
