#!/bin/bash

# Defina variáveis
BRANCH="Canary"
DEST_DIR="/var/www/html"

# Navegar até o diretório do projeto
cd $DEST_DIR

# Verificar se é um repositório Git
if [ ! -d ".git" ]; then
    echo "Erro: O diretório especificado não é um repositório Git."
    exit 1
fi

# Buscar atualizações do repositório remoto
git fetch origin $BRANCH 2>&1

# Verificar diferenças entre o branch local e o branch remoto
DIFF_OUTPUT=$(git diff origin/$BRANCH --name-only 2>&1)

# Verificar se há diferenças
if [ -n "$DIFF_OUTPUT" ]; then
    echo "update"
    echo "$DIFF_OUTPUT"
else
    echo "no_update"
fi
