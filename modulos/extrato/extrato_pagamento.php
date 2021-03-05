<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

header("Content-type: application/vnd.ms-word");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=extrato_repasse_locacao.doc");
header("Pragma: no-cache");

$titulo = 'Extrato de Repasse de Locaчуo';

if (isset($_SESSION["SISTEMA_codPessoa"])) {
    $codPagamento = $_GET['codPagamento'];
    
    include("../../conexao/conexao.php");
    include("../../php/php.php");
    include("../diversos/util.php");
    
    include_once 'conteudo_pagamento.php';

} else {
    header('location:login.php');
}
?>