Push-Location $PSScriptRoot\..\front

try {
    npx nodemon --delay 3 --watch server.ts --watch dist/en/**/* --ext js,css,jpg,png,svg,mp4,webp,webm --exec "npm" run build:ssr:dev

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
