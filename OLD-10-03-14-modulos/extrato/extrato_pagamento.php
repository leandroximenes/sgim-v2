<?php 
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

header("Content-type: application/vnd.ms-word");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=extrato_repasse_locacao.doc");
header("Pragma: no-cache");

$titulo = 'Extrato de Repasse de Locacação';

if(isset($_SESSION["SISTEMA_codPessoa"])){
 
	include("../../conexao/conexao.php");
	include("../../php/php.php");
	include("../diversos/util.php");
	
	global $mySQL;
	$codPagamento = $_GET['codPagamento'];
	$sql          = sprintf("CALL procContratoPagamentoUnicoListar($codPagamento)");
	$rs           = $mySQL->runQuery($sql);
	$linha        = mysqli_fetch_assoc($rs);

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
<table border='0' width='600' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan='2'>
			<div class="clJustificar">
				<table width='600'>
					<tr>
						<td colspan='2'> 
							<div id='cabecalhoRelatorio' class='clearfix'>
								<div id='logoRelatorio'>
									<img src='http://www.tabakalimoveis.com.br/sgim/modulos/extrato/img/logo.jpg' />
								</div>
								<div id='tituloRelatorio'>
									<div id='nomeRelatorio'>
										<?php echo $titulo; ?>
									</div>
								</div>
								
							</div>
						</td>
					</tr>
				</table>
			<table width='600'>
					<tr>
						<td colspan='2'><b>Locador:</b> <?php echo $linha['nomeLocatario']; ?></td>
					</tr>
					<tr>
						<td colspan='2'><b>Endereço do Imóvel Locado:</b><br/>
						<?php echo utf8_decode($linha['endereco']); ?></td>
					</tr>	
					<tr>
						<td>
							<b>Bairro:</b> <?php echo utf8_decode($linha['bairro']); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						</td>
						<td>
							<b>Cidade:</b> <?php echo utf8_decode($linha['cidade']).'/'.utf8_decode($linha['uf']); ?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Nº do Contrato:</b> <?php echo $linha['numero']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						</td>
						<td>
							<b>Início da Locação:</b> <?php echo $linha['dataInicio']; ?> 
						</td>
					</tr>
				</table>
				<hr/>

				<table width='600'>
					<tr>
						<td><b>Banco:</b> <?php echo $linha['nomeBanco']; ?></td>

						<td><b>Agência:</b> <?php echo $linha['agencia']; ?></td>
						<td><b>Conta:</b> <?php echo $linha['conta']; ?></td>
					</tr>
					<?php 
						if($linha['favorecido'] != ''){
					?>
							<tr>
								<td colspan='3'><b>Favorecido:</b> <?php echo $linha['favorecido']; ?></td>
							</tr>	
					<?php 
						}
					?>
				</table>

				<hr/>

				<table width='600'>
					<tr>
						<td>
							<b>Período de Locação:</b> <?php echo $linha['periodoInicial']; ?> a 
							<?php echo $linha['periodoFinal']; ?>
						</td>
						<td><b>Parcela:</b> <?php echo $linha['parcela']; ?></td>
					</tr>	
				</table>

				<hr/>

				<table width='600'>
					<tr>
						<td>
							<b>Valor de Locação:</b> <?php echo 'R$ ' . number_format($linha['valor'], 2, ',', '.'); ?>
						</td>
						<td><b>Comissão:</b> <?php echo 'R$ ' . number_format($linha['comissao'], 2, ',', '.'); ?></td>
					</tr>	

					<tr>
						<td colspan='2'>
							<b>Data de Crédito:</b> <?php echo $linha['dataRepasse']; ?>
						</td>
					</tr>
					
					<tr>
						<td>
							<b>Multa:</b> <?php echo 'R$ ' . number_format($linha['valorMulta'], 2, ',', '.'); ?>
						</td>
						<td><b>Tabakal:</b> <?php echo 'R$ ' . number_format($linha['valorGastoServico'], 2, ',', '.'); ?></td>
					</tr>

					<tr>
						<td colspan='2'>
							<b>Desconto:</b> <?php echo 'R$ ' . number_format($linha['valorDesconto'], 2, ',', '.'); ?>
						</td>
						
					</tr>

					<tr>
						<td>
							<b>Aluguel Recebido:</b> <?php echo 'R$ ' . number_format($linha['valorPagamento'] + $linha['valorGastoServico'], 2, ',', '.'); ?>
						</td>
						<td><b>Repasse:</b> <?php echo 'R$ ' . number_format($linha['valorRepasse'], 2, ',', '.'); ?></td>
					</tr>
				</table>

				<hr/>

				<table width='600'>
					<tr>
						<td>
							<b>Observações:</b> <?php echo $linha['observacaoPagamento']; ?>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align='right'>
							Brasília-DF, <?php echo data(); ?>
						</td>
					</tr>

					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align='center'>
							_____________________________
							<br/>
							Marleide Teles
							<br/>
							TABAKAL IMÓVEIS
						</td>
					</tr>
				</table>
				
			</div>	
			<br/>
			<br/>
			<br/>	


		</td>
	</tr>
</table>


<?php

}else{
	header('location:login.php');
}	
?>