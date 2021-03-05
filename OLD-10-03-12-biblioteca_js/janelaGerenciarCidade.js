function gerenciarCidade(){
	var codCidadeSelecionado = 0


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

	function cidadeExcluir(value){
		if(value == 1){
			return '<center><img src="img/ic_desativar.png" /></center>'
		}else{
			return '<center><img src="img/ic_ativar.png" /></center>'
		}
	}

	//popular combo dos UF's
	var storeUF = new Ext.data.Store({
		id: 'storeUF',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_cidade.php',
			method: 'POST'
		}),
		baseParams:{acao: "UFListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codUf','uf']
		},[ 
			{name: 'codUf', type: 'int'},
			{name: 'uf', type: 'string'}

		])
		//sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	storeUF.load();
		
	cbUf = new Ext.form.ComboBox({
		id: 'cbUf',
		typeAhead: false,
		fieldLabel: '<b>UF</b>',
		store: storeUF, //carregar UF's
		mode:'local',
		triggerAction: 'all',
		editable: false,
		anchor: '98%',
		displayField: 'uf',
		valueField: 'codUf',
		forceSelection: true,
		allowBlank : false,
		emptyText: 'Selecione...'
	})
	
	tfCidade = new Ext.form.TextField({
		fieldLabel: '<b>Cidade</b>',
		name: 'cidade',
		id: 'tfCidade',
		allowBlank:false,
		blankText:"Por favor insira a cidade.",
		autoCreate: {tag: 'input', type: 'text', maxlength: '100'}, //seta o tamanho máximo q o input vai aceitar
		disabled:true,
		anchor : '98%'	
	})
	
	//habilitar text field da cidade quando um uf for selecionado e listar as cidades
	cbUf.on('select', function() {
		if(cbUf.getValue() != 0){
			tfCidade.enable();
		}else{
			tfCidade.disable();
		}

		cidadeStore.load({params: {acao: "cidadeListar", codUf: cbUf.getValue()}})
	});

	//zerar form para criação de uma nova cidade
	function novo(){
		codCidadeSelecionado = 0;
		tfCidade.disable();
		cidadeGrid.getForm().reset();
		cidadeStore.load({params: {acao: "cidadeListar", codUf: "0"}})
	}
	
	function salvar (){

		if(cbUf.getValue() != ""){
			if(tfCidade.getValue() != "" && tfCidade.isValid()){
				Ext.Ajax.request({
					url: 'modulos/diversos/gerenciar_cidade.php',
					params: { 
						acao			: 'cidadeCadastrar',
						codUf			: cbUf.getValue(),
						cidade 			: tfCidade.getValue(),
						CidadeSelecionado 	: codCidadeSelecionado
					},
					callback: function(options, success, response) {
						var retorno = Ext.decode(response.responseText);
						
						if(retorno.success == false){
							msg('Informação','Problema ao cadastrar a cidade!')
						}else{
							msg('Informação','Operação executada com sucesso!');
							cidadeStore.load({params: {acao: "cidadeListar", codUf: cbUf.getValue()}});
						}
					}
				})

				
				
			}else{
				msg('Informação','Preencha o nome da cidade para salvar!');
			}
		}else{
			msg('Informação','Selecione o UF para cadastrar uma nova cidade!');
		}
	}

	//buscar cidades para popular o grid
	cidadeStore = new Ext.data.Store({

		id: 'cidadeStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/diversos/gerenciar_cidade.php',
			method: 'POST'
		}),
		baseParams:{
				acao: "cidadeListar", 
				codUf : "0"
		},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codCidade','cidade','status']
		},[ 
			{name: 'codCidade', type: 'int', mapping: 'codCidade'},
			{name: 'cidade', type: 'string', mapping: 'cidade'},
			{name: 'status', type: 'int', mapping: 'status'}
		])
	}) 
	
	//colunas do grid
	cidadeColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'codCidade',
	        dataIndex: 'codCidade', 
	        width: 100,
			readOnly: false,
			hidden: true
	      },{
	        header: '<b>Cidade</b>',
	        dataIndex: 'cidade', 
	        width: 180,
			readOnly: false
	      },{
	        header: '<b>Excluir</b>',
	        dataIndex: 'status', 
	        width: 50,
			align: 'center',
			renderer: cidadeExcluir,
			readOnly: true
	      }
	])	
	cidadeColuna.defaultSortable = true
	
	
	//grid que lista as cidades cadastradas
	var gridListaCidade = new Ext.grid.GridPanel({
		id: 'gridListaCidade',
		ds: cidadeStore,
		cm: cidadeColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				var dados = grid.store.getAt(linha);
				var codCidade = dados.get('codCidade')
				var statusCidade = dados.get('status')
				manterCidade(coluna,statusCidade,codCidade)
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
						manterCidade('2',dados.get('status'),dados.get('codCidade'))
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
					codCidadeSelecionado = rec.data.codCidade
					Ext.getCmp("cidadeGrid").getForm().loadRecord(rec)
				}			
			}
		}),
		autoExpandColumn: 'codCidade',
		height: 250,
		border: true
	})
	//grid que lista as cidades cadastradas
	
	//ativar e desativar as cidades
	function manterCidade(coluna, statusCidade,codCidade){
		if(coluna == '2'){
			if(statusCidade == 1){
				if(confirm('Tem certeza que deseja desativar essa cidade?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_cidade.php',
						params: { 
							acao		: 'cidadeGerenciar',
							codCidade	:  codCidade,
							status		:  0
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'cidade desativada com sucesso!')
								cidadeStore.reload()
								cidadeGrid.getForm().reset()
							}
						}
					})
				}
			}else{
				if(confirm('Tem certeza que deseja ativar esse Grupo?')){
					Ext.Ajax.request({
						url: 'modulos/diversos/gerenciar_cidade.php',
						params: { 
							acao		: 'cidadeGerenciar',
							codCidade	:  codCidade,
							status		:  1
						},
						callback: function(options, success, response) {
							
							var retorno = Ext.decode(response.responseText);
							
							if(retorno.success == false){
								msg('Erro', 'Erro ao tentar executar a operação!')
							}else{
								msg('Mensagem', 'cidade ativada com sucesso!')
								cidadeStore.reload()
								cidadeGrid.getForm().reset()
							}
						}
					})
				}
			}
		}
	
	}
	//ativar e desativar as cidades
	
	
	
	var cidadeGrid = new Ext.FormPanel({
		id: 'cidadeGrid',
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
						items: [cbUf]
					},{
						columnWidth: 1,
						labelWidth: 50,
						layout: 'form',
						autoHeight: true,
						border: false,
						items: [tfCidade]
					},{
						columnWidth: 1,
						layout: 'fit',
						items: [gridListaCidade]
					}]
				}]
					
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

	cidadeStore.load({params: {acao: "cidadeListar", codUf: "0"}})
	
	var janelaGerenciarCidade = new Ext.Window({
		title: 'Gerenciar Cidade',
		id: 'janelaGerenciarCidade',
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
		items:[cidadeGrid]
	})
	janelaGerenciarCidade.show()
}