[CmdletBinding()]
Param(
    [switch] $Ssh
)

Set-StrictMode -Version latest
$ErrorActionPreference = "Stop"

Push-Location $PSScriptRoot

function Exec
{
    [CmdletBinding()]
    param(
        [Parameter(Position=0,Mandatory=1)][scriptblock]$cmd,
        [Parameter(Position=1,Mandatory=0)][string]$errorMessage = ("Error executing command {0}" -f $cmd)
    )
    & $cmd
    if ($lastexitcode -ne 0) {
        Throw ("Exec: " + $errorMessage)
    }
}

Try {
    $RemoteRoot = "https://gitlab.com/minds"

    If ($Ssh) {
        $RemoteRoot = "git@gitlab.com:minds"
    }

    Write-Host "Using $RemoteRoot"

    # Fetch latest
    Exec { git pull }

    # Setup the other repos
    Exec { git clone $RemoteRoot/front.git front --config core.autocrlf=input }
    Exec { git clone $RemoteRoot/engine.git engine --config core.autocrlf=input }
    Exec { git clone $RemoteRoot/sockets.git sockets --config core.autocrlf=input }
}
Catch {
    Pop-Location
    Exit 1
}
Finally {
    Pop-Location
    Exit 0
}
