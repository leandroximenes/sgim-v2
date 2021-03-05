function contratoEncerrar(codContrato){
	var codGrupoSelecionado = 0


	var msg = function(title, msg){
		Ext.Msg.show({
			title: title, 
			msg: msg,
			minWidth: 200,
			modal: true,
			icon: Ext.Msg.INFO,
			buttons: Ext.Msg.OK
		});
	};

	dfData = new Ext.form.DateField({
		fieldLabel: '<b>Data</b>',
		name: 'nome',
		allowBlank:false,
		format : 'd/m/Y',
		blankText:"Por favor insira o nome.",
		maxLength: 100,
		anchor : '98%'
	})

	txObservacoes = new Ext.form.TextArea({
		fieldLabel: '<b>Observações</b>',
		id: 'txObservacoes',
		allowBlank: true,
		maxLength: 100,
		anchor : '98%',
		height : 50
	})

	function manterImovel(coluna, statusImovel,codGrupo){
		
		if(coluna == '2'){
			if(statusImovel == 1){
				if(confirm('Tem certeza que deseja desativar esse Grupo?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_grupo.php',
						params: { 
							acao: 'grupoGerenciar',
							codGrupo:  codGrupo,
							status:  0
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Grupo desativado com sucesso!')
								grupoStore.reload()
								grupoGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar esse Grupo?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_grupo.php',
						params: { 
							acao: 'grupoGerenciar',
							codGrupo:  codGrupo,
							status:  1
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Grupo ativado com sucesso!')
								grupoStore.reload()
								grupoGrid.getForm().reset()
							}
						}
					})
				}
			}
		}
	
	}

	var grupoGrid = new Ext.FormPanel({
        id: 'grupoGrid',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
        	columnWidth: 1,

			bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding:10px 10px',
            items:[{
				layout:'column',
				width: '100%',
				border: false,
				items: [{	
					columnWidth: 1,
					labelWidth: 100,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [dfData, txObservacoes]
				}]
			}]
				
        }],	
		bbar: ['->',

			botaoSalvar= new Ext.Button({
				id: 'botaoSalvar',
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })



function salvar (){

	if(dfData.getValue() != ""){
		if(confirm('Tem certeza que deseja encerrar esse Contrato?')){
			Ext.Ajax.request({
				url: 'modulos/contrato/gerenciar_contrato.php',
				params: { 
					acao: 'contratoEncerrar',
					codContrato: codContrato,
					data : dfData.getValue(),
					observacao : txObservacoes.getValue()
				},
				callback: function(options, success, response) {
					var retorno = Ext.decode(response.responseText);
					
					if(retorno.success == false){
						msg('Informação','Problema ao encerrar contrato!')
					}else{
						msg('Informação','Operação executada com sucesso!')
					}
				}
			})

			janelaGerenciarGrupo.close()
		}else{
			msg('Informação','Operação Cancelada!')
		}
	}else{
		msg('Informação','Preencha o nome do Grupo depois clique em salvar!')
	}
}

	var janelaGerenciarGrupo = new Ext.Window({
		title: 'Encerrar Contrato',
		id: 'janelaEncerrarContrato',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		autoHeight: true,
		width: 400,
		anchor: 50,
		height: 250,
		closeAction:'close',
		iconCls: 'grupo',
		modal: true,
		items:[grupoGrid]
	})
	janelaGerenciarGrupo.show()
}