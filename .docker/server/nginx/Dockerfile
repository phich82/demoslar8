FROM nginx:latest

COPY /cert /usr/local/share/ssl
# COPY /etc/conf/magento243dev.conf /usr/local/etc/nginx/sites-available/magento243dev.conf
# COPY /etc/conf/nginx.conf /etc/nginx/nginx.conf
COPY /etc/conf/magento243dev.conf /etc/nginx/nginx.conf

COPY /supervisor/debian_ubuntu/etc/supervisor/conf.d/websockets.conf /etc/supervisor/conf.d/websockets.conf

RUN apt-get update && apt-get install -y vim

# Install supervisor
RUN apt install supervisor

# Add following line to /etc/hosts file
# echo '127.0.0.1     magento243.dev' >> /etc/hosts

# CMD ["ln", "-s", "/usr/local/etc/nginx/sites-available/magento243dev.conf", "/usr/local/etc/nginx/sites-enabled/magento243dev.conf"]
