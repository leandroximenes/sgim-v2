<?php
session_start();
header('Content-Type: text/html; charset=iso-8859-1');

//header("Content-type: application/vnd.ms-word");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=relatorio_aniversariantes.doc");
//header("Pragma: no-cache");

$titulo = 'Relatório de Pessoas';

if(isset($_SESSION["SISTEMA_codPessoa"])){

	include("../../conexao/conexao.php");
	include("../../php/php.php");
	
	global $mySQL;
	$sql = sprintf("CALL procRelatorioPessoaListar()");
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
		<td colspan='6'> 
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
		<th> Nome</th>
		<th> E-mail</th>
		<th> Endereço</th>
		<th> Grupos</th>
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
			<td width='200'> <?php echo utf8_decode($rsLinha['nome']); ?></td>
			<td width='130'> <?php echo utf8_decode($rsLinha['email']); ?></td>
			<td width='270'> <?php echo utf8_decode($rsLinha['endereco'] .", ". $rsLinha['bairro'] .", ". $rsLinha['cidade'].", ". $rsLinha['uf']); ?></td>
			<td width='130'> <?php echo utf8_decode($rsLinha['grupos']); ?></td>
		</tr>
<?php
		$cont ++;
	}
?>
</table>

<?php
	} else {
		echo 'Não existem Pagamentos em atraso!';
	}
	

}else{
	header('location:login.php');
}	
?>
<script>
	window.print();
</script>