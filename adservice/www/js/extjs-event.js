var clientHeight = window.innerHeight-90,clientWidth = window.innerWidth-90;
var pageSize = 10,maxPageSize=10000,systemTitle = '系统提示',noValidInfo='信息填写不完整';
var timeSolt = [{"value":"1","name":"1"},{"value":"2","name":"2"},{"value":"3","name":"3"},{"value":"4","name":"4"},{"value":"5","name":"5"},{"value":"6","name":"6"},{"value":"7","name":"7"},{"value":"8","name":"8"},{"value":"9","name":"9"},{"value":"10","name":"10"},{"value":"11","name":"11"},{"value":"12","name":"12"},{"value":"13","name":"13"},{"value":"14","name":"14"},{"value":"15","name":"15"},{"value":"16","name":"16"},{"value":"17","name":"17"},{"value":"18","name":"18"},{"value":"19","name":"19"},{"value":"20","name":"20"},{"value":"21","name":"21"},{"value":"22","name":"22"},{"value":"23","name":"23"},{"value":"24","name":"24"}];

//时间段选择
var timeSoltStore = Ext.create('Ext.data.Store',{
    fields:['name','value'],
    data:timeSolt

});

//广告主
Ext.define("advertiserModel",{
    extend:"Ext.data.Model",
    fields:['entry_id','name_zh','name_en','name_full','status','create_time','update_time']
});

var advertiserStore = Ext.create('Ext.data.Store',{
            model:"advertiserModel",
            autoLoad:true,
            pageSize:maxPageSize,
            proxy:{
                    type : "ajax",
                    url : "/advertise/advertisers/allList",
                    reader:{
                        type:"json",
                        root:"data"
                    }
            }
});


//优先级
Ext.define("campaignPriorityModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var campaignPriorityStore = Ext.create('Ext.data.Store',{
    model:"campaignPriorityModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getCampaignPriority",
        reader:{
            type:"json",
            root:"data"
        }
    }
});

//播放规则
Ext.define("creativeShowRuleModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var creativeShowRuleStore = Ext.create('Ext.data.Store',{
    model:"creativeShowRuleModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getcreativeShowRule",
        reader:{
            type:"json",
            root:"data"
        }
    }
});


//投放方式
Ext.define("campaignDisplayWayModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var campaignDisplayWayStore = Ext.create('Ext.data.Store',{
    model:"campaignDisplayWayModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getcampaignDisplayWay",
        reader:{
            type:"json",
            root:"data"
        }
    }
});


//投放时间段
Ext.define("timeTargetModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var timeTargetStore = Ext.create('Ext.data.Store',{
    model:"timeTargetModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getTimeTarget",
        reader:{
            type:"json",
            root:"data"
        }
    }
});

//设备品质
Ext.define("qualityTargetModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var qualityTargetStore = Ext.create('Ext.data.Store',{
    model:"qualityTargetModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getQualityTarget",
        reader:{
            type:"json",
            root:"data"
        }
    }
});

//设备品质
Ext.define("countryTargetModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var countryTargetStore = Ext.create('Ext.data.Store',{
    model:"countryTargetModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getCountryTarget",
        reader:{
            type:"json",
            root:"data"
        }
    }
});



//订单模型
Ext.define("orderModel",{
    extend:"Ext.data.Model",
    fields:['id','name']
});

var orderStore = Ext.create('Ext.data.Store',{
    model:"orderModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/advertise/orders/getList",
        reader:{
            type:"json",
            root:"data"
        }
    }
});

//省份
Ext.define("provinceModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var provinceStore = Ext.create('Ext.data.Store',{
    model:"provinceModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getProvince",
        reader:{
            type:"json",
            root:"data"
        }
    }
});


//城市列表
Ext.define("cityModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var cityStore = Ext.create('Ext.data.Store',{
    model:"cityModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getCity",
        reader:{
            type:"json",
            root:"data"
        }
    }
});


//媒体列表
Ext.define("publicationModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var publicationStore = Ext.create('Ext.data.Store',{
    model:"publicationModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getMedia",
        reader:{
            type:"json",
            root:"data"
        }
    }
});



//广告位列表
Ext.define("zonesModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var zonesStore = Ext.create('Ext.data.Store',{
    model:"zonesModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getZones",
        reader:{
            type:"json",
            root:"data"
        }
    }
});


