#!/bin/bash
find . -name "composer.json" -not -path "*/vendor/*" -execdir composer install --no-dev \;