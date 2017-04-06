# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.require_version ">= 1.5.0"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

 config.vm.provider "virtualbox" do |v|
    v.memory = 1024
    v.cpus = 2
    #v.gui = true
  end

  config.vm.provider "virtualbox" do |v|
    host = RbConfig::CONFIG['host_os']
    slowNetworkFix = false

    # Give VM 1/4 system memory & access to all physical cpu cores on the host
    if host =~ /darwin/
      cpus = `sysctl -n hw.physicalcpu`.to_i
      # sysctl returns Bytes and we need to convert to MB
      sysmem = `sysctl -n hw.memsize`.to_i / 1024 / 1024
    elsif host =~ /linux|arch/
      cpus = `nproc`.to_i # @todo: get physical cpus (for VBox enhanced performance)
      # meminfo shows KB and we need to convert to MB
      sysmem = `grep 'MemTotal' /proc/meminfo | sed -e 's/MemTotal://' -e 's/ kB//'`.to_i / 1024
    elsif host =~ /mswin|windows|mingw/
      cpus = `powershell (Get-WmiObject Win32_ComputerSystem).numberoflogicalprocessors`.to_i # @todo: get physical cpus (for VBox enhanced performance)
      sysmem = `powershell (Get-WmiObject Win32_ComputerSystem).totalphysicalmemory`.to_i / 1024 / 1024
      slowNetworkFix = true
    else # unknown platform
      cpus = 1
      sysmem = 1024
      slowNetworkFix = true
    end

    mem = sysmem / 4
    mem = 2560 if mem < 2560

    v.customize ["modifyvm", :id, "--memory", mem]
    v.customize ["modifyvm", :id, "--cpus", cpus]

    if slowNetworkFix
      v.customize ["modifyvm", :id, "--nictype1", "virtio"]
      v.customize ["modifyvm", :id, "--nictype2", "virtio"]
    end
  end

  config.vm.hostname = "minds"

  config.vm.synced_folder ".", "/var/www/Minds", type: "nfs"

  config.vm.box = "ubuntu/trusty64"
  config.vm.network :private_network, ip: "10.54.0.111"

  config.vm.network :forwarded_port, guest: 80, host: 8800
  config.vm.network :forwarded_port, guest: 8010, host: 8810

  config.vm.provision :shell, path: "bin/bootstrap-ubuntu.sh", run: 'always'

end
