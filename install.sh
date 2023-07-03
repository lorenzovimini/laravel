#!/bin/bash
if [ "$#" -eq 0 ]
then
        read -p  "Install path: " INSTALL_PATH

        if [ -z "$INSTALL_PATH" ]
        then
                echo 'ERROR: Install path cannot be blank please try again!'
                exit 0
        fi
        if [ ! -d "$INSTALL_PATH" ]
        then
                echo "ERROR: Install path ${INSTALL_PATH} not exit. Try again"
                exit 0
        fi
else
        INSTALL_PATH=$1;
        if [ -z "$INSTALL_PATH" ]
        then
                echo "ERROR: Install path ${INSTALL_PATH} not exit. Try again"
                exit 0
        fi


fi

echo "Install path ${INSTALL_PATH}"

command -v git >/dev/null 2>&1 ||
{ echo >&2 "Git is not installed. Installing.."; exit 0; }

git clone https://github.com/lorenzovimini/laravel ${INSTALL_PATH} && rm -rf ${INSTALL_PATH}/.git && rm -rf ${INSTALL_PATH}/.github
cd ${INSTALL_PATH} && composer update

echo "Install COMPLETED. Process config ..."

php artisan wta:install
php artisan wta:post-install

echo "Config COMPLETED."