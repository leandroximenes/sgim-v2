<?php
	session_start(); 
	if(isset($_SESSION["SISTEMA_codPessoa"])){

	include("../../conexao/conexao.php");

	@ini_set("display_errors", 1);
	@ini_set("log_errors", 1);
	@ini_set("error_reporting", E_ALL);

	function JEncode($arr){
		if (version_compare(PHP_VERSION,"5.2","<"))
		{    
			require_once("./JSON.php");   //if php<5.2 need JSON class
			$json = new Services_JSON();  //instantiate new json object
			$data=$json->encode($arr);    //encode the data in json format
		} else
		{
			//utf_prepare($arr);
			$data = json_encode($arr);    //encode the data in json format
		}
		return $data;
	}

	function tipoImovelListar(){
		global $mySQL;
		
		$sql = sprintf("CALL procTipoImovelListar()");
		$rsRemote = $mySQL->runQuery($sql);
		$rsRemoteRowCount = $rsRemote->num_rows;
			
		if($rsRemoteRowCount>0){
			while ($rsRemoteRow = mysqli_fetch_assoc($rsRemote)) {
				$arr[] = $rsRemoteRow;
			}
			$jsonresult = JEncode($arr);
			echo '({"total":"'.$rsRemoteRowCount.'","resultado":'.$jsonresult.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}

	function imovelProprietarioListar() {
		
		global $mySQL;
		
		$codProprietario = $_POST['codProprietario'];
		#Consulta os Monitoramentos remotos
		
		$sql = sprintf("call procImovelProprietarioListar($codProprietario)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
			
		if($rsQuant>0){
			while ($linha = mysqli_fetch_assoc($rs)) {
				$arr[] = $linha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}

	function imovelUnicoListar() {
		
		global $mySQL;
		
		$codImovel = $_POST['codImovel'];
		#Consulta os Monitoramentos remotos
		
		$sql = sprintf("call procImovelUnicoListar($codImovel)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
			
		if($rsQuant>0){
			while ($linha = mysqli_fetch_assoc($rs)) {
				$arr[] = $linha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}


	function imovelListar() {
		
		global $mySQL;

		
		$sql = sprintf("call procImovelListar()");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
			
		if($rsQuant>0){
			while ($linha = mysqli_fetch_assoc($rs)) {
				$arr[] = $linha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}


	function imovelGerenciar($acao) {
		
		global $mySQL;
		
		$codImovel = (integer) (isset($_POST['codImovel']) ? $_POST['codImovel'] : $_GET['codImovel']);

		$sql = sprintf("call procImovelGerenciar($acao, $codImovel)");

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

	function imovelSumarioListar() {
		
		global $mySQL;
		
		$codContrato = (integer) (isset($_POST['codigoContrato']) ? $_POST['codigoContrato'] : $_GET['codigoContrato']);
	
		$sql = sprintf("call procSumarioListar($codContrato)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;

		if($rsQuant>0){
			while ($linha = mysqli_fetch_assoc($rs)) {
				$arr[] = $linha;
			}
			$json = JEncode($arr);
			echo '({"total":"'.$rsQuant.'","resultado":'.$json.'})';
		} else {
			echo '({"total":"0", "resultado":""})';
		}
	}
	
	function sumarioCadastrar(){
		
		global $mySQL;
		
		if(isset($_POST['codigoContrato'])){
			$codContrato = $_POST['codigoContrato'];
		}else{
			$codContrato = 0;
		}
		$codSumario			= $_POST['codSumario'];
		$observacao 		= (isset($_POST['observacao']) ? str_replace("'","\"",str_replace("%","%%",$_POST['observacao'])) : '');

		$sql = sprintf("call procSumarioCadastrar($codSumario, $codContrato, '$observacao')");
				
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

	function imovelCadastrar(){
		
		global $mySQL;
		
		if(isset($_POST['codImovel'])){
			$codImovel = $_POST['codImovel'];
		}else{
			$codImovel = 0;
		}
		
		//retirar o R$ dos valores
		$valor = str_replace("R$","",$_POST['valor']);	
		$valor = str_replace(".","",$valor);	
		$valor = str_replace(",",".",$valor);	
		
		$condominio = str_replace("R$","",$_POST['condominio']);	
		$condominio = str_replace(".","",$condominio);	
		$condominio = str_replace(",",".",$condominio);	
		
		$codProprietario 	= (int) $_POST['codProprietario'];
		$codTipoImovel 		= $_POST['codTipoImovel'];
		$codTipoServico 	= $_POST['codTipoServico'];
		$endereco 		= $_POST['endereco'];
		$bairro 		= $_POST['bairro'];
		$cep 			= str_replace("-","",$_POST['cep']);
		$telefoneCondominio 	= $_POST['telefoneCondominio'];
		$telefoneSindico 	= $_POST['telefoneSindico'];
		$cidade 		= $_POST['cidade'];
		$areaPrivativa 		= str_replace(',','.',$_POST['areaPrivativa']);
		$areaComum		= str_replace(',','.',$_POST['areaComum']);
		$dce 			= $_POST['dce'];
		$uf 			= $_POST['uf'];
		$valor 			= $valor;
		$latitude 		= $_POST['latitude'];
		$longitude 		= $_POST['longitude'];
		$condominio 	= $condominio;
		$quartos 		= (int) $_POST['quartos'];
		$suites 		= (int) $_POST['suites'];
		$banheiros 		= (int) $_POST['banheiros'];
		$garagem 		= (int) $_POST['garagem'];
		$nIptu 			=  $_POST['nIptu'];
		$nCeb 			=  $_POST['nCeb'];
		$nCaesb 		=  $_POST['nCaesb'];
		$observacao 		= $_POST['observacao'];

		$sql = sprintf("call procImovelCadastrar($codImovel, $codProprietario,$codTipoImovel, $codTipoServico, '$endereco', '$bairro', '$cep', '$uf', '$cidade', '$areaPrivativa', '$areaComum', $dce, $valor, $condominio, $quartos, $suites, $banheiros, '$telefoneCondominio', '$telefoneSindico', $garagem, '$nIptu','$nCeb', '$nCaesb', '$observacao', '$latitude','$longitude')");

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


	/*- --------------------------------------------------------------------------------------------------------------------
	------------------------------------------------------------------------------------------------------------------------
									Recebe o parametro que indica que funчуo irс ser executada
	------------------------------------------------------------------------------------------------------------------------
	--------------------------------------------------------------------------------------------------------------------- */

	if ( isset($_POST['acao'])){
		$acao = $_POST['acao'];
	}

	if ( isset($_GET['acao'])){
		$acao = $_GET['acao'];
	}
	switch($acao){
		case "imovelListar":
			imovelListar();
			break;
		case "listarTipoImovel":
			tipoImovelListar();
			break;
		case "imovelDesativar":
			imovelGerenciar(0);
			break;
		case "imovelAtivar":
			imovelGerenciar(1);
			break;
		case "imovelCadastrar":
			imovelCadastrar();
			break;
		case "imovelUnicoListar":
			imovelUnicoListar();
			break;
		case "imovelProprietarioListar":
			imovelProprietarioListar();
			break;
		case "imovelSumarioListar":
			imovelSumarioListar();
			break;
		case "cadastrarSumario":
			sumarioCadastrar();
			break;
		default:
			echo "{failure:true}";
			break;
	}

}else{
	header('location:login.php');
}	
?>