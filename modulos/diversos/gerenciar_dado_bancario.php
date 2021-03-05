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
	
	function bancoListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procBancoListar()");
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

	function dadosBancariosCadastrar(){
		global $mySQL;
	
		if(isset($_POST['codPessoa'])){
			$codPessoa = $_POST['codPessoa'];
		}else{
			echo "{success:false}";
		}
		
		if($_POST['codDadoBancario'] == ""){
			$codDadoBancario = 0;
		}else{
			$codDadoBancario = $_POST['codDadoBancario'];
		}
		
		$codPessoa			= $codPessoa;                  
		$codBanco		   	= (int) $_POST['codBanco'];
		$conta			   	= (string) $_POST['tfConta'];
		$agencia		   	= (string) $_POST['tfAgencia'];
		$observacoesDadoBancario  	= $_POST['txObservacoesDadoBancario'];
						
		//Cadastrar dados bancсrios
		$sql = sprintf("call procDadosBancariosCadastrar($codDadoBancario,$codPessoa,$codBanco,'$conta','$agencia','$observacoesDadoBancario')");

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
	}//fim da funчуo
	
	function carregarDadosBancarios(){
		global $mySQL;
		
		if(isset($_POST['codPessoa'])){
			$codPessoa = $_POST['codPessoa'];
		}else{
			echo "{success:false}";
			exit;
		}
		
		//verifica se jс existe dado bancario cadastrado para o usuario
		$sql = sprintf("call procDadosBancariosListar($codPessoa)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
		
		if($rsQuant>0){
			while ($linha = mysqli_fetch_assoc($rs)) {
				$arr[] = $linha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo "{success:false}";
			exit;
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
		case "bancoListar":
			bancoListar();
			break;
		case "dadosBancariosCadastrar";
			dadosBancariosCadastrar();
			break;
		case "carregarDadosBancarios";
			carregarDadosBancarios();
			break;
	}
}else{
	header('location:login.php');
}	
?>