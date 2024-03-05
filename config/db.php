<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db:3306;dbname=concursos',
    'username' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_PASSWORD'],
    'charset' => 'utf8',
];
