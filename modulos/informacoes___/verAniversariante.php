<?php
	include("../../conexao/conexao.php");

	global $mySQLAlert;

	$sql     = sprintf("call procPessoaAniversarioDiaListar()");
	$rs      = $mySQL->runQuery($sql);
	$rsQuant = $rs->num_rows;

	if($rsQuant>0){
		echo true;
	}else{
		echo false;
	}
?>