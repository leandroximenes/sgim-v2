/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.onReady(function(){

    /**
     * Handler specified for the 'Available' column renderer
     * @param {Object} value
     */
    function formatDate(value){
        return value ? value.dateFormat('d m, Y') : '';
    }
	
	function formatoDia(value){
	
		novaData = new Date(value)
		return novaData.dateFormat('d/m/Y')
	}



    var cm = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [
		{
			header: '<b>Pagamento</b>',
			dataIndex: 'codPagamento', 
			width: 100,
			readOnly: false,
			hidden: true
		},{
			header: '<b>Contrato</b>',
			dataIndex: 'codContrato', 
			width: 70,
			hidden: true,
			readOnly: false
		},{
			header: '<b>Parc.</b>',
			dataIndex: 'parcela', 
			width: 30,
			align: 'center',
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Valor Pagamento</b>',
			dataIndex: 'valorPagamento', 
			width: 60,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Data Pagamento</b>',
			dataIndex: 'dataPagamento', 
			width: 60,
			align: 'center',
			renderer: formatoDia
		},{
			header: '<b>Valor Desconto</b>',
			dataIndex: 'valorDesconto', 
			width: 60,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Valor Multa</b>',
			dataIndex: 'valorMulta', 
			width: 50,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Valor Gasto</b>',
			dataIndex: 'valorGastoServico', 
			width: 55,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Repasse</b>',
			dataIndex: 'valorRepasse', 
			width: 50,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Data Repasse</b>',
			dataIndex: 'dataRepasse', 
			width: 60,
			align: 'center',
			renderer: formatoDia
		},{
			header: '<b>IR</b>',
			dataIndex: 'valorIR', 
			width: 50,
			align: 'center',
			//renderer: formatoMoeda,
            editor: new Ext.form.NumberField({
                allowBlank: false,
                allowNegative: false,
                maxValue: 100000
            })
		},{
			header: '<b>Data Vencimento</b>',
			dataIndex: 'dataVencimento', 
			width: 60,
			align: 'center',
			renderer: formatoDia,
            editor: new Ext.form.DateField({
                format: 'm/d/y',
                minValue: '01/01/06',
                disabledDays: [0, 6],
                disabledDaysText: 'Plants are not available on the weekends'
            })
		}]
    });

   pessoaStore = new Ext.data.Store({
		id: 'pessoaStore',
		proxy: new Ext.data.HttpProxy({
			url: '../modulos/pagamento/gerenciar_pagamento.php',
			method: 'POST'
		}),
		baseParams:{
			acao: "pagamentoListar",
			codContrato: 1
		},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codPagamento']
		},[ 
			{name: 'codPagamento', type: 'int', mapping: 'codPagamento'},
			{name: 'codContrato', type: 'int', mapping: 'codContrato'},
			{name: 'parcela', type: 'int', mapping: 'parcela'},
			{name: 'valorPagamento', type: 'float', mapping: 'valorPagamento'},
			{name: 'valorDesconto', type: 'float', mapping: 'valorDesconto'},
			{name: 'valorMulta', type: 'float', mapping: 'valorMulta'},
			{name: 'valorGastoServico', type: 'float', mapping: 'valorGastoServico'},
			{name: 'valorRepasse', type: 'float', mapping: 'valorRepasse'},
			{name: 'valorIR', type: 'float', mapping: 'valorIR'},
			{name: 'dataRepasse', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataRepasse'},
			{name: 'dataPagamento', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataPagamento'},
			{name: 'dataVencimento', type: 'date', dateFormat:'Y-m-d H:i:s', mapping: 'dataVencimento'}
		]),
		sortInfo: {field:'codPagamento', direction:'ASC'}
	})

    // create the editor grid
    var grid = new Ext.grid.EditorGridPanel({
        store: pessoaStore,
        cm: cm,
        renderTo: 'editor-grid',
        width: 600,
        height: 300,
       // autoExpandColumn: 'common', // column with this id will be expanded
        title: 'Edit Plants?',
        frame: true,
        clicksToEdit: 1
    });

    pessoaStore.load();
});