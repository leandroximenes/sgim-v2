<?php 
session_start(); 
$_SESSION["teste"] = "teste de session";
echo "seзгo criada: "  . $_SESSION["teste"];
?>