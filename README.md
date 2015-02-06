ERS (EJC Registration System)
=============================

Introduction
------------

The EJC Registration System has these main tasks:
1. Give the EJC core orga team the ability to configure all needed tickets easily. (Admin)
1. Give jugglers the possibility to buy tickets for the European Juggling Convention. (PreReg)
2. Give the organisation team onsite the possibility to easily check what the participants have booked. (OnsiteReg)
3. Create needed statistics after the EJC. (Stats)

Installation instructions
-------------------------

### 1. Get a copy of the project:

```
$ git clone https://github.com/inbaz/ers
```

### 2. Create a VirtualHost running PHP 

We tested on PHP 5.5, maybe 5.4 is working aswell, 5.3 doesn't

### 3. Create a mysql database and user

```
mysql> CREATE DATABASE ers CHARACTER SET utf8 COLLATE utf8_bin;
mysql> GRANT ALL PRIVILEGES ON ers.* TO 'ers'@'localhost' IDENTIFIED BY 'CHANGE_ME';
mysql> exit;
```

### 4. Install other components via composer

```
$ php composer.phar install
```    

### 5. Generate database scheme

```
$ php vendor/bin/doctrine-module orm:validate-schema
$ php vendor/bin/doctrine-module orm:schema-tool:create
$ php vendor/bin/doctrine-module orm:schema-tool:update --force
```

### 6. Load database example data (optional)

```
mysql < install/ers-insterts.sql
```

Server Administration Information
---------------------------------

### 1. Firewall

    iptables-save
```
# Generated by iptables-save v1.4.7 on Wed Jan 21 09:57:17 2015
*filter
:INPUT ACCEPT [0:0]
:FORWARD ACCEPT [0:0]
:OUTPUT ACCEPT [8190:1147926]
-A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
-A INPUT -p icmp -j ACCEPT
-A INPUT -i lo -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 20 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 21 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 22 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 65495:65535 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 80 -j ACCEPT
-A INPUT -p tcp -m state --state NEW -m tcp --dport 443 -j ACCEPT
-A INPUT -j REJECT --reject-with icmp-host-prohibited
-A FORWARD -j REJECT --reject-with icmp-host-prohibited
COMMIT
# Completed on Wed Jan 21 09:57:17 2015
```

### 2. Webserver

### 3. MySQL server

```
query_cache_size    = 8M
tmp_table_size      = 16M
max_heap_table_size = 16M
thread_cache_size   = 4
table_open_cache    = 64
```