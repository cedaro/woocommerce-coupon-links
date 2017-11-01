#!/usr/bin/env bash

VERSION=$1

rm -rf vendor/woocommerce
git clone --branch $VERSION --depth 1 https://github.com/woocommerce/woocommerce.git vendor/woocommerce/woocommerce
