
Push-Location $PSScriptRoot\..\front

try {
    npx nodemon --delay 3 --watch dist/server.js --watch dist/server/**/* --ext js,mjs --exec "npm" run serve:ssr

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
