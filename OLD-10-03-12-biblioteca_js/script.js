function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function janela(url,nome,parametros,valor) 
{ 
	if(valor == 1){
		h = 640;
		w = 480; 
		alt = ((screen.height - h)/2) - 20; 
		larg = ((screen.width - w)/2) - 10; 
		var parametros="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, top="+alt+",left="+larg+", width=340, height=450"; 
		window.open(url,nome,parametros);
	}else{
		h = 640;
		w = 480;
		alt = ((screen.height - h)/2) - 20; 
		larg = ((screen.width - w)/2) - 10; 
		var parametros="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, top="+alt+",left="+larg+", width=660, height=510"; 
		window.open(url,nome,parametros);
	}
} 

function verificar(endereco){
	if(confirm("Deseja realmente Excluir o Imóvel?")) {
		window.location = endereco;
	} else {
	}
}
