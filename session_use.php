<?php
session_start(); 
var_dump($_SESSION);
var_dump($_SESSION["teste"]);

if(isset($_SESSION["teste"])){
    echo '<br /><br />Sessão funcionando!';
}else{
    echo '<br /><br />Sessão não funcionou!';
}