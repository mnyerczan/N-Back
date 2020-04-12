#Jogosulságok beállítása szükséges sz alábbi könyvtárakra, hogy a képfeltöltés működjön..

sudo chmod -R 777 /img/forum_ikons
sudo chmod -R 777 /users
sudo chmod -R 777 /users/forum_images

sudo chmod -R 777 /log

#Linuxon:

#sudo chmod 777 /path/directory [ /file ]
-sudo chown -R www-data:www-data /users
-sudo chown -R www-data:www-data /log

#Modulok:

#Képfeltöltéshez: 
#(akt verz.)

sudo apt -y install php7.3-mysql
sudo apt -y install php7.3-gd 
#-PDO: 
sudo apt -y install pkg-php-tools


#Apache rewrite engedélyezése

sudo a2enmod rewrite

#.htaccess fájl engedélyezés
sudo nano /etc/apache2/sites-available/000-default.conf

#    <Directory /var/www/html>
#            Options Indexes FollowSymLinks MultiViews
#            AllowOverride All
#            Require all granted
#    </Directory>


systemctl restart apache2
