<?php

define('DEBUG',true);
define('DB_NAME','LbvGikVYpQ'); //database name
define('DB_USER','LbvGikVYpQ'); //database user
define('DB_PASSWORD','SgJWRqEyJM'); //database password
define('DB_HOST','remotemysql.com'); //database host ** use IP address to avoid DNS lookup

define('DEFAULT_CONTROLLER','Home'); //default controller if one isnt defined yet
define('DEFAULT_LAYOUT','default'); //if no layout is set in the controller, use this layout;

define('PROOT','/API/'); //set this to '/' on a live server. P = Project

define('ACCESS_RESTRICTED','Restricted'); //controller name for the restricted redirect

define('SECRET_KEY','THjlodfshdeloinbsg');  // secret key can be a random string and keep in secret from anyone
define('ALGORITHM','HS256'); 

define('JWT_PROCESSING_ERROR',300);
define('ATHORIZATION_HEADER_NOT_FOUND',301);
define('ACCESS_TOKEN_ERRORS',302);
