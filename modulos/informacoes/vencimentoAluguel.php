<?php include("../../conexao/conexao_simples.php"); header('Content-Type: text/html; charset=iso-8859-1');?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
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

			$sql     = "select distinct c.qtdMeses, DATE_ADD(c.dataInicio, INTERVAL 11 month) as dataAviso, pf.nome, i.endereco from pagamento as p 
inner join contrato as c on p.codContrato = c.codContrato
inner join pessoa as pf on pf.codPessoa = c.codPessoaInquilino
inner join imovel as i on i.codImovel = c.codImovel
where c.codContrato not in (SELECT codContrato FROM contratoEncerramento)
AND 
c.qtdMeses > 12 
AND (
     (
      DATE_ADD(NOW(), INTERVAL -7 day) < DATE_ADD(c.dataInicio, INTERVAL 12 month) 
      AND 
      DATE_ADD(NOW(), INTERVAL 7 day) > DATE_ADD(c.dataInicio, INTERVAL 12 month)
     )
      OR
     (
      DATE_ADD(NOW(), INTERVAL -7 day) < DATE_ADD(c.dataInicio, INTERVAL 24 month) 
      AND 
      DATE_ADD(NOW(), INTERVAL 7 day) > DATE_ADD(c.dataInicio, INTERVAL 24 month)
     ) 
    )";
			$q      = mysql_query($sql);
			$rsQuant = mysql_num_rows($q);
		
			if($rsQuant>0){

		?>	
				<span class="negrito">Contrato(s) para emissão de boleto(s):</span>
				<br/>
				<br/>
				<table>

		<?php
					while ($linha = mysql_fetch_assoc($q)) {
		?>
						<tr>
							<td><img src='../../img/ic_estrela.png' /></td>
							<td>
								<span class="negrito"> Inquilino:</span> <?php echo utf8_decode($linha["nome"]); ?><br/>
								<span class="negrito"> Imóvel:</span> <?php echo  utf8_decode($linha["endereco"]); ?>
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