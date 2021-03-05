<?php
	session_start(); 
	header('Content-Type: text/html; charset=iso-8859-1');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	if(isset($_SESSION['codPessoa'])){
		session_destroy();
	}
?>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"> 
<meta HTTP-EQUIV="Expires" CONTENT="-1"> 
<title>:: Tabakal Imóveis - SGIM - Sistema de Gerenciamento de Imóveis.::</title>
<link rel="stylesheet" type="text/css" href="ext-3.3.1/resources/css/ext-all-notheme.css" />
<script type="text/javascript" src="ext-3.3.1/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="ext-3.3.1/ext-all-debug.js"></script>
<link rel="stylesheet" href="ext-3.3.1/resources/css/ext-all.css" />
<link rel="stylesheet" href="biblioteca_css/estilos.css" />
<script src="biblioteca_js/login.js"></script>

</head>
<div id="cadastro" style="display: none; border: 1px solid red;">

</div>
<body class="bodyLogin">
<table width="100%" height="100%">
	<tr>
		<td  align="center" valign="middle">
			<div id="bgLogin">
				<div id="center">
				</div>
				© Copyright 2011, Tabakal Imóveis - todos os direitos reservados.
			</div>
		</td>
	</tr>
</table>
</body>
</html>