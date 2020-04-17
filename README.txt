MidWork v2.0 Beta

Requirements

PHP 7.2 or higher, MySQL PDO

Installation

1.
Use composer to install:
 require midmyk/midwork2:dev-master
or
 manually download zip archive and unpack it into private directory (not accessible from the web)

2. Move the contents of "public" folder to your public/web directory, for e.g. public_html and change path to your app/Config.php in public/index.php
3. Configure HOST (website url) in app/Config.php
4. Go to your website

HOW-TOs

1. How to specify custom port for my database connection?

Uncomment or add the following to your app/Config.php
$config['db_port'] = 1234;

Optionally add the following to your /.env to have different port for your local development
db_port = 1234