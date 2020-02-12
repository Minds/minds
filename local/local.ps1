Push-Location $PSScriptRoot

try {
    npm i --silent --no-progress --no-audit --no-fund
    $env:NODE_OPTIONS = '--max_old_space_size=4096'; node cli.js $args

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
