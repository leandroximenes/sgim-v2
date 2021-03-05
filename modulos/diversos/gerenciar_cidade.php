<?php
session_start(); 
if(isset($_SESSION["SISTEMA_codPessoa"])){

	include("../../conexao/conexao.php");

	function JEncode($arr){
		if (version_compare(PHP_VERSION,"5.2","<")){    
			require_once("./JSON.php"); 
			$json = new Services_JSON();
			$data=$json->encode($arr);
		}else{
			//utf_prepare($arr);
			$data = json_encode($arr);
		}
		return $data;
	}
	
	//cadastrar uma nova cidade para um UF
	function cidadeCadastrar(){
		
		global $mySQL;
		
		if(isset($_POST['codUf']) || isset($_POST['cidade']) || isset($_POST['CidadeSelecionado'])){
			$codUf = $_POST['codUf'];
			$cidade = $_POST['cidade'];
			$CidadeSelecionado = $_POST['CidadeSelecionado'];
		}else{
			echo "{success:false}";
			exit;
		}
			
		$sql = sprintf("call procCidadeCadastrar($CidadeSelecionado,$codUf,'$cidade')");
		
		try {
			if (!($rs = $mySQL->runQuery($sql))) {
				throw new Exception("Erro ao executar comando."); 
			}else{
				echo "{success:true}";
			}
		} catch (Exception $e) {
			echo "{success:false}";
			exit;
		}
	}

	//ativa e desativa uma cidade
	function cidadeGerenciar() {
		
		global $mySQL;
		
		$codCidade  = (integer) $_POST['codCidade'];
		$status	    = (integer) $_POST['status'];

		$sql = sprintf("call procCidadeGerenciar($status, $codCidade)");

		try {
			if (!($rs = $mySQL->runQuery($sql))) {
				throw new Exception("Erro ao executar comando."); 
			}else{
				echo "{success:true}";
			}
		} catch (Exception $e) {
			echo "{success:false}";
			exit;
		}
	}

	function UFListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procUFListar()");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
			
		if($rsQuant>0){
			while ($rsLinha = mysqli_fetch_assoc($rs)) {
				$arr[] = $rsLinha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "results":""})';
		}
	}

	function cidadeListar(){
		global $mySQL;
		
		$codUf = $_POST['codUf'];
		
		if($codUf == ""){
			echo "{success:false}";
			exit;
		}
		
		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procCidadeListar($codUf)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
			
		if($rsQuant>0){
			while ($rsLinha = mysqli_fetch_assoc($rs)) {
				$arr[] = $rsLinha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}

	/*----------------------------------------------------------------------------------------------------------------------
	------------------------------------------------------------------------------------------------------------------------
									Recebe o parametro que indica que funчуo irс ser executada
	------------------------------------------------------------------------------------------------------------------------
	--------------------------------------------------------------------------------------------------------------------- */


	$acao = "";
	if(isset($_POST['acao'])){
		$acao = $_POST['acao'];
	}

	if(isset($_GET['acao'])){
		$acao = $_GET['acao'];
	}
	switch($acao){
		case "UFListar":
			UFListar();
			break;

		case "cidadeListar":
			cidadeListar();
			break;

		case "cidadeCadastrar":
			cidadeCadastrar();
			break;	
		
		case "cidadeGerenciar":
			cidadeGerenciar();
			break;
	}



}else{
	header('location:login.php');
}	
?>