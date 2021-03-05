<?php 
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

header("Content-type: application/vnd.ms-word");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=contrato_administracao_Garantia.doc");
header("Pragma: no-cache");

$titulo = 'COntrato de administração';

if(isset($_SESSION["SISTEMA_codPessoa"])){
 
	include("../../conexao/conexao.php");
	include("../../php/php.php");
	include("../diversos/util.php");
	
	global $mySQL;
	$codImovel = $_GET['codImovel'];
	$sql = sprintf("CALL procContratoImovelUnicoListar($codImovel)");
	$rs = $mySQL->runQuery($sql);

?>
<style> 
body{
	margin: 0px;
	font-size: 12px;
	font-family: arial;
}

.clearfix:after {
	content: ".";
	display: block;
	clear: both;
	visibility: hidden;
	height: 0;
}


table{
	font-size: 11pt;
	font-family: arial;
}

th{
	background: #19688F;
	font-weight: bold;
	color: #FFF;
}

.vermelho{
	color: #990000;
}

.verde{
	color: #31CC2E;
}

.clJustificar{
	text-align: justify;
}

#cabecalhoRelatorio{
	width: 650px;
	margin-bottom: 10px;
	height: 62px;
	background: url('img/bg_topo.jpg') bottom repeat-x;
	float: left;
}

#logoRelatorio{
	float: left;
}

#tituloRelatorio{
	float: right;
	text-align: right;
}

#nomeRelatorio{
	padding-top: 15px;
	font-size: 18px;
	color: #19688F;
}
</style>

<?php
	$rsLinha = mysqli_fetch_assoc($rs);
	$enderecoImovel = $rsLinha['endereco'];
	$bairroImovel = $rsLinha['bairro'];
	$cidadeImovel = $rsLinha['cidade'];
	$ufImovel = $rsLinha['uf'];
	$cepImovel = $rsLinha['cep'];
	$codProprietario = $rsLinha['codProprietario'];
	$valor = $rsLinha['valor'];

	$sql = sprintf("CALL procPessoaListarUnico($codProprietario)");
	$rsLocatorio = $mySQL->runQuery($sql);
	$rsLocatarioLinha = mysqli_fetch_assoc($rsLocatorio);
	$nomeLocatario = utf8_decode($rsLocatarioLinha['nome']);
	$emailProprietario = utf8_decode($rsLocatarioLinha['email']);
	$profissao = utf8_decode($rsLocatarioLinha['profissao']);
	$cpf = $rsLocatarioLinha['cpf'];
	$rg = $rsLocatarioLinha['rg'];
	$orgaoExpedidor = $rsLocatarioLinha['orgaoExpedidor'];
	$enderecoLocatario = $rsLocatarioLinha['endereco'];
	$bairroLocatario = $rsLocatarioLinha['bairro'];
	$cidadeLocatario = $rsLocatarioLinha['cidade'];
	$cepLocatario = $rsLocatarioLinha['cep'];
	$nacionalidade = $rsLocatarioLinha['nacionalidade'];
	
	$sqlTelefone = sprintf("CALL procPessoaTelefoneListar($codProprietario)");
	
	$rsTelefone = $mySQL->runQuery($sqlTelefone);
		
	$sqlBanco 		= sprintf("CALL procPessoaDadoBancarioListar($codProprietario)");
	$rsBanco 		= $mySQL->runQuery($sqlBanco);
	$rsBancoLinha 	= mysqli_fetch_assoc($rsBanco);
	
	$banco = $rsBancoLinha['banco'];
	$agencia = $rsBancoLinha['agencia'];
	$conta = $rsBancoLinha['conta'];
	$observacao = $rsBancoLinha['observacao'];
	
	//se existir conjuge ele lista!
	$sqlConjuge = sprintf("CALL procPessoaConjugeListar($codProprietario)"); 
	$rsConjugeLocatario = $mySQL->runQuery($sqlConjuge);
	
	
	$rsLocatarioConjugeLinha = mysqli_fetch_assoc($rsConjugeLocatario);
	$conjugeLocatarioNome = $rsLocatarioConjugeLinha['nome'];
	$conjugeLocatarioRg = $rsLocatarioConjugeLinha['rg'];
	$conjugeLocatarioOrgaoExpedidor = $rsLocatarioConjugeLinha['orgaoExpedidor'];
	$conjugeLocatarioCpf = $rsLocatarioConjugeLinha['cpf'];
	$conjugeLocatarioProfissao = $rsLocatarioConjugeLinha['profissao'];
	$conjugeLocatarioNacionalidade = $rsLocatarioConjugeLinha['nacionalidade'];
	
	if($conjugeLocatarioNome != ""){
		$strConjugeListar = ' casado(a) com ' . $conjugeLocatarioNome . ', ' . $conjugeLocatarioNacionalidade . '(a), portador(a) da carteira de identidade ' . $conjugeLocatarioRg . ' '  . $conjugeLocatarioOrgaoExpedidor . ', inscrito(a) no CPF sob o número ' .  $conjugeLocatarioCpf . ',';
	}else{
		$strConjugeListar = "";
	}
	//fim
