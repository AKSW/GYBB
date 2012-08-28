<?php

// different javascript-inclusion etc.
define('DEBUG', true);

// where your base belongs to us
define('BASE_URL', 'http://getyourbikeback.webgefrickel.de/');

// where all the RDF goes
define('DEFAULT_LGD_GRAPH', 'http://getyourbikeback.webgefrickel.de/');
define('BASE_ENDPOINT_URL', 'http://127.0.0.1');
define('BASE_ENDPOINT_PORT', '8181');

// facebook config
define('APP_ID', '388573504514465');
define('APP_SECRET', '66c2db5c7e004fac135cd1a6057dff86');

// database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'julian');
define('DB_PASSWORD', '8CUxDz7zGuA}$g');
define('DB_DATABASE', 'lgd');

//virtuoso configuration
define('VOS_DSN', 'VOS');
define('VOS_USER', 'dba');
define('VOS_PASSWORD', 'dba');

define('MAX_UPLOAD_SIZE', 1024 * 1024); // 1 MB
define('MIME_TYPE', 'image/jpeg');
define('UPLOAD_FOLDER', '/var/www/getyourbikeback/web/uploads/'); // absolute server path

?>
