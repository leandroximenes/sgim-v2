<?php
	include("../../conexao/conexao.php");

	global $mySQLAlert;

	$sql     = sprintf("call procInfoContratoVencimento30()");
	$rs      = $mySQL->runQuery($sql);
	$rsQuant = $rs->num_rows;

	if($rsQuant>0){
		echo true;
	}else{
		echo false;
	}
?>