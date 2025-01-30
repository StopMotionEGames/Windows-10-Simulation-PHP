#!/bin/bash

# Defina variáveis
REPO_URL="https://github.com/StopMotionEGames/Windows-10-Simulation-PHP.git"
BRANCH="Canary"
DEST_DIR="/var/www/html"
TEMP_DIR="/var/www/html/temp"
TOKEN="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"

# Crie o diretório temporário
mkdir -p $TEMP_DIR
cd $TEMP_DIR

# Verifique se o repositório já está clonado
if [ ! -d ".git" ]; then
  # Clone o repositório privado com o token
  git clone -b $BRANCH https://$TOKEN@github.com/StopMotionEGames/Windows-10-Simulation-PHP.git .
else
  # Puxe as atualizações do branch Canary
  git pull origin $BRANCH
fi

# Mover arquivos necessários para o diretório de destino
rsync -av --exclude='.git' $TEMP_DIR/ $DEST_DIR/

echo "Sincronização completa!"

# Limpar o diretório temporário
rm -rf $TEMP_DIR
