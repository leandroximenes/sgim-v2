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
	

	function contratoGerenciar() {
		
		global $mySQL;
		
		$codContrato  = (integer) $_POST['codContrato'];
		$status       = (integer) $_POST['statusContrato'];

		$sql = sprintf("call procContratoGerenciar($status, $codContrato)");

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

	function contratoUnicoListar(){
		global $mySQL;

		$codContrato  = (integer) $_POST['codContrato'];
		
		$sql = sprintf("CALL procContratoUnicoListar($codContrato)");
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

	function contratoListar(){
		global $mySQL;

		$sql = sprintf("CALL procContratoListar()");
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
	
	function atualizarVencimento(){
		global $mySQL;
		
		$codContrato = (int) $_POST['codContrato'];

		$sql = sprintf("CALL procContratoVencimentoAtualizar($codContrato)");
		$rs = $mySQL->runQuery($sql);
		$rsQuant = $rs->num_rows;
	}

	function contratoCadastrar(){
		
		global $mySQL;
		
		if($_POST['valor'] != "R$ 0,00"){
			$valor = str_replace("R$","",$_POST['valor']);	
			$valor = str_replace(".","",$valor);	
			$valor = str_replace(",",".",$valor);	
		}else{
			$valor = "0";
		}
		
		if($_POST['multaAtraso'] != "R$ 0,00"){
			$multaAtraso = str_replace("R$","",$_POST['multaAtraso']);	
			$multaAtraso = str_replace(".","",$multaAtraso);	
			$multaAtraso = str_replace(",",".",$multaAtraso);	
		}else{
			$multaAtraso = "0";
		}
		
		if($_POST['descontoPontualidade'] != "R$ 0,00"){
			$descontoPontualidade = str_replace("R$","",$_POST['descontoPontualidade']);	
			$descontoPontualidade = str_replace(".","",$descontoPontualidade);	
			$descontoPontualidade = str_replace(",",".",$descontoPontualidade);	
		}else{
			$descontoPontualidade = "0";
		}
		
		$codFuncionario       = $_SESSION["SISTEMA_codPessoa"];
		$codContrato          = (int) $_POST['codContrato'];
		$codProprietario      = (int) $_POST['codProprietario'];
		$codImovel            = (int) $_POST['codImovel'];
		$codContratante       = (int) $_POST['codContratante'];
		$codTipoServico       = (int) $_POST['codTipoServico'];
		$comissao             = $_POST['comissao'];
		$dataInicio           = (isset($_POST['dataInicio']) ? $_POST['dataInicio'] : 0);
		$qtdMeses             = (int) $_POST['qtdMeses'];
		$dataSeparada         = explode("/",$dataInicio);
		$dataInicioInvertida  = $dataSeparada[2]."/".$dataSeparada[1]."/".$dataSeparada[0];
		$dataFim			  = strftime("%Y/%m/%d", (strtotime($dataSeparada[2]."/".$dataSeparada[1]."/".$dataSeparada[0]. " " .$qtdMeses." month - 1 day")));
		$observacao			  = $_POST['observacoes'];
			
		$sql = sprintf("call procContratoCadastrar($codContrato,$codFuncionario,$codProprietario,$codImovel,$codContratante,$codTipoServico,'$comissao','$descontoPontualidade','$valor','$multaAtraso','$dataInicioInvertida','$dataFim',$qtdMeses,'$observacao')");
		
		try {
			if (!($rs = $mySQL->runQuery($sql))) {
				throw new Exception("Erro ao executar comando."); 
			}else{
		
				$rsLinha           = mysqli_fetch_assoc($rs);
				$codUltimoContrato = $rsLinha['codContrato'];

				for($i=1;$i<=$qtdMeses;$i++){
			
					if($i != $qtdMeses){
						$dataVencimento = strftime("%Y/%m/%d", (strtotime($dataSeparada[2]."/".$dataSeparada[1]."/".$dataSeparada[0]. " " .$i." month")));
					}else{
						$dataVencimento = strftime("%Y/%m/%d", (strtotime($dataSeparada[2]."/".$dataSeparada[1]."/".$dataSeparada[0]. " " .$i." month - 1 day")));
					}

					$sqlPagamento = sprintf("call procPagamentoCadastrar($codUltimoContrato,$i,'$dataVencimento', '$valor',  '$descontoPontualidade', '$multaAtraso')");
					$rsPagamento = $mySQL->runQuery($sqlPagamento);
				}

				echo "{success:true}";
		
		
			}
		} catch (Exception $e) {
			echo "{failure:true}";
			exit;
		}
	}



	function contratoEncerrar(){
		
		global $mySQL;
		
		$codContrato          = (int) $_POST['codContrato'];
		$dataEncerramento     = $_POST['data'];
		$observacao			  = $_POST['observacao'];
			
		$sql = sprintf("call procContratoEncerramento($codContrato,'$dataEncerramento','$observacao')");
		
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
									Recebe o parametro que indica que função irá ser executada
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
		case "contratoEncerrar":
			contratoEncerrar();
			break;
		
		case "contratoListar":
			contratoListar();
			break;

		case "contratoCadastrar":
			contratoCadastrar();
			break;
		
		case "contratoGerenciar":
			contratoGerenciar();
			break;

		case "contratoUnicoListar":
			contratoUnicoListar();
			break;
			
		case "atualizarVencimento":
			atualizarVencimento();
			break;		
	}

}else{
	header('location:login.php');
}	
?>