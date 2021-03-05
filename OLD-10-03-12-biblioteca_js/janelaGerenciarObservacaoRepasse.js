function gerenciarObservacaoRepasse(codPagamento){
	
	var codPagamentoSelecionada;

	txObservacoes = new Ext.form.TextArea({
		fieldLabel: '<b>Observações</b>',
		name: 'obs',
		id: 'txObservacoes',
		allowBlank: true,
		anchor : '98%',
		height : 70
	})

	var janelaObservacao = new Ext.FormPanel({
        id: 'janelaObservacao',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
        	columnWidth: 1,
            xtype: 'panel',
			frame: true,
			bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
            items:[{
				layout:'column',
				width: '100%',
				border: false,
				items: [{
					columnWidth: 1,
					labelWidth: 110,
					layout: 'form',
					border: false,
					items: [txObservacoes]
				}]
			}]
				
        }],
		bbar: [
			botaoSalvar= new Ext.Button({
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })

	codPagamentoSelecionada = codPagamento
		

function carregarObservacoes(){
	if(codPagamentoSelecionada != "" || codPagamentoSelecionada != 0){
		Ext.Ajax.request({
			url: 'modulos/pagamento/gerenciar_pagamento.php',
			params: {
				acao:'pagamentoObservacaoRepasse',
				codPagamento: codPagamentoSelecionada
			},
			callback: function(options, success, response){
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success != false){
					Ext.getCmp("txObservacoes").setValue(retorno.resultado[0].observacao);
				}else{
					return;
				}
			}
		})
	}
}

	
function salvar (){
	
	if(txObservacoes.getValue() != ''){
		Ext.Ajax.request({
			url: 'modulos/pagamento/gerenciar_pagamento.php',
			params: { 
				acao         : 'pagamentoObservacaoRepasseCadastrar',
				codPagamento : codPagamentoSelecionada,
				observacoes  : txObservacoes.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar os dados!');
				}else{
					msg('Informação','Operação executada com sucesso!');
					repasseStore.reload()
					win.close();
				}
			}
		})
	}else{
		msg('Informação','Existem campos obrigatórios em Branco!')
	}	
}

	win = new Ext.Window({
		title: 'Observações de Repasse',
		id: 'dadoBancarioRelacionar',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		width: 500,
		iconCls: 'manterUsuario',
		modal: true,
		items:[janelaObservacao]
	})
	win.show();
	carregarObservacoes();
}