//设备列表
Ext.define("deviceModel",{
    extend:"Ext.data.Model",
    fields:['name','value']
});

var deviceStore = Ext.create('Ext.data.Store',{
    model:"deviceModel",
    autoLoad:true,
    pageSize:maxPageSize,
    proxy:{
        type : "ajax",
        url : "/menu/getDevices",
        reader:{
            type:"json",
            root:"data"
        }
    }
});




/**
 * 错误提示
 * **/
function error(msg){
    Ext.Msg.show({
        title:systemTitle,
        msg:msg,
        icon:"x-message-box-error"
    });
}


//创建投放
function add_campaign() {
    var form = new Ext.form.Panel({
        defaultType:"textfield",
        method:"POST",
        url: '/advertise/campaign/add',
        border:false,
        fieldDefaults: {
            labelWidth: 70,
            labelAlign: "left",
            margin:10
        },
        items: [
            {
                xtype: "container",
                layout: "hbox",
                items: [
                    {
                        xtype:"textfield",
                        name: "campaign_name",
                        fieldLabel: "投放名称",
                        flex:1,
                        allowBlank:false
                        //vtype:'ip'
                    },{
                        xtype: "combobox",
                        name: "campaign_priority",
                        fieldLabel: "优先级",
                        flex:1,
                        displayField: "name",
                        valueField: "value",
                        store:campaignPriorityStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "belong_to_advertiser",
                        fieldLabel: "广告主",
                        flex:1,
                        displayField: "name_zh",
                        valueField: "entry_id",
                        store:advertiserStore,
                        allowBlank: false,
                        forceSelection:true,
                        queryMode:'local',
                        typeAhead:true,
                        listeners:{
                            change:function(f,n,o){
                                //切换对应订单的列表
                                var order = Ext.getCmp('campaign_order_list');
                                order.clearValue();
                                order.store.load(
                                    {params:{advertiser_id:n}}
                                );
                            }
                        }
                    },{
                        xtype: "combobox",
                        id:"campaign_order_list",
                        name: "order_id",
                        fieldLabel: "订单名称",
                        flex:1,
                        displayField: "name",
                        valueField: "id",
                        store:orderStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "creative_show_rule",
                        fieldLabel: "播放规则",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:creativeShowRuleStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true
                    },{
                        xtype: "combobox",
                        name: "campaign_display_way",
                        fieldLabel: "投放方式",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:campaignDisplayWayStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "quality_target",
                        fieldLabel: "设备品质",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:qualityTargetStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                        listeners:{
                            change: function (f, n, o) {
                                var device = Ext.getCmp('campaign_device_id');
                                if(n==2)
                                {
                                    device.show();
                                }else{
                                    device.hide();
                                }
                            }
                        },
                    }, {
                        xtype: "combobox",
                        name: "country_target",
                        fieldLabel: "投放区域",
                        displayField: "name",
                        valueField: "value",
                        flex: 1,
                        store: countryTargetStore,
                        allowBlank: false,
                        forceSelection: true,
                        typeAhead: true,
                        listeners:{
                            change: function (f, n, o) {
                                var privonce = Ext.getCmp('campaign_province');
                                if(n==2)
                                {
                                    privonce.show();
                                }else{
                                    var city = Ext.getCmp('campaign_city');
                                    privonce.hide();
                                    city.hide();
                                }
                            }
                        },
                    }
             ]},{
                xtype: "container",
                layout: "hbox",
                items:[{
                        xtype: "combobox",
                        name: "device_name[]",
                        fieldLabel: "设备",
                        id: "campaign_device_id",
                        displayField: "name",
                        valueField: "name",
                        flex: 1,
                        multiSelect: true,
                        store: deviceStore,
                        hidden: true,
                        allowBlank: false,
                        forceSelection: true,
                        queryMode:'local',
                        typeAhead: true,

                    }]
                },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "province[]",
                        fieldLabel: "省份",
                        id:"campaign_province",
                        displayField: "name",
                        valueField: "name",
                        flex:1,
                        multiSelect:true,
                        store:provinceStore,
                        hidden:true,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                        queryMode:'local',
                        listeners:{
                            change:function(f,n,o){
                                var city = Ext.getCmp('campaign_city');
                                city.show();
                                //city.clearValue();
                                city.store.load(
                                    {params:{'cn[]':n}}//切换对应城市
                                );
                            }
                        },
                       /* listConfig : {
                            itemTpl : Ext.create('Ext.XTemplate','<input type=checkbox>{[values.name]}'),
                        }*/
                    }, {
                        xtype: "combobox",
                        name: "city[]",
                        id:"campaign_city",
                        fieldLabel: "城市",
                        displayField: "name",
                        valueField: "name",
                        flex: 1,
                        multiSelect:true,
                        hidden:true,
                        store: cityStore,
                        forceSelection: true,
                        queryMode:'local',
                        typeAhead: true,
                        /*listConfig : {
                            itemTpl : Ext.create('Ext.XTemplate','<input type=checkbox>{[values.name]}'),
                        }*/
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "media_id[]",
                        fieldLabel: "媒体",
                        flex:1,
                        multiSelect:true,
                        displayField: "name",
                        valueField: "value",
                        store:publicationStore,
                        forceSelection: true,
                        typeAhead:true,
                        queryMode:'local',
                        listeners:{
                            change:function(f,n,o){
                                var zones = Ext.getCmp('compaign_zones');
                                zones.show();
                                zones.clearValue();
                                zones.store.load(
                                    {params:{'media_id[]':n}}
                                );
                            }
                        }
                    },{
                        xtype: "combobox",
                        name: "zones_name[]",
                        id:"compaign_zones",
                        fieldLabel: "广告位",
                        flex:1,
                        multiSelect:true,
                        displayField: "name",
                        valueField: "name",
                        allowBlank: false,
                        forceSelection: true,
                        queryMode:'local',
                        typeAhead:true,
                        store:zonesStore
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "datefield",
                        name: "campaign_start",
                        fieldLabel: "开始时间",
                        flex:1,
                        allowBlank: false,
                        format:"Y-m-d",
                        submitFormat:"Y-m-d"
                    },{
                        xtype: "datefield",
                        name: "campaign_end",
                        fieldLabel: "结束时间",
                        flex:1,
                        allowBlank: false,
                        format:"Y-m-d",
                        submitFormat:"Y-m-d"
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "time_target",
                        fieldLabel: "投放时间",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        allowBlank: false,
                        store:timeTargetStore,
                        listeners:{
                            change: function (f, n, o) {
                                var time_solt = Ext.getCmp('time_slot');
                                if(n==2)
                                {
                                    time_solt.show();
                                }else{
                                    time_solt.hide();
                                }
                            }
                        },
                    },{
                        xtype: "combobox",
                        name: "time_slot[]",
                        fieldLabel: "时间段选择",
                        id:"time_slot",
                        displayField: "name",
                        valueField: "name",
                        multiSelect:true,
                        hidden:true,
                        flex:1,
                        allowBlank: false,
                        store:timeSoltStore,
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "numberfield",
                        name: "total_amount",
                        fieldLabel: "曝光限制",
                        flex:1,
                        allowBlank: false,
                        minValue:0,
                        maxValue:10000000000
                    }
                ]},{
                    xtype:"textarea",
                    fieldLabel: "简介",
                    name:"campaign_desc",
                    anchor:"100%",
                    height:150,
                    allowBlank: false
            }]
    });
    var win = Ext.create('Ext.window.Window',{
        autoShow: true,
        title: '添加投放',
        width: 600,
        height: 650,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        x:clientWidth/3,
    	y:100,
        modal:true,
        plain:true,
        items: form,
        buttons:[
            {
                text:"确定",
                handler:function(){
                    if(form.isValid())
                    {
                        form.submit({
                            success: function(form, action) {
                                Ext.Msg.alert(systemTitle, action.result.msg,function(){
                                    win.close();
                                    location.reload();

                                });
                            },
                            failure: function(form, action) {
                                error(action.result.msg);
                            }
                        });
                    }else {
                        error(noValidInfo);
                    }
                }
            },{
                text:"取消",
                handler:function(){
                    win.close();
                }
            }]
    });
}


