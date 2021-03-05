/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.onReady(function(){
    var items = [];
    
    Ext.QuickTips.init();
    
	var barraMenus = new Ext.Toolbar({
		resizable: false,
		items:['->',{
			text: 'Logoff',
			iconCls: 'ic_logout',
			handler: function(){
				window.location = "login.php"
			}
		}]
	})
		
	var panelHeader = new Ext.Panel({
		region: 'north',
		border: false,
		html: "conteudo html"

	})
		
	var panelMenu = {
		region: 'center',
		border: false,		
		tbar: barraMenus
	}	

	var panelNorth = {
		height: 74,
		border: false,
		region: 'north',
		items:[panelHeader,panelMenu]
	}

	var abaNoc = {
		xtype: 'panel',
		id: 'abaTeste',
		title: 'Teste',
		autoScroll: true
	}

	var tree = new Ext.tree.TreePanel({
		id: 'tree',
		animate:true,     
        containerScroll: true,
		root: new Ext.tree.AsyncTreeNode({
			id:'task'
		}),
		loader: new Ext.tree.TreeLoader({ 
			//dataUrl:''
		}),
		rootVisible:false,
		border: false
    })

	
	tree.on('dblclick',function(node,e){
		tabAdicionais = Ext.getCmp('panelCenter').getComponent(node.id+'0');
			if(node.text == "Servi&ccedil;os"){
				if(!tabAdicionais){
					adicionarAbaServicos(node.text, node.id, node.attributes.autoLoad,  node.attributes.titulo, node.attributes.cliente, node.attributes.base,0);
				}	
			}
		Ext.getCmp('panelCenter').setActiveTab(node.id+'0');
	})

	var cliente = {
		xtype: 'panel',
		region: 'west',
		id: 'menu',
		width: 205,	
		title:'Menu',
		autoScroll: true,
		iconCls: 'ic_clientes',
		collapsible: true,
		split:true,
		items: [tree]
	}
		
		
	//Aba Central onde todas as abas secundárias irão ser visualizadas
	var panelCenter = new Ext.TabPanel({
		region: 'center',
		id: 'panelCenter',
		enableTabScroll:true,
		activeTab:1,
		items:[abaNoc]
	})
	
	//Viewport Principal, todas as abas estão anexadas a ela
	var panelGeral = new Ext.Viewport({
		layout: 'border',
		items: [panelNorth,cliente,panelCenter]
	})	
    
    
});