#!/usr/bin/env bash
export $(egrep -v '^#' ./config/.env | xargs)

echo "[---------------- Resetting Artisan's ----------------]"

#pending

echo "[---------------- Resetting Artisan's  ----------------]"