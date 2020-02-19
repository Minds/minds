#!/usr/bin/env bash
set -e

usage () {
  echo "Usage: init.sh [--ssh]"
}

# Argument parsing

ssh=
while [ "$1" != "" ]; do
    case $1 in
        --ssh )                 ssh=1
                                ;;
        * )                     usage
                                exit 1
    esac
    shift
done

#

REMOTE_ROOT="https://gitlab.com/minds"

if [ "$ssh" = "1" ]; then
  REMOTE_ROOT="git@gitlab.com:minds"
fi

#

cd "$(dirname "${BASH_SOURCE[0]}")"

# Clone the main repo
git pull

# Setup the other repos
git clone $REMOTE_ROOT/front front --config core.autocrlf=input
git clone $REMOTE_ROOT/engine engine --config core.autocrlf=input
git clone $REMOTE_ROOT/sockets sockets --config core.autocrlf=input