//编辑投放
function edit_campaign(id) {
    var form = new Ext.form.Panel({
        defaultType:"textfield",
        method:"POST",
        url: '/advertise/campaign/add',
        border:false,
        fieldDefaults: {
            labelWidth: 70,
            labelAlign: "left",
            margin:10
        },
        items: [
			{
			    xtype:"hiddenfield",
			    value:id,
			    name:"campaign_id",
			},
            {
                xtype: "container",
                layout: "hbox",
                items: [
                    {
                        xtype:"textfield",
                        name: "campaign_name",
                        fieldLabel: "投放名称",
                        flex:1,
                        allowBlank:false
                        //vtype:'ip'
                    },{
                        xtype: "combobox",
                        name: "campaign_priority",
                        fieldLabel: "优先级",
                        flex:1,
                        displayField: "name",
                        valueField: "value",
                        store:campaignPriorityStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "belong_to_advertiser",
                        fieldLabel: "广告主",
                        flex:1,
                        displayField: "name_zh",
                        valueField: "entry_id",
                        store:advertiserStore,
                        allowBlank: false,
                        forceSelection:true,
                        queryMode:'local',
                        typeAhead:true,
                        listeners:{
                            change:function(f,n,o){
                                //切换对应订单的列表
                                var order = Ext.getCmp('campaign_order_list');
                                order.clearValue();
                                order.store.load(
                                    {params:{advertiser_id:n}}
                                );
                            }
                        }
                    },{
                        xtype: "combobox",
                        id:"campaign_order_list",
                        name: "order_id",
                        fieldLabel: "订单名称",
                        flex:1,
                        displayField: "name",
                        valueField: "id",
                        store:orderStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "creative_show_rule",
                        fieldLabel: "播放规则",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:creativeShowRuleStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true
                    },{
                        xtype: "combobox",
                        name: "campaign_display_way",
                        fieldLabel: "投放方式",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:campaignDisplayWayStore,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "quality_target",
                        fieldLabel: "设备品质",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        store:qualityTargetStore,
                        allowBlank: false,
                        typeAhead:true,
                        listeners:{
                            change: function (f, n, o) {
                                var device = Ext.getCmp('campaign_device_id');
                                if(n==2)
                                {
                                    device.show();
                                }else{
                                    device.hide();
                                }
                            }
                        },
                    }, {
                        xtype: "combobox",
                        name: "country_target",
                        fieldLabel: "投放区域",
                        displayField: "name",
                        valueField: "value",
                        flex: 1,
                        store: countryTargetStore,
                        allowBlank: false,
                        forceSelection: true,
                        typeAhead: true,
                        listeners:{
                            change: function (f, n, o) {
                                var privonce = Ext.getCmp('campaign_province');
                                if(n==2)
                                {
                                    privonce.show();
                                }else{
                                    var city = Ext.getCmp('campaign_city');
                                    privonce.hide();
                                    city.hide();
                                }
                            }
                        },
                    }
             ]},{
                xtype: "container",
                layout: "hbox",
                items:[{
                        xtype: "combobox",
                        name: "device_name[]",
                        fieldLabel: "设备",
                        id: "campaign_device_id",
                        displayField: "name",
                        valueField: "name",
                        flex: 1,
                        multiSelect: true,
                        store: deviceStore,
                        forceSelection:true,
                        hidden: true,
                        allowBlank: false,
                        queryMode:'local',
                        typeAhead: true,

                    }]
                },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "province[]",
                        fieldLabel: "省份",
                        id:"campaign_province",
                        displayField: "name",
                        valueField: "name",
                        flex:1,
                        multiSelect:true,
                        store:provinceStore,
                        hidden:true,
                        allowBlank: false,
                        forceSelection:true,
                        typeAhead:true,
                        queryMode:'local',
                        listeners:{
                            change:function(f,n,o){
                                var city = Ext.getCmp('campaign_city');
                                city.show();
                                //city.clearValue();
                                city.store.load(
                                    {params:{'cn[]':n}}//切换对应城市
                                );
                            }
                        },
                       /* listConfig : {
                            itemTpl : Ext.create('Ext.XTemplate','<input type=checkbox>{[values.name]}'),
                        }*/
                    }, {
                        xtype: "combobox",
                        name: "city[]",
                        id:"campaign_city",
                        fieldLabel: "城市",
                        displayField: "name",
                        valueField: "name",
                        flex: 1,
                        multiSelect:true,
                        hidden:true,
                        store: cityStore,
                        queryMode:'local',
                        typeAhead: true,
                        /*listConfig : {
                            itemTpl : Ext.create('Ext.XTemplate','<input type=checkbox>{[values.name]}'),
                        }*/
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "media_id[]",
                        fieldLabel: "媒体",
                        flex:1,
                        multiSelect:true,
                        displayField: "name",
                        valueField: "value",
                        store:publicationStore,
                        forceSelection: true,
                        typeAhead:true,
                        queryMode:'local',
                        listeners:{
                            change:function(f,n,o){
                                var zones = Ext.getCmp('compaign_zones');
                                zones.show();
                                zones.clearValue();
                                zones.store.load(
                                    {params:{'media_id[]':n}}//切换对应城市
                                );
                            }
                        }
                    },{
                        xtype: "combobox",
                        name: "zones_name[]",
                        id:"compaign_zones",
                        fieldLabel: "广告位",
                        flex:1,
                        multiSelect:true,
                        displayField: "name",
                        valueField: "name",
                        allowBlank: false,
                        queryMode:'local',
                        typeAhead:true,
                        store:zonesStore
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "datefield",
                        name: "campaign_start",
                        fieldLabel: "开始时间",
                        flex:1,
                        allowBlank: false,
                        format:"Y-m-d",
                        submitFormat:"Y-m-d"
                    },{
                        xtype: "datefield",
                        name: "campaign_end",
                        fieldLabel: "结束时间",
                        flex:1,
                        allowBlank: false,
                        format:"Y-m-d",
                        submitFormat:"Y-m-d"
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "combobox",
                        name: "time_target",
                        fieldLabel: "投放时间",
                        displayField: "name",
                        valueField: "value",
                        flex:1,
                        allowBlank: false,
                        store:timeTargetStore,
                        listeners:{
                            change: function (f, n, o) {
                                var time_solt = Ext.getCmp('time_slot');
                                if(n==2)
                                {
                                    time_solt.show();
                                }else{
                                    time_solt.hide();
                                }
                            }
                        },
                    },{
                        xtype: "combobox",
                        name: "time_slot[]",
                        fieldLabel: "时间段选择",
                        id:"time_slot",
                        displayField: "name",
                        valueField: "name",
                        forceSelection:true,
                        multiSelect:true,
                        hidden:true,
                        flex:1,
                        allowBlank: false,
                        store:timeSoltStore,
                    }
                ]},{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "numberfield",
                        name: "total_amount",
                        fieldLabel: "曝光限制",
                        flex:1,
                        allowBlank: false,
                        minValue:0,
                        maxValue:10000000000
                    }
                ]},{
                    xtype:"textarea",
                    fieldLabel: "简介",
                    name:"campaign_desc",
                    anchor:"100%",
                    height:150,
                    allowBlank: false
            }]
    });
    
    
  //加载数据
    form.getForm().load({
        url:"/advertise/campaign/getInfo?campaign_id="+id
    });
    
    
    var win = Ext.create('Ext.window.Window',{
        autoShow: true,
        title: '编辑投放',
        width: 600,
        height: 650,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        x:clientWidth/5,
    	y:100,
        plain:true,
        modal:true,
        items: form,
        buttons:[
            {
                text:"确定",
                handler:function(){
                    if(form.isValid())
                    {
                        form.submit({
                            success: function(form, action) {
                                Ext.Msg.alert(systemTitle, action.result.msg,function(){
                                    win.close();
                                    location.reload();

                                });
                            },
                            failure: function(form, action) {
                                error(action.result.msg);
                            }
                        });
                    }else {
                        error(noValidInfo);
                    }
                }
            },{
                text:"取消",
                handler:function(){
                    win.close();
                }
            }]
    });
}























