<?php

$pgData = [
    'host' => getenv('POSTGRES_HOST'),
    'port' => getenv('POSTGRES_PORT'),
    'username' => getenv('POSTGRES_USER'),
    'password' => getenv('POSTGRES_PASSWORD'),
    'db' => getenv('POSTGRES_DB'),
];

return [
    'class' => \yii\db\Connection::class,
    'dsn' => "pgsql:host={$pgData['host']};dbname={$pgData['db']}",
    'username' => $pgData['username'],
    'password' => $pgData['password'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
