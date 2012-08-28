<?php

// different javascript-inclusion etc.
define('DEBUG', true);

// where your base belongs to us
// NOTE if you change your baseurl you should change the RESOURCE_GRAPH and
// other graph values and some prefixes in the classes/sparqlConstants.php too
define('BASE_URL', 'http://demo.aksw.org/');
define('WEB_BASE_URL', 'http://demo.aksw.org/');
// where all the RDF goes
define('RESOURCE_GRAPH', 'http://demo.aksw.org/resource/');
define('ONTOLOGY_GRAPH', 'http://demo.aksw.org/ontology/');
define('DATACUBE_GRAPH', 'http://demo.aksw.org/datacube/');
define('VOID_GRAPH', 'http://demo.aksw.org/void/');

define('BASE_ENDPOINT_URL', 'http://127.0.0.1');
define('BASE_ENDPOINT_PORT', '8890');

// facebook config -- add your app ID and secret here
define('APP_ID', '');
define('APP_SECRET', '');

// virtuoso configuration -- this is the default config
define('VOS_DSN', 'VOS');
define('VOS_USER', 'dba');
define('VOS_PASSWORD', 'dba');

define('MAX_UPLOAD_SIZE', 1024 * 1024); // 1 MB
define('MIME_TYPE', 'image/jpeg');
define('UPLOAD_FOLDER', '/var/www/uploads/'); // absolute server path

// security token for curl cronjobs, add the following parameter as token
// to run the action=statistics command.
define('CRON_JOB_TOKEN', 'z2lIM45Yrs');

// number of days for statistical datasets
define('STATISTICS_HISTORY_DAYS', 180);
?>