//新建订单
function add_orders() {
    var form = new Ext.form.Panel({
        defaultType:"textfield",
        method:"POST",
        url: '/advertise/orders/add',
        border:false,
        fieldDefaults: {
            labelWidth: 70,
            labelAlign: "left",
            margin:10
        },
        items: [
            {
                xtype: "container",
                layout: "hbox",
                items: [
                    {
                        xtype:"textfield",
                        name: "name",
                        fieldLabel: "订单名称",
                        flex:1,
                        allowBlank:false
                        //vtype:'ip'
                    },{
                        xtype: "combobox",
                        name: "advertiser_id",
                        fieldLabel: "广告主",
                        flex:1,
                        displayField: "name_zh",
                        valueField: "entry_id",
                        store:advertiserStore,
                        allowBlank: false,
                        forceSelection:true,
                        queryMode:'local',
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "datefield",
                        name: "start_time",
                        fieldLabel: "开始日期",
                        flex:1,
                        allowBlank: false,
                        format: "Y-m-d",
                        submitFormat:"Y-m-d",
                    },{
                        xtype: "datefield",
                        name: "end_time",
                        fieldLabel: "结束日期",
                        flex:1,
                        allowBlank: false,
                        format: "Y-m-d",
                        submitFormat:"Y-m-d",
                    }
                ]
            },{
                xtype:"textarea",
                fieldLabel: "备注",
                name:"note",
                anchor:"100%",
                height:100,
                allowBlank: false
            },]
    });
    var win = Ext.create('Ext.window.Window',{
        autoShow: true,
        title: '创建订单',
        width: 600,
        height: 300,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        x:clientWidth/3,
    	y:50,
        plain:true,
        items: form,
        buttons:[
            {
                text:"确定",
                handler:function(){
                    if(form.isValid())
                    {
                        form.submit({
                            success: function(form, action) {
                                Ext.Msg.alert(systemTitle, action.result.msg,function(){
                                    win.close();
                                    location.reload();
                                });
                            },
                            failure: function(form, action) {
                                error(action.result.msg);
                            }
                        });
                    }else {
                        error(noValidInfo);
                    }
                }
            },{
                text:"取消",
                handler:function(){
                    win.close();
                }
            }]
    });

}






