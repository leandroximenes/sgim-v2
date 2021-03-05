function reajusteContrato(codContrato, nomePessoa){
	
	var codContratoSelecionado;

		
	var sm2 = new Ext.grid.CheckboxSelectionModel;

    var ds = new Ext.data.Store({
		id: 'mRemoteDataStore',
		baseParams:{
			acao: 'reajusteContratoListar',
			codContrato: codContrato					
		},
		pruneModifiedRecords:true,
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/contrato/reajuste_contrato.php',
			method: 'post'
		}),
		
		reader: new Ext.data.JsonReader({
			totalProperty: 'total',
			root: 'resultado',
			id: ['codReajuste']
		},[
			{name: 'codReajuste', type: 'int', mapping: 'codReajuste'},
			{name: 'valorAtual', type: 'float', mapping: 'valorAtual'},
			{name: 'data', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'data'}
		])
	})
	

	var cm = new Ext.grid.ColumnModel([
		{
			id:'codReajuste',
			header: "<b>Código</b>", 
			readOnly: true, 
			width: 50, 
			align: 'center',
			dataIndex: 'codReajuste'
		},{
			header: "<b>Valor</b>", 
			width: 120, 
			sortable: true, 
			align: 'center',
			dataIndex: 'valorAtual',
			renderer: formatoMoeda
		},{	
			header: '<b>Data</b>',
			dataIndex: 'data', 
			align: 'center',
			width: 130,
			renderer: formatoData
		 }
	])
	cm.defaultSortable = true
	
	tfNovoReajuste = new Ext.form.TextField({
		fieldLabel: '<b>Novo Reajuste</b>',
		name: 'novoReajuste',
		id: 'tfNovoReajuste',
		allowBlank:false,
		anchor : '95%'
	})

	btNovoReajuste = new Ext.Button({
		text: '<b></b>',
		tooltip: '<b>Novo Reajuste</b>',
		iconCls:'botaoSalvar',
		handler: salvar
	})
	
 	var grid = new Ext.grid.GridPanel({
        id:'grid',
        cm: cm,
		ds: ds,
        frame:true,
        iconCls:'icon-grid',

		pruneModifiedRecords:true,
		tbar:["<b>Novo Reajuste (R$): </b>",
			tfNovoReajuste,
			btNovoReajuste
		],
		height: 250
    })
	
	function salvar(){
		if(tfNovoReajuste.getValue() == ''){
			Ext.Msg.alert('Aviso', 'Por favor insira o novo valor de Reajuste!')
		}else{
			Ext.Ajax.request({
				url: 'modulos/contrato/reajuste_contrato.php',
				params: { 
					acao             : 'reajusteContratoCadastrar',
					codContrato      : codContrato,
					valorAtual       : tfNovoReajuste.getValue()
				},
				callback: function(options, success, response) {
					var retorno = Ext.decode(response.responseText);


					if(retorno.success == false){
						msg('Informação','Problema ao cadastrar o imóvel!')
					}else{
						msg('Informação','Operação executada com sucesso!')
						
						tfNovoReajuste.value = ''
						ds.load({params:{start:0, limit:10}});
					}
				}
			})
		}
	}

	win = new Ext.Window({
		title: 'Reajsutar Contrato',
		id: 'perfisRelacionar',
		layout: 'fit',
		border: false,
		draggable: true,
		resizable: false,
		width: 330,
		height: 300,
		iconCls: 'manterUsuario',
		modal: true,
		items:[grid]
	})
	win.show()
	ds.load({params:{start:0, limit:10}});
}