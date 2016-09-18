#!/usr/bin/env bash

download() {
	if [ `which curl` ]; then
		curl -s "$1" > "$2";
	elif [ `which wget` ]; then
		wget -nv -O "$2" "$1"
	fi
}

install_woocommerce() {
	download https://api.wordpress.org/plugins/info/1.0/woocommerce.json /tmp/woocommerce.json
	DOWNLOAD_LINK=$(grep -o 'https:\\/\\/downloads\.wordpress\.org\\/plugin\\/woocommerce\.[0-9]*\.[0-9]*\.[0-9]*\.zip' /tmp/woocommerce.json | sed 's~\\/~/~g')
	download $DOWNLOAD_LINK /tmp/woocommerce.zip
	unzip -q /tmp/woocommerce.zip -d /tmp
}

install_woocommerce