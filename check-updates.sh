#!/bin/bash

# Defina variáveis
BRANCH="Canary"
DEST_DIR="/var/www/html"

# Definir variável de ambiente HOME
export HOME="/var/www"

# Navegar até o diretório do projeto
cd $DEST_DIR

# Verificar se é um repositório Git
if [ ! -d ".git" ]; then
    echo "Erro: O diretório especificado não é um repositório Git."
    exit 1
fi

# Buscar atualizações do repositório remoto
echo "Fetching updates from remote..." | tee -a /var/www/html/check-updates.log
FETCH_OUTPUT=$(git fetch origin $BRANCH 2>&1)
echo "Fetch Output: $FETCH_OUTPUT" | tee -a /var/www/html/check-updates.log

# Verificar diferenças entre o branch local e o branch remoto
echo "Checking for differences..." | tee -a /var/www/html/check-updates.log
DIFF_OUTPUT=$(git diff origin/$BRANCH --name-only 2>&1)
echo "Diff Output: $DIFF_OUTPUT" | tee -a /var/www/html/check-updates.log

# Verificar se há diferenças
if [ -n "$DIFF_OUTPUT" ]; then
    echo "update" | tee -a /var/www/html/check-updates.log
    echo "$DIFF_OUTPUT" | tee -a /var/www/html/check-updates.log
else
    echo "no_update" | tee -a /var/www/html/check-updates.log
fi
