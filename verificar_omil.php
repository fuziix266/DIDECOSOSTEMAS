<?php
require 'vendor/autoload.php';

$adapter = new Laminas\Db\Adapter\Adapter([
    'driver' => 'Pdo_Mysql',
    'hostname' => 'localhost',
    'database' => 'dideco',
    'username' => 'root',
    'password' => ''
]);

$sql = new Laminas\Db\Sql\Sql($adapter);
$select = $sql->select('tramites')->where(['departamento_id' => 14]);
$statement = $sql->prepareStatementForSqlObject($select);
$result = $statement->execute();

echo "Trámites de OMIL (departamento_id = 14):\n\n";
foreach ($result as $row) {
    echo "- {$row['nombre']} (slug: {$row['slug']})\n";
}
