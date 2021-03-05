<?php
$servidor = "tabakalimoveis.com.br"; /* maquina a qual o banco de dados est?¿½ */
$usuario = "tabak865_admin"; /* usuario do banco de dados MySql */
$senha = "tabak865@123"; /* senha do banco de dados MySql */
$banco = "tabak865_sgin"; /* seleciona o banco a ser usado */

$conexao = mysql_connect($servidor, $usuario, $senha);  /* Conecta no bando de dados MySql */

mysql_select_db($banco); /* seleciona o banco a ser usado */