?>

<table border='0' width='600' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan='2'>
			<center><b>Contrato de Administração de Imóvel Com Garantia de Pagamento de Aluguel</b></center>
			<br/>
			<br/>

			<div class="clJustificar">
				<b>Contrato de Administração de Imóvel </b>que fazem entre si, <?php echo utf8_decode($nomeLocatario) . ", " . $nacionalidade ."(a), ". utf8_decode($profissao) .", " ?> 
				portador(a) da carteira de identidade <?php echo $rg . ' '  . $orgaoExpedidor . ", "; ?>	inscrito(a) no CPF sob o <?php echo $cpf; ?>, 
				<?php if($strConjugeListar != ""){ echo $strConjugeListar; }else{ echo ","; } ?>  
				residente(s) e domiciliado(a)(s) 
				<?php 
					echo utf8_decode($enderecoLocatario) . " - " . utf8_decode($bairroLocatario) . " - " . utf8_decode($cidadeLocatario) . " - CEP: " . $cepLocatario; 
					echo " Fones: "
				?>
				<?php
					while($linhaTelefone = mysqli_fetch_assoc($rsTelefone)){
						
						$mystring = $linhaTelefone['telefone'];
						$findme   = '________';
						$pos = strpos($mystring, $findme);
					
						if(!$pos){
							echo $mystring . ", ";;
						}
					}	
				?>e TABAKAL Empreendimentos Imobiliários Ltda., inscrita no CNPJ/MF sob o nº 06.864.021/0001-31 e Inscrição CF/DF 
				nº 07.457.662/001-02 Brasília - DF, e no Conselho Regional de Corretores de Imóveis - CRECI, sob o nº 9508, representada 
				pela corretora de imóveis MARLEIDE DE ARAUJO TELES, CRECI/DF 8091, aqui denominados, respectivamente, 
				CONTRATANTE(S) LOCADOR(ES)(A) e CONTRATADA ADMINISTRADORA, mediante as seguintes condições:
			</div>
			<br />		
			<div class="clJustificar">
				<b>CLÁUSULA PRIMEIRA - </b>O(s)(A) Contratante(s) Locador(es)(a) ajusta(m) com a Contratada Administradora a administração de um imóvel situado na <?php echo utf8_decode($enderecoImovel) . ', '. utf8_decode($bairroImovel) . ', ' . utf8_decode($cidadeImovel) . "-" . $ufImovel . " - CEP: " . $cepImovel; ?>, tudo de conformidade com os termos da procuração anexa, que passa a fazer parte integrante deste instrumento. 
			</div>
			<br/>
			<div class="clJustificar">
				<b>CLÁUSULA SEGUNDA - </b>À Contratada Administradora é facultada, sob sua inteira responsabilidade, a escolha do locatário e das garantias fidejussórias que ele prestar, estabelecendo as condições do contrato de locação que em nome do(s)(a) Contratante(s) Locador(es)(a) firmará, observando a legislação pertinente, e obviamente, seus interesses.
			</div>
			<br/>
			<div class="clJustificar">
				<b>CLÁUSULA TERCEIRA - </b>O valor do contrato de locação inicial a ser celebrado será de <?php echo 'R$ '. number_format($valor, 2, ',', '.'); ?> (<?php echo extenso($valor, true, true, true); ?>), reajustáveis a cada 12 (doze) meses, de acordo com o IGPM/FGV. Fica consignado que correrá por conta do locatário os encargos de água, luz, seguro de incêndio, telefone, IPTU/TLP. 
			</div>
			<br/>
			<div class="clJustificar">
				<b>CLÁUSULA QUARTA - </b>A Contratada Administradora prestará assistência advocatícia ao(s)(à) Contratante(s) Locador(es)(a), defendendo todos seus direitos, especificamente no que diz respeito à locação e acessórios do imóvel ora administrado.
			</div>
			<br/>
			<div class="clJustificar">
				<b>parágrafo Único – </b>As despesas judiciais e os honorários advocatícios estranhos ao contrato de locação e seus acessórios correrão por conta do(s)(a) Contratante(s) Locador(es)(a).
			</div>
			<br/>
			<div class="clJustificar">
				<b>CLÁUSULA QUINTA - </b>A Contratada Administradora fará jus, a título de remuneração pelos serviços que prestar ao(s)(à) Contratante(s) Locador(es)(a), a comissão de 15% (quinze por cento) do valor dos aluguéis líquidos recebidos do locatário, e será esta descontada na prestação mensal de contas, contra recibo.
			</div>				
			<br/>
			<div class="clJustificar">					
				<b>CLÁUSULA SEXTA - </b>O(s)(A) Contratante(s) Locador(es)(a) estipula(m) que tem interesse em receber da Contratada Administradora o aluguel líquido conforme a seguir: Depósito Bancário no Banco <?php echo $banco; ?>  - Agência: <?php echo $agencia . ", "; ?> Conta Corrente <?php echo $conta . ", " . utf8_decode($observacao); ?>.
			</div>	
			<br/>
			<div class="clJustificar">		
				<b>CLÁUSULA SÉTIMA - </b>A Contratada Administradora colocará à disposição do(s)(o) Contratante(s) Locador(es)(a) o valor líquido referente ao aluguel até o quinto dia útil, a contar da data do efetivo recebimento do aluguel. Mensalmente a  Contratada Administradora enviará ao e-mail  <b><?php echo strtolower($emailProprietario); ?></b>, extrato com créditos e débitos relativos à locação.
			</div>			
			<br/>
			<div class="clJustificar">		
				<b>CLÁUSULA OITAVA - </b>A Contratada Administradora ficará desobrigada de efetuar o pagamento do aluguel ao(s)(à) Contratante(s) Locador(es)(a) se este não for pago pelo locatário em caso de desapropriação, interdição, venda ou penhora, arresto ou seqüestro do imóvel, calamidade pública e guerra, quando ajuizada ação de retomada, ou ainda, quando por qualquer motivo o(s)(a) Contratante(s) Locador(es)(a) der(em) causa a que o locatário retenha o pagamento.
			</div>			
			<br/>
			<div class="clJustificar">		
				<b>CLÁUSULA NONA - </b>Não efetuado o pagamento do aluguel pelo locatário e necessitando a Contratada Administradora promover a cobrança amigável e/ou judicial contra o mesmo não poderá(ão) o(s)(a) Contratante(s) Locador(es)(a), em hipótese alguma, revogar(em) a procuração que àquela outorgou, nem tampouco obstar, por qualquer forma, os procedimentos judiciais que serão promovidos, sob pena de ficar(em) sujeito(s) o(s)(a) Contratante(s) Locador(es)(a) ao pagamento de uma indenização equivalente ao montante do que esteja sendo exigido do locatário em Juízo.
			</div>			
			<br/>
			<div class="clJustificar">					
				<b>CLÁUSULA DÉCIMA – </b>Uma vez que o aluguel é garantido ao(s)(à) Contratante(s) Locador(es)(a) pela Contratada Administradora, a esta caberão integralmente os juros, a correção monetária e as multas cobradas do locatário, inclusive a multa de rescisão contratual conforme o caso, sem prejuízo da comissão a esta devida, na forma pactuada na <b>CLÁUSULA QUINTA</b>.
			</div>	
			<br/>
			<div class="clJustificar">					
				<b>CLÁUSULA DÉCIMA PRIMEIRA – </b>A Contratada Administradora mediante autorização do(s)(a) Contratante(s) Locador(es)(a) celebrará novo contrato de locação, por prazo idêntico ou diverso, se a locação em curso vier a ser rescindida antes do prazo previsto, seja amigável ou judicialmente.
			</div>	
			<br/>
			<div class="clJustificar">			
				<b>Parágrafo Primeiro - </b> Ocorrendo à hipótese prevista no caput desta Cláusula, se obriga a Contratada Administradora dar ciência ao(s)(à) Contratante(s) Locador(es)(a), tudo com vistas a ser ajustado novo preço e anuência quanto ao novo prazo da locação.
			</div>
			
			<br/>
			<div class="clJustificar">							
				<b>CLÁUSULA DÉCIMA SEGUNDA - </b>Ao(s)(À) Contratante(s) Locador(es)(a) será defeso celebrar acordos com locatário sem expressa anuência escrita da Contratada Administradora, assim como ingerir na administração do imóvel, sob pena de multa equivalente ao valor de 01 (um) mês de aluguel conforme disposto na <b>CLÁUSULA TERCEIRA</b>.
			</div>					
			<br/>
			<div class="clJustificar">							
				<b>CLÁUSULA DÉCIMA TERCEIRA - </b>Na vigência do presente contrato de administração, caso seja autorizada a venda pelo(s)(a) Contratante(s) Locador(es)(a), fica desde já a Contratada Administradora, nomeada a intermediadora da venda do imóvel em questão, fazendo jus, portanto, à comissão equivalente a <b>5% (cinco por cento)</b> sobre o valor da transação.
			</div>			
			<br/>
			<div class="clJustificar">							
				<b>CLÁUSULA DÉCIMA QUARTA - </b>O presente contrato de Administração é celebrado por prazo idêntico ao contrato de locação a ser celebrado e somente poderá ser rescindido nas seguintes condições:
			</div>					
			<br/>
			<div class="clJustificar">							
				a) - <b>Por justa causa</b>, caso a Contratada Administradora, sem qualquer justificativa válida, deixe de prestar contas do aluguel, após o prazo de carência, salvo motivo de força maior, tais como greve bancária, calamidade pública, etc. Nestas circunstâncias nada será devido de comissão à Administradora Contratada, exigindo-se apenas que se faça a indispensável prova da infringência contratual que se notifique judicialmente a Contratada Administradora, a fim de que se proceda administrativamente a rescisão do presente contrato, sob pena de não o fazendo ser feita judicialmente.
			</div>			
			<br/>
			<div class="clJustificar">							
				b) - <b>Sem justa causa</b>, devendo ser precedida ser precedida de notificação com antecedência mínima de <b>90 (noventa)</b> dias do vencimento do contrato locatício, caso pretenda(m) o(s)(a) Contratante(s) Locador(es)(a) retirar(em) o imóvel da Administração da Contratada Administradora, após o vencimento do contrato locatício. Neste caso, arcará(ão) o(s)(a) Contratante(s) Locador(es)(a) com o pagamento da comissão imobiliária pactuada na <b>CLÁUSULA QUINTA</b>, calculada sobre os meses restantes até o término do contrato locatício ou, na ausência da notificação no prazo previsto, o equivalente a um mês de aluguel.
			</div>			
			<br/>
			<div class="clJustificar">							
				<b>Parágrafo Único - </b>Se a Contratada Administradora, sem motivo justificado, rescindir o presente contrato de Administração, se obrigará igualmente ao pagamento da multa correspondente a comissão imobiliária pactuada na <b>CLÁUSULA QUINTA</b>, calculada sobre os meses restantes até o término do contrato locatício, ou na ausência de notificação no prazo previsto, o equivalente a um mês de aluguel.
			</div>			
			<br/>
			<div class="clJustificar">											
				<b>CLÁUSULA DÉCIMA QUINTA - </b>Rescindido este contrato, ficará sem efeito a procuração referida na <b>CLÁUSULA PRIMEIRA</b>, outorgada pelo(s)(a) Contratante(s) Locador(es)(a) à Contratada Administradora.
			</div>	
			<br/>
			<div class="clJustificar">															
				<b>CLÁUSULA DÉCIMA SEXTA - </b>Elegem os contratantes o foro da Circunscrição Judiciária de Brasília-DF, com exclusão de qualquer outro, para que sejam dirimidas as questões oriundas deste contrato.
			</div>			
			<br/>
			<br/>
			<br/>		
			<div> <!-- data-->
