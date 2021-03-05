 <?php
 
$mysqli = new mysql_connect("187.84.182.48","tabak865_admin","tabak865@123","tabak865_sgim");

/* check connection */
if (mysql_error()) {
    printf("Connect failed: %s\n", mysql_error());
    exit();
}

printf ("System status: %s\n", $mysqli->stat());

$mysqli->close();

echo 'Conexão estabelecida com sucesso';
?>