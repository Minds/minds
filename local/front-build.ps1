Push-Location $PSScriptRoot\..\front

try {
    $env:NODE_OPTIONS = '--max_old_space_size=4096'; npm run build:dev -- --delete-output-path=false

    if ($LastExitCode -ne 0) {
        throw "Something failed"
    }
}
catch {
    Pop-Location
    exit 1
}
finally {
    Pop-Location
    exit 0
}
