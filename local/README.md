# Local Stack

## Requirements
- git
- docker 18 or higher, with docker-compose
- node 10.x or higher, with npm and npx
- port 8080 open

### Extra requirements for Windows
- Windows 10 Pro (needed for Docker)
- PowerShell

## Creating an alias
This is an optional step, but all examples in this document will be using the alias.

### Linux/macOS
Add to your ~/.bashrc (or ~/.zshrc) file
```sh
alias minds=/path/to/minds/local/local
alias minds-front-build=/path/to/minds/local/front-build
alias minds-ssr-build=/path/to/minds/local/ssr-build
alias minds-ssr-serve=/path/to/minds/local/ssr-serve
```

### Windows
Open PowerShell and run
```powershell
echo $profile
```
That command will output the location to your profile script. Edit it and add
```powershell
Set-Alias -Name minds -Value X:\Path\To\minds\local\local.ps1
Set-Alias -Name minds-front-build -Value X:\Path\To\minds\local\front-build.ps1
Set-Alias -Name minds-ssr-build -Value X:\Path\To\minds\local\ssr-build.ps1
Set-Alias -Name minds-ssr-serve -Value X:\Path\To\minds\local\ssr-serve.ps1
```

## Preparing your OS

### Linux
- Nothing to do.

### macOS
- Setup Docker VM to have at least 6.5GB and it uses at leas 2 CPUs.

### Windows
- Setup Docker VM to have at least 6.5GB and it uses at leas 2 CPUs.
- Enable Shared Drives availability to the drive that has the Minds repository (https://docs.docker.com/docker-for-windows/#file-sharing).


## Installing Minds
> **Important!**
>
> This operation will wipe out all your current data in the Minds containers.
>
> Ensure you run `docker-compose down` to dispose old Docker containers **before updating `master` or checking out this branch**.

Run
```sh
minds install
```

## Running

### Starting the containers

Run
```sh
minds up
```

### Stopping the containers

Run
```sh
minds down
```

### Restarting the containers

Run
```sh
minds restart
```

### Rebuilding the containers
After any infrastructure changes, run
```sh
minds rebuild
```

## Running the frontend stack

### Linux

#### App
Run
```sh
minds-front-build
```

#### SSR Server
SSR server runs inside two Docker containers: `front-live-server` and `front-live-server-compiler`.

To check out their activity, open a terminal in the `minds` directory and run
```sh
docker-compose logs -f --tail=40 front-live-server front-live-server-compiler
```

### macOS/Windows

#### App
Run
```sh
minds-front-build
```

#### SSR Server
Open two consoles and run in the first one:
```sh
minds-ssr-build
```
And in the seconds
```sh
minds-ssr-serve
```

The last one might show an error first, but it's normal as your computer might be re-building the server at the time your started it.

## PHPSpec

### Running test suite
Run
```sh
minds phpspec
```

#### Running a directory
Run
```sh
minds phpspec run --format=pretty --no-code-generation Spec/.../
```

#### Running a single file
Run
```sh
minds phpspec run --format=pretty --no-code-generation Spec/.../.../MyFileSpec.php
```

#### Creating a new spec

#### Linux/macOS

Run
```sh
minds phpspec describe Minds\\...\\...\\MyClass
```

#### Windows

Run
```powershell
minds phpspec describe Minds\...\...\MyClass
```
