function gerenciarProfissao(){
	var codProfissaoSelecionada = 0


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

	function profissaoExcluir(value){
		if(value == 1){
			return '<center><img src="img/ic_desativar.png" /></center>'
		}else{
			return '<center><img src="img/ic_ativar.png" /></center>'
		}
	}


	profissaoStore = new Ext.data.Store({
		id: 'profissaoStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_profissao.php',
			method: 'POST'
		}),
		baseParams:{acao: "profissaoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codProfissao','nome']
		},[ 
			{name: 'codProfissao', type: 'int', mapping: 'codProfissao'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'status', type: 'boolean', mapping: 'status'}
		])
	}) 
	
	profissaoColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codProfissao',
	        dataIndex: 'codProfissao', 
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
			renderer: profissaoExcluir,
			readOnly: true
	      }
	])	
	profissaoColuna.defaultSortable = true

	tfNome = new Ext.form.TextField({
		fieldLabel: '<b>Profissão</b>',
		name: 'nome',
		allowBlank:false,
		blankText:"Por favor insira o nome.",
		maxLength: 100,
		anchor : '98%'
	})

	function manterImovel(coluna, statusImovel,codProfissao){
		
		if(coluna == '2'){
			if(statusImovel == 1){
				if(confirm('Tem certeza que deseja desativar essa Profissão?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_profissao.php',
						params: { 
							acao: 'profissaoGerenciar',
							codProfissao:  codProfissao,
							status:  0
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Profissão desativada com sucesso!')
								profissaoStore.reload()
								profissaoGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar esse Profissão?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_profissao.php',
						params: { 
							acao: 'profissaoGerenciar',
							codProfissao:  codProfissao,
							status:  1
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Profissão ativada com sucesso!')
								profissaoStore.reload()
								profissaoGrid.getForm().reset()
							}
						}
					})
				}
			}
		}
	
	}

	var gridListaProfissao = new Ext.grid.GridPanel({
		id: 'gridListaProfissao',
		ds: profissaoStore,
		cm: profissaoColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				var dados = grid.store.getAt(linha);
				var codProfissao = dados.get('codProfissao')
				var statusProfissao = dados.get('status')
				manterImovel(coluna,statusProfissao,codProfissao)
				
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
						manterImovel('2',dados.get('status'),dados.get('codProfissao'))
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
					codProfissaoSelecionada = rec.data.codProfissao
					Ext.getCmp("profissaoGrid").getForm().loadRecord(rec)
				}			
			}
		}),
		autoExpandColumn: 'codProfissao',
		height: 250,
		border: true
	})

	var profissaoGrid = new Ext.FormPanel({
        id: 'profissaoGrid',
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
					labelWidth: 70,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [tfNome]
				}]
			}]
				
        },{
			columnWidth: 1,
			layout: 'fit',
			items: [gridListaProfissao]
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
	codProfissaoSelecionada = 0
	profissaoStore.load()
	profissaoGrid.getForm().reset()
}	

function salvar (){

	if(tfNome.getValue() != ""){
		Ext.Ajax.request({
			url: 'modulos/diversos/gerenciar_profissao.php',
			params: { 
				acao: 'profissaoCadastrar',
				codProfissao: codProfissaoSelecionada,
				nome : tfNome.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar o Profissão!')
				}else{
					msg('Informação','Operação executada com sucesso!')
				}
			}
		})

		novo()
		codProfissaoSelecionada = 0
		profissaoGrid.getForm().reset()
	}else{
		msg('Informação','Preencha o nome do Profissão depois clique em salvar!')
	}
}

	profissaoStore.load({params: {start: 0, limit: 15}})

	var janelaGerenciarProfissao = new Ext.Window({
		title: 'Gerenciar Profissão',
		id: 'janelaGerenciarProfissao',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		autoHeight: true,
		width: 300,
		anchor: 50,
		height: 250,
		closeAction:'close',
		iconCls: 'profissao',
		modal: true,
		items:[profissaoGrid]
	})
	janelaGerenciarProfissao.show()
}