<?php
				$dia = date("d"); 
				$mess = date("m"); 
				$ano = date("y"); 

				switch ($mess) {
					case "01":    $mes = "Janeiro";     break;
					case "02":    $mes = "Fevereiro";   break;
					case "03":    $mes = "Março";       break;
					case "04":    $mes = "Abril";       break;
					case "05":    $mes = "Maio";        break;
					case "06":    $mes = "Junho";       break;
					case "07":    $mes = "Julho";       break;
					case "08":    $mes = "Agosto";      break;
					case "09":    $mes = "Setembro";    break;
					case "10":    $mes = "Outubro";     break;
					case "11":    $mes = "Novembro";    break;
					case "12":    $mes = "Dezembro";    break; 
				}
				
				echo 'Brasília-DF, ' . $dia . ' de ' .  $mes . ' de 20' . $ano;
?>
			</div>
			<br />			
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<table width='600'>
				<tr>
					<td align='center'>
						________________________________
						<br/>
						<b>CONTRATANTE LOCADOR(A)</b>
						<br/>
						<?php echo $nomeLocatario; ?>
						<br/>
						CPF: <?php echo $cpf; ?>
					</td>
<?php
					if($strConjugeListar != ""){
?>
					<td align='center'>
						________________________________
						<br/>
						<b>CÔNJUGE</b>
						<br/>
						<?php echo $conjugeLocatarioNome; ?>
						<br/>
						CPF: <?php echo $conjugeLocatarioCpf; ?>
					</td>
<?php
					}
?>
				</tr>
			</table>
			<br/>
			<br/>
			<br/>

			<table width='600'>
				<tr>
					<td align='center' width='50%'>
						________________________________
						<br/>
						<b>CONTRATADA ADMINISTRADORA</b>
						<br/>
						<b>TABAKAL</b> Emp. Imobiliários Ltda. 
						<br/>
						CNPJ/MF nº 06.864.021/0001-31
					</td>
				</tr>
			</table>
			<br/>
			<br/>
			<br/>

			Testemunhas:
			<br/>
			<br/>
			<br/>

			<table width='600'>
				<tr>
					<td align='center' width='50%'>
						________________________________
						<br/>
						Aurélio Magno da Fonseca Pinto
						<br/>
						CPF nº 444.079.121-20	
					</td>
					<td align='center' width='50%'>
						________________________________
						<br/>
						
						<br/>
						CPF nº:
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


<?php

}else{
	header('location:login.php');
}	
?>