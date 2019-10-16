#!/bin/sh
#
# "Docker For Mac" and "Docker For Windows" resolve 'host.docker.internal' to the host IP.
# On other platforms we need to get the default route IP and add a resolvable entry for this
# so that we can reverse proxy to Angular Dev Server running on the host machine.
#
add_host_entry() {
  ip -4 route list match 0/0 | awk '{print $3" host.docker.internal"}' >> /etc/hosts
}

echo -n "Checking if host.docker.internal resolves..."
nslookup host.docker.internal > /dev/null 2>&1
if [ $? == 0 ]; then
  echo " OK"
else
  echo " FAIL"
  echo -n "Adding host.docker.internal to /etc/hosts..."
  add_host_entry
  if [ $? == 0 ]; then
    echo " OK";
  else
    echo " FAIL";
  fi
fi
echo "Starting Nginx..."
nginx -g "daemon off;"
