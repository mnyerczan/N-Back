Jogosulságok beállítása szükséges sz alábbi könyvtárakra, hogy a képfeltöltés működjön..

/img/forum_ikons
/users
/users/forum_images

/log

Linuxon:

#sudo chmod 777 /path/directory [ /file ]
-sudo chown -R www-data:www-data /users
-sudo chown -R www-data:www-data /log

Modulok:

-Képfeltöltéshez: php7.2-mysql, php7.2-gd (akt verz.)
-PDO: pkg-php-tools
