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

	function grupoCadastrar(){
		
		global $mySQL;
		
		if(isset($_POST['codGrupo'])){
			$codGrupo = $_POST['codGrupo'];
		}else{
			$codGrupo = 0;
		}
		
		$nome = $_POST['nome'];
		$sql = sprintf("call procGrupoCadastrar($codGrupo,'$nome')");
		
		try {
			if (!($rs = $mySQL->runQuery($sql))) {
				throw new Exception("Erro ao executar comando."); 
			}else{
				echo "{success:true}";
			}
		} catch (Exception $e) {
			echo "{failure:true}";
			exit;
		}
	}

	function grupoGerenciar() {
		
		global $mySQL;
		
		$codGrupo     = (integer) $_POST['codGrupo'];
		$status		   = (integer) $_POST['status'];

		$sql = sprintf("call procGrupoGerenciar($status, $codGrupo)");

		try {
			if (!($rs = $mySQL->runQuery($sql))) {
				throw new Exception("Erro ao executar comando."); 
			}else{
				echo "{success:true}";
			}
		} catch (Exception $e) {
			echo "{failure:true}";
			exit;
		}
	}


	function grupoListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procGrupoGeralListar()");
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
		case "grupoListar":
			grupoListar();
			break;

		case "grupoCadastrar":
			grupoCadastrar();
			break;

		case "grupoGerenciar":
			grupoGerenciar();
			break;	
	}



}else{
	header('location:login.php');
}	
?>