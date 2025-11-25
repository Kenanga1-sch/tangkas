#!/bin/bash

# --- KONFIGURASI ---
# Sesuaikan dengan path folder yang baru dibuat
BACKUP_DIR="/home/kenanga1/backups/anggaran"
# Sesuaikan dengan nama container database di docker-compose.yml
CONTAINER_NAME="sekolah-db"
# User & Password Database (Sesuai .env / docker-compose)
DB_USER="root"
DB_PASS="rahasia_admin" # Ganti dengan password root mysql Anda
DB_NAME="anggaran_sekolah"

# Format nama file: backup_2025-10-20.sql
DATE=$(date +%Y-%m-%d_%H-%M-%S)
FILENAME="backup_$DATE.sql"

# --- EKSEKUSI BACKUP ---
echo "Mulai backup $DATE..."

# Perintah docker untuk dump database
docker exec $CONTAINER_NAME /usr/bin/mysqldump -u $DB_USER --password=$DB_PASS $DB_NAME > $BACKUP_DIR/$FILENAME

# Cek apakah berhasil
if [ $? -eq 0 ]; then
  echo "Backup BERHASIL disimpan di: $BACKUP_DIR/$FILENAME"
  
  # Kompresi biar hemat tempat (opsional)
  gzip $BACKUP_DIR/$FILENAME
  echo "File dikompresi menjadi .gz"

  # --- ROTASI: Hapus backup yang lebih tua dari 30 hari ---
  find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -delete
  echo "Backup lama (>30 hari) telah dibersihkan."
  
else
  echo "Backup GAGAL!"
fi