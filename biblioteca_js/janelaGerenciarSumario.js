function gerenciarSumario(codContrato){

	var codSumarioSelecionado = 0;

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

	sumarioStore = new Ext.data.Store({
		id: 'sumarioStore',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/imovel/gerenciar_imovel.php',
			method: 'POST'
		}),
		baseParams:{
			acao: "imovelSumarioListar",
			codigoContrato: codContrato
		},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codSumario']
		},[ 
			{name: 'codSumario', type: 'int', mapping: 'codSumario'},
			{name: 'codContrato', type: 'int', mapping: 'codContrato'},
			{name: 'data', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'data'},
			{name: 'observacao', type: 'string', mapping: 'observacao'},
			{name: 'status', type: 'int', mapping: 'status'}
		])
	}) 
	
	sumarioColuna = new Ext.grid.ColumnModel(
		[{
	        header: 'Código',
	        dataIndex: 'codSumario', 
	        width: 40,
			readOnly: false,
			hidden: true
	      },{
	        header: '<b>Data</b>',
			renderer: formatoData,
	        dataIndex: 'data', 
	        width: 40,
			readOnly: false
	      },{
	        header: '<b>Observação</b>',
	        dataIndex: 'observacao', 
	        width: 200,
			readOnly: false
	      }
	])	
	
	sumarioColuna.defaultSortable = true

	tfContratante = new Ext.form.TextField({
		fieldLabel: '<b>Contratante</b>',
		id: 'tfContratante',
		maxLength: 100,
		anchor : '98%',
		disabled: true
	})
	
	dtInicioLocacao = new Ext.form.DateField({
		id: 'dtInicioLocacao',
		name: 'dtInicioLocacao',
		fieldLabel: '<b>Início Locação</b>',
		allowBlank: false,
		format : 'd/m/Y',
		disabled: true,
		anchor: '98%'
	})

	dtFimLocacao = new Ext.form.DateField({
		id: 'dtFimLocacao',
		name: 'dtFimLocacao',
		fieldLabel: '<b>Fim Locação</b>',
		allowBlank: false,
		disabled: true,
		format : 'd/m/Y',
		anchor: '98%'
	})

	txObservacoes = new Ext.form.TextArea({
		fieldLabel: '<b>Observações</b>',
		name: 'observacao',
		id: 'txObservacoes',
		allowBlank: true,
		maxLength: 100,
		anchor : '98%',
		height : 50
	})

	var gridListaSumario = new Ext.grid.GridPanel({
		id: 'gridListaSumario',
		ds: sumarioStore,
		cm: sumarioColuna,
		listeners:{
			cellclick: function(grid,linha,coluna){
				var dados = grid.store.getAt(linha);
				var codSumario = dados.get('codSumario')				
			},
			rowcontextmenu: function(grid,rowIndex, e){
				e.stopEvent()
				var acao;
				var dados = grid.store.getAt( rowIndex );
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
					codSumarioSelecionado = rec.data.codSumario
					Ext.getCmp("sumarioGrid").getForm().loadRecord(rec)
				}			
			}
		}),
		autoExpandColumn: 'codSumario',
		height: 250,
		border: true
	})

	var sumarioGrid = new Ext.FormPanel({
        id: 'sumarioGrid',
        frame: true,
		autoHeight: true,
        labelAlign: 'left',
        layout: 'column',	
		items: [{
            columnWidth: 1,
            xtype: 'fieldset',
			style: 'margin: 0px 5px 5px 5px;',
            title:'Dados do Contrato', 
			bodyStyle: Ext.isIE ? 'padding:0 0 5px 10px' : 'padding: 10px 0px 5px 10px',
            items:[{
				layout:'column',
				width: '100%',
				border: false,
				items: [{	
					columnWidth: .8,
					labelWidth: 95,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [tfContratante]
				},{	
					columnWidth: .27,
					labelWidth: 95,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [dtInicioLocacao]
				},{	
					columnWidth: .25,
					labelWidth: 80,
					layout: 'form',
					autoHeight: true,
					border: false,
					items: [dtFimLocacao]
				}]
			}]
			},{
				columnWidth: 1,
				xtype: 'fieldset',
				style: 'margin: 0px 5px 5px 5px;',
				title:'Súmula', 
				bodyStyle: Ext.isIE ? 'padding:0 0 0px 10px' : 'padding: 10px 10px 5px 10px',
				items:[{
					layout:'column',
					width: '100%',
					border: false,
					items: [{
						columnWidth: 0.8,
						labelWidth: 95,
						layout: 'form',
						border: false,
						items: [txObservacoes]
					}]
				}]	
			},{
			columnWidth: 1,
			layout: 'fit',
			items: [gridListaSumario]
        }],	
		bbar: [
			'->',			
			botaoNovo = new Ext.Button({
				text: 'Novo',
				tooltip: 'Novo',
				handler: novo,   
				iconCls:'botaoNovo'
			}),'->',
			
			botaoSalvar= new Ext.Button({
				text: 'Salvar',
				tooltip: 'Salvar',
				handler: salvar,
				iconCls:'botaoSalvar'
			})
		]
    })


function novo(){
	codSumarioSelecionado = 0
	sumarioStore.load()
	sumarioGrid.getForm().reset()
}	

function salvar (){

	if(txObservacoes.getValue() != ""){
		Ext.Ajax.request({
			url: 'modulos/imovel/gerenciar_imovel.php',
			params: { 
				acao			: 'cadastrarSumario',
				codSumario		: codSumarioSelecionado,
				codigoContrato	: codContrato,
				observacao		: txObservacoes.getValue()
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

	}else{
		msg('Informação','Informe uma observação!');
	}
}

	sumarioStore.load({params: {codigoContrato : codContrato}})

	Ext.Ajax.request({
	url: 'modulos/contrato/gerenciar_contrato.php',
	params: { 
		acao	    : 'contratoUnicoListar',
		codContrato : codContrato
	},
	callback: function(options, success, response) {
		
		var retorno = Ext.decode(response.responseText);
		
		document.getElementById('dtInicioLocacao').value    = retorno.resultado[0].dataInicio;
		document.getElementById('dtFimLocacao').value		= retorno.resultado[0].dataFim;
		document.getElementById('tfContratante').value		= retorno.resultado[0].inquilino;
		}
	})
	
	var janelaGerenciarSumario = new Ext.Window({
		title: 'Súmula',
		id: 'janelaGerenciarSumario',
		border: false,
		draggable: true,
		resizable: false,
		shadow: false,
		autoHeight: false,
		width: 900,
		closeAction:'close',
		iconCls: 'manterUsuario',
		modal: true,
		items:[sumarioGrid]
	})
	janelaGerenciarSumario.show()
}