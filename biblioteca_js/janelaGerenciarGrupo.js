function gerenciarGrupo(){
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

	function grupoExcluir(value){
		if(value == 1){
			return '<center><img src="img/ic_desativar.png" /></center>'
		}else{
			return '<center><img src="img/ic_ativar.png" /></center>'
		}
	}


	grupoStore = new Ext.data.Store({
		id: 'grupoStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_grupo.php',
			method: 'POST'
		}),
		baseParams:{acao: "grupoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codGrupo','nome']
		},[ 
			{name: 'codGrupo', type: 'int', mapping: 'codGrupo'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'status', type: 'boolean', mapping: 'status'}
		])
	}) 
	
	grupoColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codGrupo',
	        dataIndex: 'codGrupo', 
	        width: 100,
			readOnly: false,
			hidden: true
	      },{
	        header: '<b>Nome</b>',
	        dataIndex: 'nome', 
	        width: 180,
			readOnly: false
	      },{
	        header: '<b>Excluir</b>',
	        dataIndex: 'status', 
	        width: 50,
			align: 'center',
			renderer: grupoExcluir,
			readOnly: true
	      }
	])	
	grupoColuna.defaultSortable = true

	tfNome = new Ext.form.TextField({
		fieldLabel: '<b>Grupo</b>',
		name: 'nome',
		allowBlank:false,
		blankText:"Por favor insira o nome.",
		maxLength: 100,
		anchor : '98%'
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

	var gridListaGrupo = new Ext.grid.GridPanel({
		id: 'gridListaGrupo',
		ds: grupoStore,
		cm: grupoColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				var dados = grid.store.getAt(linha);
				var codGrupo = dados.get('codGrupo')
				var statusGrupo = dados.get('status')
				manterImovel(coluna,statusGrupo,codGrupo)
				
			},
			rowcontextmenu: function(grid,rowIndex, e){
				e.stopEvent()
				var acao;
				var dados = grid.store.getAt( rowIndex );
				
				if(dados.get('status') == 1){
					acao = 'Desativar';
					status = 'botaoDesativar'; 
				}else{
					acao = 'Ativar';
					status = 'botaoAtivar'; 
				}
				
				var contextMenu = new Ext.menu.Menu();
				contextMenu.add({
					text: acao,
					iconCls: status,
					handler: function (){
						manterImovel('2',dados.get('status'),dados.get('codGrupo'))
					}
				});
				
				contextMenu.showAt(e.xy);
			}
		},
		viewConfig: {
			forceFit: true,
			getRowClass: function(record, rowIndex, rp, ds){
				if(record.data.status == '0'){
					return 'linhaDesativada'
				}
			}
		}, 
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: true,
			listeners: {
				rowselect: function(sm, row, rec) {
					codGrupoSelecionado = rec.data.codGrupo
					Ext.getCmp("grupoGrid").getForm().loadRecord(rec)
				}			
			}
		}),
		autoExpandColumn: 'codGrupo',
		height: 250,
		border: true
	})

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
					labelWidth: 50,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [tfNome]
				}]
			}]
				
        },{
			columnWidth: 1,
			layout: 'fit',
			items: [gridListaGrupo]
        }],	
		bbar: ['->',
			
			botaoNovo = new Ext.Button({
				id: 'botaoNovo',
				text: 'Novo',
				tooltip: 'Novo',
				handler: novo,   
				iconCls:'botaoNovo'
			}),'-',

			botaoSalvar= new Ext.Button({
				id: 'botaoSalvar',
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })


function novo(){
	codGrupoSelecionado = 0
	grupoStore.load()
	grupoGrid.getForm().reset()
}	

function salvar (){

	if(tfNome.getValue() != ""){
		Ext.Ajax.request({
			url: 'modulos/diversos/gerenciar_grupo.php',
			params: { 
				acao: 'grupoCadastrar',
				codGrupo: codGrupoSelecionado,
				nome : tfNome.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar o Grupo!')
				}else{
					msg('Informação','Operação executada com sucesso!')
				}
			}
		})

		novo()
		codGrupoSelecionado = 0
		grupoGrid.getForm().reset()
	}else{
		msg('Informação','Preencha o nome do Grupo depois clique em salvar!')
	}
}

	grupoStore.load({params: {start: 0, limit: 15}})

	var janelaGerenciarGrupo = new Ext.Window({
		title: 'Gerenciar Grupo',
		id: 'janelaGerenciarGrupo',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		autoHeight: true,
		width: 300,
		anchor: 50,
		height: 250,
		closeAction:'close',
		iconCls: 'grupo',
		modal: true,
		items:[grupoGrid]
	})
	janelaGerenciarGrupo.show()
}