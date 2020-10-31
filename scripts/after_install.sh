#!/bin/bash

set -eux

cd ~/travelog
php artisan migrate --force
php artisan config:cache