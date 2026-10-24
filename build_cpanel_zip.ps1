param(
    [string]$OutputDir = $PSScriptRoot
)

$ProgressPreference = 'SilentlyContinue'

$targetZip = Join-Path $OutputDir "cpanel_ready_upload.zip"
$tempZip = Join-Path $OutputDir "cpanel_temp.zip"

if (Test-Path $tempZip) {
    Remove-Item -Path $tempZip -Force -ErrorAction SilentlyContinue
}

$excludeList = @(
    '.git',
    'cpanel_ready_upload.zip',
    'cpanel_temp.zip',
    'temp_cpanel.zip',
    'build_cpanel_zip.ps1'
)

$itemsToZip = Get-ChildItem -Path $OutputDir -Force | Where-Object {
    $excludeList -notcontains $_.Name
}

Write-Host "Compressing $($itemsToZip.Count) items into $tempZip..."
Compress-Archive -Path $itemsToZip.FullName -DestinationPath $tempZip -Force

if (Test-Path $targetZip) {
    Remove-Item -Path $targetZip -Force -ErrorAction SilentlyContinue
}

Move-Item -Path $tempZip -Destination $targetZip -Force
$zipInfo = Get-Item $targetZip
Write-Host "Successfully created $($targetZip) ($([math]::Round($zipInfo.Length / 1KB, 2)) KB)"
