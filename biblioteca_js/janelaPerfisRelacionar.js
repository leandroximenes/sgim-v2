function perfisRelacionar(codPessoa, nomePessoa){
	
	var codPessoaSelecionada;

	//Tipo de Imóvel
	var storeCliente = new Ext.data.Store({
		id: 'storeCliente',
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'POST'
		}),
		baseParams:{acao: "pessoaVwListar"},
		reader: new Ext.data.JsonReader({
			root: 'resultado',
			totalProperty: 'total',
			id: ['codProfissao','nome']
		},[ 
			{name: 'codPessoa', type: 'int'},
			{name: 'nome', type: 'string'}

		]),
		sortInfo:{field: 'nome', direction: "ASC"}
	}) 

	var sm2 = new Ext.grid.CheckboxSelectionModel;

    var ds = new Ext.data.Store({
		id: 'mRemoteDataStore',
		baseParams:{
			acao: 'pessoaGrupoListar',
			codPessoa: codPessoa					
		},
		pruneModifiedRecords:true,
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'post'
		}),
		
		reader: new Ext.data.JsonReader({
			totalProperty: 'total',
			root: 'resultado',
			id: ['codGrupo']
		},[
			{name: 'codGrupo', type: 'int', mapping: 'codGrupo'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'marcado', type: 'boolean', mapping: 'marcado'}
		])
	})
	
	var checkColumn = new Ext.grid.CheckColumn({
      header: "-",
      dataIndex: 'marcado',
      width: 30   
    });

	var cm = new Ext.grid.ColumnModel([checkColumn,
		{id:'codGrupo',header: "Código", readOnly: true, width: 55, dataIndex: 'codGrupo', hidden: true},
		{header: "Grupo", width: 250, sortable: true, dataIndex: 'nome'}
	])
	cm.defaultSortable = true
	
	var comboCliente = new Ext.form.ComboBox({  
		xtype: 'combo',
		fieldLabel: '<b>Selecionar Usuário</b>',
		autoHeight: true,
		width: 190,
		id: 'comboCliente',
		value: nomePessoa,
		forceSelection: true,
		emptyText: 'Selecione o Usuário',	
		triggerAction: 'all',
		anchor: '90%',
		name: 'cliente',
		selectOnFocus: true,
		store: storeCliente,
		displayField: 'nome'
	})
	
 	var grid = new Ext.grid.GridPanel({
        id:'grid',
        cm: cm,
		ds: ds,
        frame:true,
		plugins:checkColumn,
        iconCls:'icon-grid',

		pruneModifiedRecords:true,
		tbar:["<b>Selecionar Usuário:</b>",comboCliente],
        bbar:[{
            text:'Atualizar',	
            tooltip:'Atualizar',
            iconCls:'ic_atualizar',
			handler: verificar
        }],
		height: 250
    })

	comboCliente.addListener('select', SelecionarIdCliente)
	codPessoaSelecionada = codPessoa
		
	function SelecionarIdCliente(obj, record, index) {  
		codPessoaSelecionada = record.get('codPessoa')

		ds.baseParams = {
			acao: 'pessoaGrupoListar',
			codPessoa: codPessoaSelecionada
		}
		ds.load({params:{start:0, limit:30}});
	}  
	
	function verificar(){
		if(codPessoaSelecionada == '' || codPessoaSelecionada == null){
			Ext.Msg.alert('Aviso', 'Por favor selecione um Usuário!')
		}else{
			Ext.each(grid.getStore().getModifiedRecords(), function(record){                   
				var c = record.get('marcado')
				codGrupo = record.get('codGrupo')

				if(c == true){
					Ext.Ajax.request({
						url: 'modulos/usuario/gerenciar_usuario.php',
						params: { 
							acao: 'pessoaGrupoRelacionar',
							codGrupo: codGrupo,
							codPessoa: codPessoaSelecionada,
							op: 1
						},
						callback: function(options, success, response) {
							ds.reload({params:{start:0, limit:10}});
						}
					});
				}else{	
					Ext.Ajax.request({
						url: 'modulos/usuario/gerenciar_usuario.php',
						params: { 
							acao: 'pessoaGrupoRelacionar',
							codGrupo: codGrupo,
							codPessoa: codPessoaSelecionada,
							op: 2
						},
						callback: function(options, success, response) {
							ds.reload({params:{start:0, limit:10}});
						}
					});
				}

			});
		}
	}

	win = new Ext.Window({
		title: 'Relacionar Perfil',
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

Ext.grid.CheckColumn = function(config){
    Ext.apply(this, config);
    if(!this.id){
        this.id = Ext.id();
    }
    this.renderer = this.renderer.createDelegate(this);
};

Ext.grid.CheckColumn.prototype ={
    init : function(grid){
        this.grid = grid;
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },

    onMouseDown : function(e, t){
        if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
            e.stopEvent();
            var index = this.grid.getView().findRowIndex(t);
            var record = this.grid.store.getAt(index);
            record.set(this.dataIndex, !record.data[this.dataIndex]);
        }
    },

    renderer : function(v, p, record){
        p.css += ' x-grid3-check-col-td'; 
        return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    }
};