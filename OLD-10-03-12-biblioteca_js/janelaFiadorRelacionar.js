function fiadorRelacionar(codContrato){
	
	var sm2 = new Ext.grid.CheckboxSelectionModel;

    var ds = new Ext.data.Store({
		id: 'ds',
		baseParams:{
			acao: 'fiadorListar',
			codContrato: codContrato					
		},
		pruneModifiedRecords:true,
		proxy: new Ext.data.HttpProxy({
			url: 'modulos/usuario/gerenciar_usuario.php',
			method: 'post'
		}),
		
		reader: new Ext.data.JsonReader({
			totalProperty: 'total',
			root: 'resultado',
			id: ['codPessoa']
		},[
			{name: 'codPessoa', type: 'int', mapping: 'codPessoa'},
			{name: 'nome', type: 'string', mapping: 'nome'},
			{name: 'marcado', type: 'boolean', mapping: 'marcado'}
		])
	})
	
	var checkColumn = new Ext.grid.CheckColumn({
      header: "-",
      dataIndex: 'marcado',
      width: 30   
    });

	var cm = new Ext.grid.ColumnModel([
		checkColumn,
		{id:'codPessoa',header: "Código", readOnly: true, width: 55, dataIndex: 'codPessoa', hidden: true},
		{header: "Fiador", width: 250, sortable: true, dataIndex: 'nome'}
	])
	cm.defaultSortable = true
	
 	var grid = new Ext.grid.GridPanel({
        id:'grid',
        cm: cm,
		ds: ds,
        frame:true,
		plugins:checkColumn,
        iconCls:'icon-grid',

		pruneModifiedRecords:true,
        bbar:[{
            text:'Atualizar',	
            tooltip:'Atualizar',
            iconCls:'ic_atualizar',
			handler: verificar
        }],
		height: 250
    })
		
	function SelecionarIdCliente(obj, record, index) {  
		codContrato = record.get('codContrato')

		ds.baseParams = {
			acao: 'pessoaGrupoListar',
			codContrato: codContrato
		}
		ds.load({params:{start:0, limit:30}});
	}  
	
	function verificar(){
		if(codContrato == '' || codContrato == null){
			Ext.Msg.alert('Aviso', 'Por favor selecione um Usuário!')
		}else{
			Ext.each(grid.getStore().getModifiedRecords(), function(record){                   
				var c = record.get('marcado')
				codFiador = record.get('codPessoa')
	
				if(c == true){
					Ext.Ajax.request({
						url: 'modulos/usuario/gerenciar_usuario.php',
						params: { 
							acao: 'contratoFiadorRelacionar',
							codFiador: codFiador,
							codContrato: codContrato,
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
							acao: 'contratoFiadorRelacionar',
							codFiador: codFiador,
							codContrato: codContrato,
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
		title: 'Relacionar Fiador',
		id: 'avalistaRelacionar',
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