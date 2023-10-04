#!/usr/bin/env bash

rsync -avz . --exclude '.env' --exclude '.git' --exclude 'storage/framework' --exclude '.idea' pablo@aang:/var/www/toro-bot/