//编辑订单
function edit_orders(id) {
    var form = new Ext.form.Panel({
        defaultType:"textfield",
        method:"POST",
        url: '/advertise/orders/edit',
        border:false,
        fieldDefaults: {
            labelWidth: 70,
            labelAlign: "left",
            margin:10
        },
        items: [
            {
                xtype:"hiddenfield",
                value:id,
                name:"id",
            },
            {
                xtype: "container",
                layout: "hbox",
                items: [
                    {
                        xtype:"textfield",
                        name: "name",
                        fieldLabel: "订单名称",
                        flex:1,
                        allowBlank:false
                        //vtype:'ip'
                    },{
                        xtype: "combobox",
                        name: "advertiser_id",
                        fieldLabel: "广告主",
                        flex:1,
                        displayField: "name_zh",
                        valueField: "entry_id",
                        store:advertiserStore,
                        allowBlank: false,
                        forceSelection:true,
                        queryMode:'local',
                        typeAhead:true,
                    }
                ]
            },{
                xtype: "container",
                layout: "hbox",
                items:[
                    {
                        xtype: "datefield",
                        name: "start_time",
                        fieldLabel: "开始日期",
                        flex:1,
                        allowBlank: false,
                        format: "Y-m-d",
                        submitFormat:"Y-m-d",
                    },{
                        xtype: "datefield",
                        name: "end_time",
                        fieldLabel: "结束日期",
                        flex:1,
                        allowBlank: false,
                        format: "Y-m-d",
                        submitFormat:"Y-m-d",
                    }
                ]
            },{
                xtype:"textarea",
                fieldLabel: "备注",
                name:"note",
                anchor:"100%",
                height:100,
                allowBlank: false
            },]
    });

    //加载数据
    form.getForm().load({
        url:"/advertise/orders/getInfo?order_id="+id
    });

    var win = Ext.create('Ext.window.Window',{
        autoShow: true,
        title: '编辑订单',
        width: 600,
        height: 300,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        x:clientWidth/5,
    	y:150,
        plain:true,
        items: form,
        buttons:[
            {
                text:"确定",
                handler:function(){
                    if(form.isValid())
                    {
                        form.submit({
                            success: function(form, action) {
                                Ext.Msg.alert(systemTitle, action.result.msg,function(){
                                    win.close();
                                    location.reload();
                                });
                            },
                            failure: function(form, action) {
                                error(action.result.msg);
                            }
                        });
                    }else {
                        error(noValidInfo);
                    }
                }
            },{
                text:"取消",
                handler:function(){
                    win.close();
                }
            }]
    });

}

