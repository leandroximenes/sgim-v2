
<?php


/* Connect to a MySQL database using driver invocation */
$dsn = 'mysql:dbname=tabak865_sgim;host=187.84.182.48';
$user = 'tabak865_admin';
$password = 'tabak865@123';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

?>
