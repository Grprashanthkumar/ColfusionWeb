#!/usr/bin/env bash

apt-get update
apt-get install -y apache2

apt-get install -y php5 libapache2-mod-php5 php5-mcrypt php5-mysql

cp /vagrant/VagrantBootstrapApacheVirtualHostConfig.conf /etc/apache2/sites-available/

cd /etc/apache2/mods-available

a2enmod proxy
a2enmod proxy_http

cd /etc/apache2/sites-available
a2dissite 000-default
a2ensite VagrantBootstrapApacheVirtualHostConfig

sudo service apache2 restart

if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant /var/www
fi

chmod -R 777 /vagrant/cache
chmod -R 777 /vagrant/OriginalSmarty/templates_c