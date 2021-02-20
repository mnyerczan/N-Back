# Ehhez az alkalmazáshoz a php7.4, vagy nagyobb verzió szükséges!
# 
# json mappában a program ír és olvas ezért a jogosultságokat úgy
# kell beállítani, hogy az apache a www-data felhasználóval is tudja írni.
sudo chmod -R 775 public/json

# Ha nincs a www-data az editáló csoportjában:
sudo chmod -R 777 public/json

# Modulok:

#Képfeltöltéshez: 
#(akt verz.)
sudo apt-get update
sudo apt -y install php7.4-mysql
sudo apt -y install php7.4-gd 
#-PDO: 
sudo apt -y install pkg-php-tools


# Apache rewrite engedélyezése
sudo a2enmod rewrite


# .htaccess fájl engedélyezés
sudo nano /etc/apache2/sites-available/000-default.conf

#    <Directory /var/www/html>
#            Options Indexes FollowSymLinks MultiViews
#            AllowOverride All
#            Require all granted
#    </Directory>


#systemctl restart apache2

# Imagick PHP osztály használatához szükséges modul.
#
# Install imagemagick
sudo apt install imagemagick

# Check
sudo apt list imagemagick -a

# Install php-imagick
sudo apt install php-imagick

# Check
sudo apt list php-magick -a

# Restart apache2
sudo systemctl restart apache2


# Verify the installation
php -m | grep imagick

# Check phpinfo
php -r 'phpinfo();' | grep imagick

# sudo apt-get install php7.4-dev
php -r 'phpinfo();' | grep imagick

# /etc/php/7.4/cli/conf.d/20-imagick.ini,
# imagick
# imagick module => enabled
# imagick module version => 3.4.4
# imagick classes => Imagick, ImagickDraw, ImagickPixel, ImagickPixelIterator, ImagickKernel
# imagick.locale_fix => 0 => 0
# imagick.progress_monitor => 0 => 0
# imagick.skip_version_check => 1 => 1

# /var/log/apache2/error.log
# PHP Warning:  Module 'imagick' already loaded in Unknown on line 0