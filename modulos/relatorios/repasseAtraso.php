<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de Repasses com Atraso';

if(isset($_SESSION["SISTEMA_codPessoa"])){

	include("../../conexao/conexao.php");
	include("../../php/php.php");
	
	global $mySQL;
	//$sql = sprintf("CALL procRelatorioRepasseAtraso()");
        
        
        $sql = "SELECT *, DATE_FORMAT(pagamento.dataVencimento,'%d/%m/%Y') as dataVencimento, DATE_FORMAT(pagamento.dataPagamento,'%d/%m/%Y') as dataPagamento,  DATE_FORMAT(contrato.dataFim,'%d/%m/%Y') as dataFimFormatada, inquilino.nome as Inquilino, locatario.nome as Locatario FROM contrato 
INNER JOIN pagamento ON contrato.codContrato = pagamento.codContrato
INNER JOIN pessoa as inquilino ON contrato.codPessoaInquilino = inquilino.codPessoa
INNER JOIN pessoa as locatario ON contrato.codPessoaLocador = locatario.codPessoa
where contrato.status = 1 
and contrato.dataFim > now() 
and pagamento.dataPagamento is not null
and pagamento.dataRepasse is null
and pagamento.dataVencimento < now()
AND pagamento.codContrato NOT IN (SELECT codContrato FROM contratoEncerramento)";
        
        
	$rs = $mySQL->runQuery($sql);
	$rsQuant = $rs->num_rows;
		
	if($rsQuant>0){

?>
<style>
body{
	margin: 0px;
	font-size: 11px;
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
	font-size: 11px;
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

#cabecalhoRelatorio{
	width: 730px;
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


		
<table border='0' width='730' cellpadding='3' cellspacing='1'>
	
	
	<tr>
		<td colspan='7'> 
			<div id='cabecalhoRelatorio' class='clearfix'>
				<div id='logoRelatorio'>
					<img src='img/logo.jpg' />
				</div>
				<div id='tituloRelatorio'>
					<div id='nomeRelatorio'>
						<?php echo $titulo; ?>
					</div>
					<?php echo data(); ?>
				</div>
				
			</div>
		</td>
	</tr>

	<tr>
		<th> Contrato</th>
		<th> Parcela</th>
		<th> Vencimento</th>
		<th> Pagamento</th>
		<th> Inquilino</th>
		<th> Locador</th>
		<th> Data Fim Contrato</th>
	</tr>
<?php			
	$cont = 0;
	while ($rsLinha = mysqli_fetch_assoc($rs)) {
		if($cont%2==0){
			$bg = '#CFE4EF';
		}else{
			$bg = '#FFF';
		}
?>			
		<tr bgcolor='<?php echo $bg; ?>'>
			<td width='50' align='center'> <?php echo $rsLinha['codContrato']; ?></td>
			<td width='50' align='center'> <?php echo $rsLinha['parcela']; ?></td>
			<td width='80' align='center'> <?php echo $rsLinha['dataVencimento']; ?></td>
			<td width='80' align='center'> <?php echo $rsLinha['dataPagamento']; ?></td>
			<td width='200'> <?php echo utf8_decode($rsLinha['Inquilino']); ?></td>
			<td width='200'> <?php echo utf8_decode($rsLinha['Locatario']); ?></td>
			<td width='70' align='center'> <?php echo $rsLinha['dataFimFormatada']; ?></td>
		</tr>
<?php
		$cont ++;
	}
?>
</table>

<?php
	} else {
		echo 'Não existem Repasses Pendentes!';
	}
	

}else{
	header('location:login.php');
}	
?>
<script>
	window.print();
</script>