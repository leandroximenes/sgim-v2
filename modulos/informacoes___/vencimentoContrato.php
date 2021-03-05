<?php
	include("../../conexao/conexao.php");
?>
<html>
<head>
<style type="text/css">
body{
	margin: 0px;
	font-family: arial;
	font-size: 11px;
}

#iframe{
	border: 0px;
	padding: 5px;
}

.negrito{
	font-weight: bold;
}

table{
	font-size: 11px;
}
</style>
</head>
<body>
	<div id="iframe">
		<?php
			global $mySQLAlert;

			$sql     = sprintf("call procInfoContratoVencimento30()");
			$rs      = $mySQL->runQuery($sql);
			$rsQuant = $rs->num_rows;
		
			if($rsQuant>0){

		?>	
				<span class="negrito">O(s) contratos(s) abaixo irão vencer nos próximos 30 dias:</span>
				<br/>
				<br/>
				<table>

		<?php
					while ($linha = mysqli_fetch_assoc($rs)) {
		?>
						<tr>
							<td><img src='../../img/ic_estrela.png' /></td>
							<td>
								<span class="negrito">Inquilino:</span> <?php echo '['.$linha["codContrato"] . '] ' .utf8_decode($linha["Inquilino"]); ?><br/>
								<span class="negrito">Data de Vecimento:</span> <?php echo  $linha["dataFimFormatada"]; ?>
							</td>
						</tr>
		<?php
					}
		?>
				</table>				
		<?php
			}
		?>

		
	</div>
</body>
</html>