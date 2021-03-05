<?php
	include("../../../conexao/conexao.php");
	
	//procContratoListarValores
	$sql = sprintf("CALL procContratoListarValores()");
	$rs = $mySQL->runQuery($sql);
		
	// até R$ 1.000,00
	// de R$ 1.000,01 a R$ 2.000,00
	// de R$ 2.000,01 a R$ 3.000,00
	// de R$ 3.000,01 a R$ 4.000,00
	// acima de R$ 4.000,01
		
	$cont1 = 0;
	$cont2 = 0;
	$cont3 = 0;
	$cont4 = 0;
	$cont5 = 0;
		
	while($linha = mysqli_fetch_assoc($rs)){
		$codContrato = $linha['codContrato'];
		$valor 		 = $linha['valor'];
		
		if($valor < 1000.01){
			$cont1 = $cont1 + 1;
		}elseif($valor > 1000 and $valor < 2000.01){
			$cont2 = $cont2 + 1;
		}
		elseif($valor > 2000 and $valor < 3000.01){
			$cont3 = $cont3 + 1;
		}elseif($valor > 3000 and $valor < 4000.01){
			$cont4 = $cont4 + 1;
		}else{
			$cont5 = $cont5 + 1;			
		}
	}
	
	echo "<chart>
	<license>JTAMVPF7P2O.H4X5CWK-2XOI1X0-7L</license>
	<chart_type>3d pie</chart_type>
	<chart_data>

		<row>
			 <null/>
			 <string>R$ 1.000,00</string>
			 <string>R$ 1.000,01 a R$ 2.000,00</string>
			 <string>R$ 2.000,01 a R$ 3.000,00</string>
			 <string>3.000,01 a R$ 4.000,00</string>
			 <string>acima de R$ 4.000,01</string>
		  </row>
		  <row>
			 <string>Region A</string>
			 <number>" . $cont1 . "</number>
			 <number>" . $cont2 . "</number>			 
			 <number>" . $cont3 . "</number>			 
			 <number>" . $cont4 . "</number>			 
			 <number>" . $cont5 . "</number>			 
		  </row>
	</chart_data>
	<chart_border top_thickness='0' bottom_thickness='4' left_thickness='4' right_thickness='0' color='000000' />

</chart>";