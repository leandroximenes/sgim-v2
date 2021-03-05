<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include("file_with_errors.php");


try {
    include("../conexao/conexao.php");
} catch (Exception $exc) {
    echo $exc->getMessage();
//echo $exc->getTraceAsString();
}


	