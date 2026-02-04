#!/bin/bash

# Script de backup do banco de dados Gestum
# Uso: ./database/backups/backup.sh

# Diretório do projeto
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
BACKUP_DIR="$PROJECT_DIR/database/backups"

# Carregar variáveis do .env
if [ -f "$PROJECT_DIR/.env" ]; then
    export $(grep -E '^DB_(HOST|PORT|DATABASE|USERNAME|PASSWORD)=' "$PROJECT_DIR/.env" | xargs)
else
    echo "Erro: Arquivo .env não encontrado em $PROJECT_DIR"
    exit 1
fi

# Configurações
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="$BACKUP_DIR/${DB_DATABASE}_${TIMESTAMP}.sql"

# Criar diretório de backup se não existir
mkdir -p "$BACKUP_DIR"

echo "Iniciando backup do banco de dados: $DB_DATABASE"
echo "Host: $DB_HOST:$DB_PORT"
echo "Arquivo de destino: $BACKUP_FILE"

# Executar mysqldump
mysqldump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --user="$DB_USERNAME" \
    --password="$DB_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --add-drop-table \
    "$DB_DATABASE" > "$BACKUP_FILE"

# Verificar se o backup foi bem sucedido
if [ $? -eq 0 ]; then
    # Comprimir o arquivo
    gzip "$BACKUP_FILE"
    echo "Backup concluído com sucesso: ${BACKUP_FILE}.gz"
    echo "Tamanho: $(du -h "${BACKUP_FILE}.gz" | cut -f1)"
else
    echo "Erro ao criar backup!"
    rm -f "$BACKUP_FILE"
    exit 1
fi