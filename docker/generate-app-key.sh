#!/usr/bin/env bash
# Generate a Laravel-compatible APP_KEY for Render dashboard:
#   bash docker/generate-app-key.sh
echo "base64:$(openssl rand -base64 32)"
