var myData = [
	['Apple','9/1 12:00am','','',''],
	['Ext','9/12 12:00am','','',''],
	['Google','10/1 12:00am','','',''],
	['Microsoft','7/4 12:00am','','',''],
	['Yahoo!','5/22 12:00am','','','']
];

var myReader = new Ext.data.ArrayReader({}, [
	{name: 'company'},
	{name: 'meetingtime', type: 'date', dateFormat: 'n/j h:ia'},
	{name: 'adicionar'},
	{name: 'excluir'},
	{name: 'editar'},
]);

function usuarioVerificar(value){
	return '<center><img src="img/ic_gerenciar_usuario.png" /></center>';
}
	
	

var gridListaUsuarios = new Ext.grid.GridPanel({

	store: new Ext.data.Store({
		data: myData,
		reader: myReader
	}),
	collapsible: false,

	columns: [
		{header: 'Status', sortable: true, width: 35, dataIndex: '', renderer: usuarioVerificar},
		{header: 'Nome', sortable: true, dataIndex: 'company'},
		{header: 'CPF/Passaporte', sortable: true, dataIndex: 'company'},
		{header: 'Perfil', sortable: true, dataIndex: 'company'},
		{header: 'Instituição', sortable: true, dataIndex: 'company'},
		{header: 'Telefone', sortable: true, dataIndex: 'company'}
	],

	viewConfig: {
		forceFit: true
	},
	id: 'gridListaUsuarios',
	autoHeight: true,
	frame: false,
	width: 750,	
	bbar:[
		botaoAdicionar = new Ext.Button({
			id: 'botaoAdicionarUsuario',
			text: 'Adicionar',
			tooltip: 'Adicionar',
			iconCls: 'botaoAdicionar'					
		})
	]
});


//Aba Central onde todas as abas secundárias irão ser visualizadas
var abaUsuarios = {
	xtype: 'panel',
	region: 'center',
	title: 'Lista de Usuários',
	id: 'abaUsuarios',
	enableTabScroll:true,
	bodyStyle: 'padding: 20px 20px 10px 20px; text-align: left;',
	items:[{
		xtype: 'compositefield',
		border: false,
		items:[{
			layout:'column',
			width: '100%',
			border: false,
			items: [{
				columnWidth:.27,
				layout:'form',
				border: false,
				labelWidth: 50,
				
				items: [{
					xtype: 'combo',
					name : 'cbHabitat',
					fieldLabel: '<b>Nome</b>'
				},{
					xtype: 'checkbox',
					name : 'cbxUsuariosAtivos',
					boxLabel: 'Exibir somente usuários ativos'
				}]
			},{
				columnWidth:.20,
				layout:'form',
				border: false,
				labelWidth: 100,
				items: [{
					xtype: 'combo',
					name : 'cbLocalConsulta',
					fieldLabel: '<b>CPF/Passaporte</b>'
				},{
					xtype: 'checkbox',
					name : 'cbxUsuariosAtivos',
					boxLabel: 'Exibir usuários excluídos'
				}]
			},{
				columnWidth:.04,
				layout:'form',
				border: false,
				
				items: [{
					xtype: 'button',
					name : 'cbBuscarEspecie',
					iconCls: 'botaoBuscar'
				}]
			}]
		}]
	},{
		xtype:'panel',
		border: false,
		height: 20
	},
		gridListaUsuarios
	]
}