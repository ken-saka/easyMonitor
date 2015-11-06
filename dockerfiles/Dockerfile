FROM ubuntu:latest

EXPOSE 80

###########################################################################
# common
RUN ln -sf /usr/share/zoneinfo/Japan /etc/localtime
RUN apt-get update --fix-missing -y
RUN apt-get install -y \
         bash \
         whois \
         cron
RUN mkdir /var/share

###########################################################################
# ssh
RUN mkdir /var/run/sshd
RUN apt-get update --fix-missing -y
RUN apt-get install -y ssh
RUN useradd kanshi
RUN p=`echo "kanshi" | mkpasswd -s` \
  ; sed -i \
    -e "s/kanshi:\!:/kanshi:${p}:/" \
    /etc/shadow
RUN echo 'kanshi ALL=(ALL:ALL) ALL' > /etc/sudoers.d/kanshi
RUN chmod 400 /etc/sudoers.d/kanshi
RUN sed -i \
        -e 's/PermitRootLogin without-password/PermitRootLogin yes/' \
        /etc/ssh/sshd_config

###########################################################################
# Nginx
RUN  apt-get install -y nginx
ADD ./setfiles/default      /etc/nginx/sites-enabled/default
ADD ./setfiles/fastcgi.conf /etc/nginx/conf.d/fastcgi.conf
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

###########################################################################
# php5-fpm
RUN apt-get install -y \
         php5 \
         php5-gd \
         php5-mysql \
         php5-fpm \
         php5-cli
RUN sed -i \
        -e 's/^listen = \/var\/run\/php5-fpm\.sock/listen = 127.0.0.1:9000/' \
        /etc/php5/fpm/pool.d/www.conf

###########################################################################
# mysql
RUN echo "mysql-server mysql-server/root_password password mysql" \
    | debconf-set-selections
RUN echo "mysql-server mysql-server/root_password_again password mysql" \
    | debconf-set-selections

RUN apt-get install -y mysql-client 
RUN apt-get install -y mysql-server
RUN sed -i \ 
        -e "s/^bind-address\s*=\s*\(.*\)/#bind-address = \1/" \
        -e "s/\(\[mysqld\]\)/\1\ncharacter-set-server = utf8/g" \
        -e "s/\(\[client\]\)/\1\ndefault-character-set = utf8/g" \
        -e "s/\(\[mysqldump\]\)/\1\ndefault-character-set = utf8/g" \
        -e "s/\(\[mysql\]\)/\1\ndefault-character-set = utf8/g" \
        -e "s/^datadir	= \/var\/lib\/mysql/datadir = \/var\/share\/mysql/" \
        /etc/mysql/my.cnf
ADD ./setfiles/init.mysql /var/tmp/init.mysql

###########################################################################
# suvervisor
RUN apt-get install -y supervisor
RUN sed -i \
        -e 's/\/var\/log\/supervisor/\/var\/share\/logs/' \
        /etc/supervisor/supervisord.conf
ADD ./setfiles/supervisor/ssh.conf   /etc/supervisor/conf.d/
ADD ./setfiles/supervisor/cron.conf  /etc/supervisor/conf.d/
ADD ./setfiles/supervisor/nginx.conf /etc/supervisor/conf.d/
ADD ./setfiles/supervisor/php.conf   /etc/supervisor/conf.d/
ADD ./setfiles/supervisor/mysql.conf /etc/supervisor/conf.d/

###########################################################################
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
