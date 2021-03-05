<?php
	include("../../../conexao/conexao.php");
	
	//procCidadeListarQuantidade
	$sql = sprintf("CALL procCidadeListarQuantidade()");
	$rs = $mySQL->runQuery($sql);
	$rs2 = $mySQL->runQuery($sql);
		
	//Mostrar, em barras, quantidade e percentual de imóveis por bairro/cidades
	$total = 0;
		
	while($linha = mysqli_fetch_assoc($rs)){ 
		$total = $total + $linha['qtde']; 
	} 
	 
	$str1 = "";
	$str2 = "";
	
	while($linha1 = mysqli_fetch_assoc($rs2)){ 
		$porcento = (($total * $linha1['qtde']) / 100);
		
		$str1 = $str1 . "<string>". $linha1['cidade'] . " - " . $linha1['bairro'] ."</string>";
		$str2 = $str2 . "<number>". $linha1['qtde'] ."</number>";
	} 

	echo "<chart>
	<license>JTAMVPF7P2O.H4X5CWK-2XOI1X0-7L</license>
	<chart_type>3d column</chart_type>
	<chart_pref rotation_x='0' rotation_y='0' min_x='20' max_x='70' min_y='30' max_y='80' />
	<axis_category  size='13' color='000000' alpha='75' orientation='diagonal_up' />
	<chart_rect  width='700' height='150' />
	
	
	<chart_data>

		<row>
			 <null/>";
	echo 	$str1;
	echo "</row>
		<row>
			 <string>Imóveis por bairro</string>";
	echo $str2;
	echo "</row>
	</chart_data>	
	
</chart>";