<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');
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


	function pagamentoObservacaoRepasseCadastrar(){
		global $mySQL;

		$codPagamento = $_POST['codPagamento'];
		$observacoes  = $_POST['observacoes'];		
		$sql          = sprintf("CALL procPagamentoObservacaoRepasseCadastrar($codPagamento,'$observacoes')");
		
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
	
	
	function pagamentoObservacaoCadastrar(){
		global $mySQL;

		$codPagamento = $_POST['codPagamento'];
		$observacoes  = $_POST['observacoes'];		
		$sql          = sprintf("CALL procPagamentoObservacaoCadastrar($codPagamento,'$observacoes')");
		
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


	function pagamentoObservacao(){
		global $mySQL;

		$codPagamento = $_POST['codPagamento'];
		$sql          = sprintf("CALL procPagamentoObservacaoListar($codPagamento)");
		
		$rs           = $mySQL->runQuery($sql);
		$rsQuant      = $rs->num_rows;
			
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


	
	function pagamentoObservacaoRepasse(){
		global $mySQL;

		$codPagamento = $_POST['codPagamento'];
		$sql          = sprintf("CALL procPagamentoObservacaoRepasseListar($codPagamento)");
		
		$rs           = $mySQL->runQuery($sql);
		$rsQuant      = $rs->num_rows;
			
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
	
	
	function pagamentoListar(){
		global $mySQL;

		$codContrato = $_POST['codContrato'];
		$sql = sprintf("CALL procPagamentoListar($codContrato)");
		
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
	
	function repasseListar(){
		global $mySQL;

		$codContrato = $_POST['codContrato'];
		$sql = sprintf("CALL procRepasseListar($codContrato)");
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

	function reajusteContratoCadastrar(){
		
		global $mySQL;
		
		$codContrato = $_POST['codContrato'];
		$valorAtual = (isset($_POST['valorAtual']) ? $_POST['valorAtual'] : 0);
		$valorAtual = str_replace(',','.',$valorAtual);
		$codPessoa = $_SESSION["SISTEMA_codPessoa"];

		$sql = sprintf("call procRajusteContratoCadastrar($codPessoa,$codContrato,$valorAtual)");
		
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


	function pagamentoGerenciar(){
		
		global $mySQL;
		
		if( $_POST['dataPagamento'] != ""){
			$dataPagamento = $_POST['dataPagamento'];
		}else{
			$dataPagamento  = 0;
		}
		
		$codContrato       = (int) $_POST['codContrato'];
		$codPagamento      = (int) $_POST['codPagamento'];
		$parcela           = (int) $_POST['parcela'];
		$dataVencimento    = $_POST['dataVencimento'];
		// $dataPagamento     = $_POST['dataPagamento'];
		$valorPagamento    = str_replace(',','.',$_POST['valorPagamento']);
		$valorDesconto     = str_replace(',','.',$_POST['valorDesconto']);
		$valorMulta        = str_replace(',','.',$_POST['valorMulta']);
		$valorGastoServico = str_replace(',','.',$_POST['valorGastoServico']);
		//$valorRepasse      = str_replace(',','.',$_POST['valorRepasse']);
		//$valorIR           = str_replace(',','.',$_POST['valorIR']);
		//$dataRepasse       = $_POST['dataPagamento'];
		$mesReferencia     = $_POST['mesReferencia'];

		
			
		$sql = sprintf("call procPagamentoGerenciar($codContrato,$codPagamento,$parcela,'$dataVencimento', '$dataPagamento', '$valorPagamento', '$valorDesconto', '$valorMulta', '$valorGastoServico', $mesReferencia )");
	
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
	
	function repasseGerenciar(){
		
		global $mySQL;
		
		if( $_POST['dataRepasse'] != ""){
			$dataRepasse = $_POST['dataRepasse'];
		}else{
			$dataRepasse  = 0;
		}
		
		$codContrato       = (int) $_POST['codContrato'];
		$codPagamento      = (int) $_POST['codPagamento'];
		$parcela           = (int) $_POST['parcela'];
		$comissao		   = str_replace(',','.',$_POST['comissao']);		
			
		$sql = sprintf("call procRepasseGerenciar($codContrato,$codPagamento,$parcela,'$dataRepasse', $comissao)");
		
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
		case "pagamentoListar":
			pagamentoListar();
			break;
		
		case "repasseListar":
			repasseListar();
			break;

		case "reajusteContratoCadastrar":
			reajusteContratoCadastrar();
			break;

		case "pagamentoGerenciar":
			pagamentoGerenciar();
			break;
		
		case "repasseGerenciar":
			repasseGerenciar();
			break;
		
		case "pagamentoObservacao":
			pagamentoObservacao();
			break;

		case "pagamentoObservacaoCadastrar":
			pagamentoObservacaoCadastrar();
			break;

		case "pagamentoObservacaoRepasse":
			pagamentoObservacaoRepasse();
			break;
			
		case "pagamentoObservacaoRepasseCadastrar":
			pagamentoObservacaoRepasseCadastrar();
			break;
	}

}else{
	header('location:login.php');
}	
?>