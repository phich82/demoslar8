# Create Databases
CREATE DATABASE IF NOT EXISTS `magento243`;

# Create user and grant rights
FLUSH PRIVILEGES;
CREATE USER 'admin'@'%' IDENTIFIED BY 'admin';
GRANT ALL ON magento243.* TO 'admin'@'%';
