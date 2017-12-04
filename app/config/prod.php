<?php
//On indique à l'application les identifiants de connection
$app['db.options'] = array(
	'driver' => 'pdo_mysql',
	'charset' => 'utf8',
	'host' => 'localhost',
	'port' => '3306',
	'dbname' => 'blogsilex',
	'user' => 'root',
	'password' => '',
);

$app['upload_dir'] = 'web/uploads';