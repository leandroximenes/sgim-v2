function gerenciarBanco(){
	var codBancoSelecionado = 0


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

	function bancoExcluir(value){
		if(value == 1){
			return '<center><img src="img/ic_desativar.png" /></center>'
		}else{
			return '<center><img src="img/ic_ativar.png" /></center>'
		}
	}

	bancoStore = new Ext.data.Store({
		id: 'bancoStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_banco.php',
			method: 'POST'
		}),
		baseParams:{acao: "bancoListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codBanco','nome']
		},[ 
			{name: 'codBanco', type: 'int', mapping: 'codBanco'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'numeroBanco', type: 'string', mapping: 'numeroBanco'},
			{name: 'status', type: 'boolean', mapping: 'status'}
		])
	}) 
	
	bancoColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codBanco',
	        dataIndex: 'codBanco', 
	        width: 100,
			readOnly: false,
			hidden: true
	      },{
	        header: '<b>Nome</b>',
	        dataIndex: 'nome', 
	        width: 180,
			readOnly: false
	      },{
	        header: '<b>Número</b>',
	        dataIndex: 'numeroBanco', 
	        width: 90,
			readOnly: false
	      },{
	        header: '<b>Excluir</b>',
	        dataIndex: 'status', 
	        width: 60,
			align: 'center',
			renderer: bancoExcluir,
			readOnly: true
	      }
	])	
	bancoColuna.defaultSortable = true

	tfNome = new Ext.form.TextField({
		fieldLabel: '<b>Banco</b>',
		name: 'nome',
		allowBlank:false,
		blankText:"Por favor insira o nome do banco.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '50'}, //seta o tamanho máximo q o input vai aceitar
		anchor : '98%'
	})
	
	tfNumero = new Ext.form.NumberField({
		fieldLabel: '<b>Número</b>',
		name: 'numeroBanco',
		allowBlank:false,
		blankText:"Por favor insira o número do banco.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '20'}, //seta o tamanho máximo q o input vai aceitar
		anchor : '98%'
	});

	function manterBanco(coluna, statusBanco,codBanco){
		
		if(coluna == '3'){
			if(statusBanco == 1){
				if(confirm('Tem certeza que deseja desativar esse Banco?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_banco.php',
						params: { 
							acao: 'bancoGerenciar',
							codBanco:  codBanco,
							status:  0
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Banco desativado com sucesso!')
								bancoStore.reload()
								bancoGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar esse Banco?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_banco.php',
						params: { 
							acao: 'bancoGerenciar',
							codBanco:  codBanco,
							status:  1
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'Banco ativado com sucesso!')
								bancoStore.reload()
								bancoGrid.getForm().reset()
							}
						}
					})
				}
			}
		}
	
	}

	var gridListaBanco = new Ext.grid.GridPanel({
		id: 'gridListaBanco',
		ds: bancoStore,
		cm: bancoColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				var dados = grid.store.getAt(linha);
				var codBanco = dados.get('codBanco')
				var statusBanco = dados.get('status')
				manterBanco(coluna,statusBanco,codBanco)
				
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
						manterBanco('2',dados.get('status'),dados.get('codBanco'))
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
					codBancoSelecionado = rec.data.codBanco
					Ext.getCmp("bancoGrid").getForm().loadRecord(rec)
				}			
			}
		}),
		autoExpandColumn: 'codBanco',
		height: 250,
		border: true
	})

	var bancoGrid = new Ext.FormPanel({
        id: 'bancoGrid',
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
				},{
					columnWidth: 1,
					labelWidth: 50,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [tfNumero]
				}]
			}]
				
        },{
			columnWidth: 1,
			layout: 'fit',
			items: [gridListaBanco]
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
	codBancoSelecionado = 0
	bancoStore.load()
	bancoGrid.getForm().reset()
}	

function salvar (){

	if(tfNome.getValue() != "" && tfNumero.getValue() != ""){
		Ext.Ajax.request({
			url: 'modulos/diversos/gerenciar_banco.php',
			params: { 
				acao		: 'bancoCadastrar',
				codBanco	: codBancoSelecionado,
				nome 		: tfNome.getValue(),
				numeroBanco	: tfNumero.getValue()
			},
			callback: function(options, success, response) {
				var retorno = Ext.decode(response.responseText);
				
				if(retorno.success == false){
					msg('Informação','Problema ao cadastrar o Banco!')
				}else{
					msg('Informação','Operação executada com sucesso!')
				}
			}
		})

		novo()
		codBancoSelecionado = 0
		bancoGrid.getForm().reset()
	}else{
		msg('Informação','Preencha o nome e o número do Banco e depois clique em salvar!')
	}
}

	bancoStore.load({params: {start: 0, limit: 15}})

	var janelaGerenciarBanco = new Ext.Window({
		title: 'Gerenciar Banco',
		id: 'janelaGerenciarBanco',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		autoHeight: true,
		width: 300,
		anchor: 50,
		height: 250,
		closeAction:'close',
		iconCls: 'banco',
		modal: true,
		items:[bancoGrid]
	})
	janelaGerenciarBanco.show()
}