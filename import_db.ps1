# Import Database Script untuk CBN Legacy DB

Write-Host "Menunggu MySQL Container siap..."
Start-Sleep -Seconds 15

$sqlFile = "..\u7942055_cbn.sql"
$containerName = "cbn-laravel-mysql-1"

if (Test-Path $sqlFile) {
    Write-Host "Memulai proses import database u7942055_cbn (313MB)..."
    Write-Host "Mohon tunggu, proses ini bisa memakan waktu beberapa menit."
    
    Get-Content $sqlFile | docker exec -i $containerName mysql -u sail -ppassword u7942055_cbn
    
    Write-Host "Import database selesai!"
} else {
    Write-Host "Error: File SQL tidak ditemukan di $sqlFile"
}
