<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

include "../diversos/util.php";

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

	function proprietarioUnicoListar(){
		global $mySQL;

		$codProprietario = $_POST['codProprietario'];
		$sql = sprintf("CALL procPessoaProprietarioUnicoListar($codProprietario)");
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

	function proprietarioListar(){
		global $mySQL;

		$sql = sprintf("CALL procPessoaProprietarioListar()");
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

	function locadorListar(){
		global $mySQL;

		$sql = sprintf("CALL procPessoaLocadorListar()");
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

	function pessoaGerenciar() {
		
		global $mySQL;
		
		$codPessoa     = (integer) $_POST['codPessoa'];
		$statusUsuario = (integer) $_POST['statusUsuario'];

		$sql = sprintf("call procPessoaGerenciar($statusUsuario, $codPessoa)");

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

	function pessoaVwListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("SELECT `p`.`codPessoa` AS `codPessoa`,
						`p`.`codTipoPessoa` AS `codTipoPessoa`,
						`p`.`nome` AS `nome`,
						`p`.`email` AS `email`,
						`p`.`senha` AS `senha`,
						`p`.`endereco` AS `endereco`,
						(
							SELECT
								concat(
									`residencial`.`ddd`,
									`residencial`.`telefone`
								) AS `concat(``residencial``.``ddd``,``residencial``.``telefone``)`
							FROM
								`telefone` `residencial`
							WHERE
								(
									(
										`residencial`.`codPessoa` = `p`.`codPessoa`
									)
									AND (
										`residencial`.`codTipoTelefone` = 1
									)
								)
							LIMIT 1
						) AS `residencial`,
						(
							SELECT
								concat(
									`celular`.`ddd`,
									`celular`.`telefone`
								) AS `concat(``celular``.``ddd``,``celular``.``telefone``)`
							FROM
								`telefone` `celular`
							WHERE
								(
									(
										`celular`.`codPessoa` = `p`.`codPessoa`
									)
									AND (
										`celular`.`codTipoTelefone` = 2
									)
								)
							LIMIT 1
						) AS `celular`,
						(
							SELECT
								concat(
									`comercial`.`ddd`,
									`comercial`.`telefone`
								) AS `concat(``comercial``.``ddd``,``comercial``.``telefone``)`
							FROM
								`telefone` `comercial`
							WHERE
								(
									(
										`comercial`.`codPessoa` = `p`.`codPessoa`
									)
									AND (
										`comercial`.`codTipoTelefone` = 3
									)
								)
							LIMIT 1
						) AS `comercial`,
						`p`.`cep` AS `cep`,
						`p`.`dataCadastro` AS `dataCadastro`,
						`p`.`dataExclusao` AS `dataExclusao`,
						`p`.`bairro` AS `bairro`,
						`p`.`cidade` AS `cidade`,
						`p`.`observacao` AS `observacao`,
						`p`.`uf` AS `uf`,
						`p`.`status` AS `status`,
						`pf`.`empresaTrabalho` AS `empresaTrabalho`,
						`es`.`codEstadoCivil` AS `codEstadoCivil`,
						`es`.`nome` AS `estadoCivil`,
						`ps`.`nome` AS `profissao`,
						_utf8 'Física' AS `tipoPessoa`,
						`pf`.`enderecoTrabalho` AS `enderecoTrabalho`,
						`pf`.`bairroTrabalho` AS `bairroTrabalho`,
						`pf`.`cidadeTrabalho` AS `cidadeTrabalho`,
						`pf`.`ufTrabalho` AS `ufTrabalho`,
						`pf`.`cepTrabalho` AS `cepTrabalho`,
						`pf`.`dataNascimento` AS `dataNascimento`,
						`pf`.`renda` AS `renda`,
						`pf`.`cpf` AS `cpf`,
						`pf`.`rg` AS `rg`,
						_utf8 '' AS `cnpj`,
						_utf8 '' AS `ie`,
						`pf`.`orgaoExpedidor` AS `orgaoExpedidor`,
						`pf`.`outroRendimento` AS `outroRendimento`,
						`pf`.`nacionalidade` AS `nacionalidade`,
						`pf`.`codProfissao` AS `codProfissao`,
						`pf`.`sexo` AS `codSexo`,
						 case WHEN `pf`.`sexo` = 'f' then 'feminino' else 'masculino' end AS 'tipoSexo',
						`pf`.`tituloEleitor` AS `tituloEleitor`,
						_utf8 '' AS `nomeRepresentante`,
						_utf8 '' AS `cpfRepresentante`,
						_utf8 '' AS `dataNascimentoRepresentante`,
						_utf8 '' AS `codProfissaoRepresentante`,
						_utf8 '' AS `orgaoExpedidorRepresentante`,
						_utf8 '' AS `outroRendimentoRepresentante`,
						_utf8 '' AS `rendaRepresentante`,
						_utf8 '' AS `rgRepresentante`,
						_utf8 '' AS `codEstadoCivilRepresentante`,
						_utf8 '' AS `estadoCivilRepresentante`,
						_utf8 '' AS `profissaoRepresentante`,
						`e`.`codConjuge` AS `codConjuge`,
						`e`.`codProfissao` AS `codProfissaoConjuge`,
						`e`.`cpf` AS `cpfConjuge`,
						`e`.`dataNascimento` AS `dataNascimentoConjuge`,
						`e`.`nacionalidade` AS `nacionalidadeConjuge`,
						`e`.`nome` AS `nomeConjuge`,
						`e`.`orgaoExpedidor` AS `orgaoExpedidorConjuge`,
						`e`.`outroRendimento` AS `outroRendimentoConjuge`,
						`e`.`renda` AS `rendaConjuge`,
						`e`.`rg` AS `identidadeConjuge`,
						`ps1`.`nome` AS `profissaoConjuge`
					FROM
						(
							(
								(
									(
										(
											`pessoa` `p`
											JOIN `pessoaFisica` `pf` ON (
												(
													`p`.`codPessoa` = `pf`.`codPessoa`
												)
											)
										)
										JOIN `profissao` `ps` ON (
											(
												`pf`.`codProfissao` = `ps`.`codProfissao`
											)
										)
									)
									JOIN `estadoCivil` `es` ON (
										(
											`es`.`codEstadoCivil` = `pf`.`codestadoCivil`
										)
									)
								)
								LEFT JOIN `conjuge` `e` ON (
									(
										`e`.`codPessoa` = `p`.`codPessoa`
									)
								)
							)
							LEFT JOIN `profissao` `ps1` ON (
								(
									`e`.`codProfissao` = `ps1`.`codProfissao`
								)
							)
						)
					UNION
						SELECT
							`p`.`codPessoa` AS `codPessoa`,
							`p`.`codTipoPessoa` AS `codTipoPessoa`,
							`p`.`nome` AS `nome`,
							`p`.`email` AS `email`,
							`p`.`senha` AS `senha`,
							`p`.`endereco` AS `endereco`,
							(
								SELECT
									concat(
										`residencial`.`ddd`,
										`residencial`.`telefone`
									) AS `concat(``residencial``.``ddd``,``residencial``.``telefone``)`
								FROM
									`telefone` `residencial`
								WHERE
									(
										(
											`residencial`.`codPessoa` = `p`.`codPessoa`
										)
										AND (
											`residencial`.`codTipoTelefone` = 1
										)
									)
								LIMIT 1
							) AS `residencial`,
							(
								SELECT
									concat(
										`celular`.`ddd`,
										`celular`.`telefone`
									) AS `concat(``celular``.``ddd``,``celular``.``telefone``)`
								FROM
									`telefone` `celular`
								WHERE
									(
										(
											`celular`.`codPessoa` = `p`.`codPessoa`
										)
										AND (
											`celular`.`codTipoTelefone` = 2
										)
									)
								LIMIT 1
							) AS `celular`,
							(
								SELECT
									concat(
										`comercial`.`ddd`,
										`comercial`.`telefone`
									) AS `concat(``comercial``.``ddd``,``comercial``.``telefone``)`
								FROM
									`telefone` `comercial`
								WHERE
									(
										(
											`comercial`.`codPessoa` = `p`.`codPessoa`
										)
										AND (
											`comercial`.`codTipoTelefone` = 3
										)
									)
								LIMIT 1
							) AS `comercial`,
							`p`.`cep` AS `cep`,
							`p`.`dataCadastro` AS `dataCadastro`,
							`p`.`dataExclusao` AS `dataExclusao`,
							`p`.`bairro` AS `bairro`,
							`p`.`cidade` AS `cidade`,
							`p`.`observacao` AS `observacao`,
							`p`.`uf` AS `uf`,
							`p`.`status` AS `status`,
							_utf8 '' AS `empresaTrabalho`,
							_utf8 '' AS `codEstadoCivil`,
							_utf8 '' AS `estadoCivil`,
							_utf8 '' AS `profissao`,
							_utf8 'Jurídica' AS `tipoPessoa`,
							_utf8 '' AS `enderecoTrabalho`,
							_utf8 '' AS `bairroTrabalho`,
							_utf8 '' AS `cidadeTrabalho`,
							_utf8 '' AS `ufTrabalho`,
							_utf8 '' AS `cepTrabalho`,
							_utf8 '' AS `dataNascimento`,
							_utf8 '' AS `renda`,
							_utf8 '' AS `cpf`,
							_utf8 '' AS `rg`,
							`pj`.`cnpj` AS `cnpj`,
							`pj`.`ie` AS `ie`,
							_utf8 '' AS `orgaoExpedidor`,
							_utf8 '' AS `outroRendimento`,
							_utf8 '' AS `nacionalidade`,
							_utf8 '' AS `codProfissao`,
                                                        _utf8 '' AS `codSexo`,
                                                        _utf8 '' AS `tipoSexo`,
							_utf8 '' AS `tituloEleitor`,
							`rl`.`nome` AS `nomeRepresentante`,
							`rl`.`cpf` AS `cpfRepresentante`,
							`rl`.`dataNascimento` AS `dataNascimentoRepresentante`,
							`rl`.`codProfissao` AS `codProfissaoRepresentante`,
							`rl`.`orgaoExpedidor` AS `orgaoExpedidorRepresentante`,
							`rl`.`outroRendimento` AS `outroRendimentoRepresentante`,
							`rl`.`renda` AS `rendaRepresentante`,
							`rl`.`rg` AS `rgRepresentante`,
							`es`.`codEstadoCivil` AS `codEstadoCivilRepresentante`,
							`es`.`nome` AS `estadoCivilRepresentante`,
							`ps`.`nome` AS `profissaoRepresentante`,
							_utf8 '' AS `codConjuge`,
							_utf8 '' AS `codProfissaoConjuge`,
							_utf8 '' AS `cpfConjuge`,
							_utf8 '' AS `dataNascimentoConjuge`,
							_utf8 '' AS `nacionalidadeConjuge`,
							_utf8 '' AS `nomeConjuge`,
							_utf8 '' AS `orgaoExpedidorConjuge`,
							_utf8 '' AS `outroRendimentoConjuge`,
							_utf8 '' AS `rendaConjuge`,
							_utf8 '' AS `identidadeConjuge`,
							_utf8 '' AS `profissaoConjuge`
						FROM
							(
								(
									(
										(
											`pessoa` `p`
											JOIN `pessoaJuridica` `pj` ON (
												(
													`p`.`codPessoa` = `pj`.`codPessoa`
												)
											)
										)
										LEFT JOIN `representanteLegal` `rl` ON (
											(
												`p`.`codPessoa` = `rl`.`codPessoa`
											)
										)
									)
									LEFT JOIN `profissao` `ps` ON (
										(
											`rl`.`codProfissao` = `ps`.`codProfissao`
										)
									)
								)
								LEFT JOIN `estadoCivil` `es` ON (
									(
										`es`.`codEstadoCivil` = `rl`.`codestadoCivil`
									)
								)
							)
						ORDER BY
							`nome`");
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

	function estadoCivilListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procEstadoCivilListar()");
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

	function profissaoListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$sql = sprintf("CALL procProfissaoListar()");
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

	function fiadorListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$codContrato = $_POST['codContrato'];
		$sql = sprintf("CALL procContratoFiadorListar($codContrato)");
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
	
	function fiadorSelecionar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$codContrato = $_POST['codContrato'];
		$sql = sprintf("CALL procContratoFiadorSelecionar($codContrato)");
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

	function pessoaGrupoListar(){
		global $mySQL;

		#Consulta os Monitoramentos remotos
		$codPessoa = $_POST['codPessoa'];
		$sql = sprintf("CALL procPessoaGrupoListar($codPessoa)");
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

	function contratoFiadorRelacionar(){
		global $mySQL;
		
		$codFiador = $_POST['codFiador'];
		$codContrato = $_POST['codContrato'];
		$op = $_POST['op'];
		
		$sql = sprintf("call contratoFiadorRelacionar($codFiador,$codContrato,$op)");

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
	
	function pessoaGrupoRelacionar(){
		global $mySQL;
		
		$codPessoa = $_POST['codPessoa'];
		$codGrupo = $_POST['codGrupo'];
		$op = $_POST['op'];
		
		$sql = sprintf("call procPessoaGrupoRelacionar($codPessoa,$codGrupo,$op)");

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

	function conjugeUnicoListar() {
		
		global $mySQL;
		
		$codPessoa = $_POST['codPessoa'];
		#Consulta os Monitoramentos remotos
		
		$sql = sprintf("call procPessoaConjugeListar($codPessoa)");
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

	function pessoaUnicoListar() {
		
		global $mySQL;
		
		$codPessoa = $_POST['codPessoa'];
		#Consulta os Monitoramentos remotos
		
		$sql = sprintf("call procPessoaListarUnico($codPessoa)");
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

	function conjugeCadastrar(){
				
		global $mySQL;
		
		//replace no . e - da masccara do cpf
		$cpf = str_replace(".", "", $_POST['cpf']);
		$cpf = str_replace("-", "", $cpf);
		
		if($cpf != ""){
			$cpfValido = validaCPF($cpf);
		
			if($cpfValido == false){
				echo "{cpf:false}";
				exit;
			}
		}
		
		//retirar o R$ dos valores
		if($_POST['renda'] != "R$ 0,00"){
			$renda = str_replace("R$","",$_POST['renda']);	
			$renda = str_replace(".","",$renda);	
			$renda = str_replace(",",".",$renda);	
		}else{
			echo "{renda:false}";
			exit;
		}
		//retirar o R$ dos valores
		$outroRendimento = str_replace("R$","",$_POST['outroRendimento']);	
		$outroRendimento = str_replace(".","",$outroRendimento);	
		$outroRendimento = str_replace(",",".",$outroRendimento);	
		
		
		$codPessoa       = $_POST['codPessoa'];
		$nome            = $_POST['nome'];
		$rg              = (int) (isset($_POST['rg']) ? $_POST['rg'] : 0);
		$codProfissao    = (int) $_POST['codProfissao'];
		$orgaoExpedidor  = (isset($_POST['orgaoExpedidor']) ? $_POST['orgaoExpedidor'] : "");
		$renda           = $renda;
		$outroRendimento = $outroRendimento;
		$nacionalidade   = (isset($_POST['nacionalidade']) ? $_POST['nacionalidade'] : "");
		$dataNascimento  =  (isset($_POST['dataNascimento']) ? $_POST['dataNascimento'] : 0);


		$sql = sprintf("call procPessoaConjugeCadastrar($codPessoa,'$nome','$cpf','$rg',$codProfissao,'$orgaoExpedidor','$renda','$outroRendimento','$dataNascimento','$nacionalidade')");

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
	
	function pessoaCadastrar(){
		
		global $mySQL;
		
		if(isset($_POST['codPessoa'])){
			$codPessoa = $_POST['codPessoa'];
		}else{
			$codPessoa = 0;
		}
		
		//replace no . e - da masccara do cpf e cnpj
		$cpf = str_replace(".", "", $_POST['cpf']);
		$cpf = str_replace("-", "", $cpf);
		
		if($cpf != ""){
			$cpfValido = validaCPF($cpf);
		
			if($cpfValido == false){
				echo "{cpf:false}";
				exit;
			}
		}
		
		if(isset($_POST['cpfConjuge'])){
			$cpfConjuge = str_replace(".", "", $_POST['cpfConjuge']);
			$cpfConjuge = str_replace("-", "", $cpfConjuge);
			
			if($cpfConjuge != ""){
				$cpfValido = validaCPF($cpfConjuge);
			
				if($cpfValido == false){
					echo "{cpfConjuge:false}";
					exit;
				}
			}
		}
		
		$cpfRepresentante = str_replace(".", "", $_POST['cpfRepresentante']);
		$cpfRepresentante = str_replace("-", "", $cpfRepresentante);
		
		
		
		if($cpfRepresentante != ""){
			$cpfRepresentanteValido = validaCPF($cpfRepresentante);
			
			if($cpfRepresentanteValido == false){
				echo "{cpfRepresentante:false}";
				exit;
			}
		}
		
		$cnpj = str_replace(".", "", $_POST['cnpj']);
		$cnpj = str_replace("/","", $cnpj);
		$cnpj = str_replace("-" ,"" , $cnpj);
		
		//retirar o R$ dos valores
		$renda = str_replace("R$","",$_POST['renda']);	
		$renda = str_replace(".","",$renda);	
		$renda = str_replace(",",".",$renda);	
		
		$outroRendimento = str_replace("R$","",$_POST['outroRendimento']);
		$outroRendimento = str_replace(".","",$outroRendimento);
		$outroRendimento = str_replace(",",".",$outroRendimento);
		
		$rendaRepresentante = str_replace("R$","",$_POST['rendaRepresentante']);
		$rendaRepresentante = str_replace(".","",$rendaRepresentante);
		$rendaRepresentante = str_replace(",",".",$rendaRepresentante);	
		
		$outroRendimentorepresentante = str_replace("R$","",$_POST['outroRendimentorepresentante']);
		$outroRendimentorepresentante = str_replace(".","",$outroRendimentorepresentante);
		$outroRendimentorepresentante = str_replace(",",".",$outroRendimentorepresentante);
		
		$rendaConjuge = str_replace("R$","",$_POST['rendaConjuge']);	
		$rendaConjuge = str_replace(".","",$rendaConjuge);	
		$rendaConjuge = str_replace(",",".",$rendaConjuge);	
		
		$outroRendimentoConjuge = str_replace("R$","",$_POST['outroRendimentoConjuge']);
		$outroRendimentoConjuge = str_replace(".","",$outroRendimentoConjuge);
		$outroRendimentoConjuge = str_replace(",",".",$outroRendimentoConjuge);
		
		
		IF($_POST['telefoneCelular'] <> "__________"){
			$celular = str_replace("(","",$_POST['telefoneCelular']);
			$celular = str_replace(")","",$celular);
			$celular = str_replace("-","",$celular);
		}else{
			$celular = 0;
		}
		
		IF($_POST['telefoneComercial'] <> "__________"){
			$comercial = str_replace("(","",$_POST['telefoneComercial']);
			$comercial = str_replace(")","",$comercial);
			$comercial = str_replace("-","",$comercial);
		}else{
			$comercial = 0;
		}
		
		IF($_POST['telefoneResidencial'] <> "__________"){
			$residencial = str_replace("(","",$_POST['telefoneResidencial']);
			$residencial = str_replace(")","",$residencial);
			$residencial = str_replace("-","",$residencial);
		}else{
			$residencial = 0;
		}
		
		$nome 				= $_POST['nome'];
		$email 				= strToUpper($_POST['email']);
		$senha 				= (isset($_POST['senha']) ? strToUpper($_POST['senha']) : "");
		$codTipoPessoa 		= (int) $_POST['codTipoPessoa'];
		$endereco			= $_POST['endereco'];
		$cep				= str_replace("-","",$_POST['cep']);
		$bairro 			= $_POST['bairro'];
		$cidade 			= $_POST['cidade'];
		$uf 				= $_POST['uf'];
		$cnpj 				= (string) (isset($cnpj) ? $cnpj : 0);
		$ie					= (int) (isset($_POST['ie']) ? $_POST['ie'] : 0);
		$cpf 				= (string) (isset($cpf) ? $cpf : 0);
		$rg					= (int) (isset($_POST['rg']) ? $_POST['rg'] : 0);
		$estadoCivil 		= (int) $_POST['estadoCivil'];
		$codProfissao 		= (int) $_POST['codProfissao'];
		$orgaoExpedidor 	= (isset($_POST['orgaoExpedidor']) ? $_POST['orgaoExpedidor'] : "");
		$renda 				= (isset($renda) ? $renda : 0);
		$outroRendimento 	= (isset($outroRendimento) ? $outroRendimento : 0);
		$empresaTrabalho 	= $_POST['empresaTrabalho'];
		$enderecoTrabalho 	= (isset($_POST['enderecoTrabalho']) ? $_POST['enderecoTrabalho'] : "");
		$bairroTrabalho 	= (isset($_POST['bairroTrabalho']) ? $_POST['bairroTrabalho'] : "");
		$cidadeTrabalho 	= (isset($_POST['cidadeTrabalho']) ? $_POST['cidadeTrabalho'] : "");
		$ufTrabalho 		= (isset($_POST['ufTrabalho']) ? $_POST['ufTrabalho'] : "");
		$cepTrabalho 		= (isset($_POST['cepTrabalho']) ? str_replace("-","",$_POST['cepTrabalho']) : "");
		$nacionalidade      = (isset($_POST['nacionalidade']) ? $_POST['nacionalidade'] : "");
		$dataNascimento     = (isset($_POST['dataNascimento']) ? $_POST['dataNascimento'] : 0);
		$observacao         = $_POST['observacoes'];
		$sexo               = $_POST['codSexo'];
		$tituloEleitor      = $_POST['tituloEleitor'];

		$nomeRepresentante           	= (isset($_POST['nomeRepresentante']) ? $_POST['nomeRepresentante'] : "");
		$estadoCivilRepresentante    	= (int)(isset($_POST['estadoCivilRepresentante']) ? $_POST['estadoCivilRepresentante'] : "");
		$profissaoRepresentante      	= (int)(isset($_POST['profissaoRepresentante']) ? $_POST['profissaoRepresentante'] : "");
		$dataNascimentoRepresentante 	= (isset($_POST['dataNascimentoRepresentante']) ? $_POST['dataNascimentoRepresentante'] : 0);
		$cpfRepresentante            	= (string) ((isset($cpfRepresentante) ? $cpfRepresentante : ""));
		$identidadeRepresentante     	= (isset($_POST['identidadeRepresentante']) ? $_POST['identidadeRepresentante'] : "");
		$orgaoExpedidorRepresentante 	= (isset($_POST['orgaoExpedidorRepresentante']) ? $_POST['orgaoExpedidorRepresentante'] : "");
		$rendaRepresentante          	= (isset($rendaRepresentante) ? $rendaRepresentante : "");
		$outroRendimentorepresentante	= (isset($outroRendimentorepresentante) ? $outroRendimentorepresentante : "");
		
		//dados do conjuge
		
		$nomeConjuge				    = (string)(isset($_POST['nomeConjuge']) ? $_POST['nomeConjuge'] : "");
		$nacionalidadeConjuge           = (string)(isset($_POST['nacionalidadeConjuge']) ? $_POST['nacionalidadeConjuge'] : "");
		$cpfConjuge                     = (string)($cpfConjuge);
		$dataNascimentoConjuge          = (isset($_POST['dataNascimentoConjuge']) ? $_POST['dataNascimentoConjuge'] : 0);
		$identidadeConjuge              = (string)(isset($_POST['identidadeConjuge']) ? $_POST['identidadeConjuge'] : "");
		$orgaoExpedidorConjuge          = (string)(isset($_POST['orgaoExpedidorConjuge']) ? $_POST['orgaoExpedidorConjuge'] : "");
		$profissaoConjuge               = (int)(isset($_POST['profissaoConjuge']) ? $_POST['profissaoConjuge'] : "");
							
		if($codTipoPessoa == 1){ //Pessoa Física
			$sql = sprintf("call procPessoaFisicaCadastrar($codPessoa,'$nome','$email','$senha','$endereco',$cep,'$bairro','$cidade','$uf','$cpf',$rg,$estadoCivil,$codProfissao,'$orgaoExpedidor',$renda,$outroRendimento, '$empresaTrabalho','$enderecoTrabalho', '$bairroTrabalho', '$cidadeTrabalho', '$ufTrabalho', '$cepTrabalho', '$dataNascimento','$nacionalidade','$observacao','$celular','$residencial','$comercial','$nomeConjuge','$nacionalidadeConjuge','$cpfConjuge','$dataNascimentoConjuge','$identidadeConjuge','$orgaoExpedidorConjuge',$rendaConjuge,$outroRendimentoConjuge,$profissaoConjuge, '$sexo', $tituloEleitor)");
		}else{
			$sql = sprintf("call procPessoaJuridicaCadastrar($codPessoa,'$nome','$email','$senha','$endereco',$cep,'$bairro','$cidade','$uf','$cnpj',$ie,'$observacao', '$nomeRepresentante', $estadoCivilRepresentante, $profissaoRepresentante, '$dataNascimentoRepresentante', '$cpfRepresentante', '$identidadeRepresentante', '$orgaoExpedidorRepresentante', '$rendaRepresentante', '$outroRendimentorepresentante','$celular','$residencial','$comercial')");
		}	
		
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

	//verifica se já existe um email igual cadastrado no banco
	function emailVerificar(){
	
		if($_POST['codPessoa'] == 0 || $_POST['codPessoa'] == ""){
	
			global $mySQL;
			
			$email = $_POST['email'];
			
			$sql 	= sprintf("call procPessoaEmailVerificar('$email')");
			$rs 	= $mySQL  -> runQuery($sql);
			$rsQtde = $rs 	  -> num_rows;
			
			if($rsQtde > 0){
				echo "{success:true}"; //email já existe
			}else{
				echo "{success:false}"; //email ainda n existe
			}
		}else{
			echo "{success:false}"; //email ainda n existe
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
		case "pessoaListar":
			pessoaListar();
			break;
		case "pessoaVwListar":
			pessoaVwListar();
			break;
		case "pessoaCadastrar":
			pessoaCadastrar();
			break;
		case "pessoaGrupoListar":
			pessoaGrupoListar();
			break;
		case "pessoaGrupoRelacionar":
			pessoaGrupoRelacionar();
			break;
		case "profissaoListar":
			profissaoListar();
			break;
		case "pessoaGerenciar":
			pessoaGerenciar();
			break;	
		case "estadoCivilListar":
			estadoCivilListar();
			break;
		case "locadorListar":
			locadorListar();
			break;
		case "proprietarioListar":
			proprietarioListar();
			break;
		case "proprietarioUnicoListar":
			proprietarioUnicoListar();
			break;
		case "pessoaUnicoListar":
			pessoaUnicoListar();
			break;
		case "fiadorListar":
			fiadorListar();
			break;
		case "contratoFiadorRelacionar":
			contratoFiadorRelacionar();
			break;
		case "conjugeUnicoListar":
			conjugeUnicoListar();
			break;
		case "conjugeCadastrar":
			conjugeCadastrar();
			break;
		case "fiadorSelecionar":
			fiadorSelecionar();
			break;
		//rotina que verifica se o email já existe no banco
		case "emailVerificar":
			emailVerificar();
			break;

			
	}

}else{
	header('location:login.php');
}	
?>