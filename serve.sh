#!/bin/bash
echo "Iniciando o servidor Laravel e npm..."

cleanup() {
  echo ""
  echo " Encerrando processos..."
  kill $(jobs -p)
  exit
}

trap cleanup SIGINT

php artisan serve &
npm run dev
