# Local Stack

## Requirements
- Any modern x86_64 multi-core CPU that supports virtualization.
- 16GB of RAM (of which around 7.5GB should be devoted to Docker VM on macOS and Windows).
- 3GB storage for Minds repositories and packages, and at least 20GB for Docker VM/Images.
- Internet connectivity (only needed for downloading and provisioning the stack).

### Software requirements
- Git
- Docker 18 or higher, with docker-compose
- Node.js 10.x or higher, with npm and npx
- Port 8080 open

#### Extra requirements for Windows
- Windows 10 Pro with Hyper-V enabled (for Docker)
- PowerShell

## Before installing

### Windows line endings
Git on Windows defaults `core.autocrlf` setting to `true`, which causes installation, provisioning and entry-point scripts to become corrupted with Windows-style line endings.

Before installing Minds, make sure you change it to `input` either globally **BEFORE** downloading the repositories (1); or by setting it when cloning (2).

(1): `git config --global core.autocrlf input`

(2): `git clone [repo] --config core.autocrlf=input`

If you already downloaded Minds repositories, you'll have to either download it again, or do a hard reset in all the repositories, as seen on https://stackoverflow.com/a/10118312.

You will get a warning every time your run the local stack if any of the repositories has the wrong `core.autocrlf` value.

## Run-from-anywhere aliases
This is an optional step, but all examples in this document will be using the alias.

### Linux/macOS
Add to your ~/.bashrc (or ~/.zshrc) file
```sh
export $MINDSROOT=/path/to/minds

alias minds=$MINDSROOT/local/local
alias minds-front-build=$MINDSROOT/local/front-build
alias minds-ssr-build=$MINDSROOT/local/ssr-build
alias minds-ssr-serve=$MINDSROOT/local/ssr-serve
```

After saving the profile script, restart your terminal windows.

### Windows
Open PowerShell and run
```powershell
echo $profile
```
That command will output the location to your profile script. Edit it and add
```powershell
$env:MINDSROOT = 'X:\Path\To\minds'

Set-Alias -Name minds -Value $env:MINDSROOT\local\local.ps1
Set-Alias -Name minds-front-build -Value $env:MINDSROOT\local\front-build.ps1
Set-Alias -Name minds-ssr-build -Value $env:MINDSROOT\local\ssr-build.ps1
Set-Alias -Name minds-ssr-serve -Value $env:MINDSROOT\local\ssr-serve.ps1
```

After saving the profile script, restart your terminal windows.

## Preparing your OS

### Linux
- Nothing to do.

### macOS
- Setup Docker VM to have at least 7.5GB and it uses at least 2 CPUs.

### Windows
- Setup Docker VM to have at least 7.5GB and it uses at least 2 CPUs.
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

### Troubleshooting

#### Random errors when building or starting the Docker containers

Git might corrupted Docker container scripts line endings. [Read this](#windows-line-endings).

#### There are random ENOENT or EPERM errors when cleaning up or building the frontend app during install on Windows

Close any application that might be actively watching the folder, such as VSCode, TortoiseGit, etc. If it still fails, reboot your computer to release any rogue lock.

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
