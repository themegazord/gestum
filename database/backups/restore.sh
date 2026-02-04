#!/bin/bash

# Script de restauração do banco de dados Gestum
# Uso: ./database/backups/restore.sh [arquivo.sql.gz]

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

# Verificar se foi passado um arquivo como argumento
if [ -z "$1" ]; then
    echo "Backups disponíveis:"
    echo "-------------------"
    ls -lh "$BACKUP_DIR"/*.sql.gz 2>/dev/null || echo "Nenhum backup encontrado."
    echo ""
    echo "Uso: $0 <arquivo.sql.gz>"
    echo "Exemplo: $0 gestum_20260204_143000.sql.gz"
    exit 1
fi

BACKUP_FILE="$1"

# Se não for caminho absoluto, procurar na pasta de backups
if [[ ! "$BACKUP_FILE" = /* ]]; then
    BACKUP_FILE="$BACKUP_DIR/$BACKUP_FILE"
fi

# Verificar se o arquivo existe
if [ ! -f "$BACKUP_FILE" ]; then
    echo "Erro: Arquivo não encontrado: $BACKUP_FILE"
    exit 1
fi

echo "============================================"
echo "ATENÇÃO: Esta operação irá SOBRESCREVER"
echo "todos os dados do banco: $DB_DATABASE"
echo "============================================"
echo ""
read -p "Deseja continuar? (s/N): " CONFIRM

if [[ ! "$CONFIRM" =~ ^[sS]$ ]]; then
    echo "Operação cancelada."
    exit 0
fi

echo ""
echo "Restaurando backup: $BACKUP_FILE"
echo "Banco de dados: $DB_DATABASE"
echo "Host: $DB_HOST:$DB_PORT"

# Restaurar o backup (descompactando se necessário)
if [[ "$BACKUP_FILE" == *.gz ]]; then
    gunzip -c "$BACKUP_FILE" | mysql \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --user="$DB_USERNAME" \
        --password="$DB_PASSWORD" \
        "$DB_DATABASE"
else
    mysql \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --user="$DB_USERNAME" \
        --password="$DB_PASSWORD" \
        "$DB_DATABASE" < "$BACKUP_FILE"
fi

# Verificar resultado
if [ $? -eq 0 ]; then
    echo ""
    echo "Restauração concluída com sucesso!"
else
    echo ""
    echo "Erro ao restaurar backup!"
    exit 1
fi
