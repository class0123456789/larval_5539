

@extends('layouts.admin')
@section('css')
<link href="/vendors/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('content')





<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>设备登记管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li class="active">
            @if(haspermission('devicewarehouse.index'))
            <a href="/admin/devicewarehouse"><strong>设备列表</strong></a>
            @endif
        </li>
    </ol>
  </div>
   @if(haspermission('devicewarehouse.create'))
  <div class="col-lg-2">
    <div class="title-action">
      <a href="{{url('admin/devicewarehouse/create')}}" class="btn btn-info">设备登记</a>
    </div>
  </div>
  @endif
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>设备管理</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
            <a class="close-link">
                <i class="fa fa-times"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          @include('flash::message')
          <div class="table-responsive">
	          <table class="table table-striped table-bordered table-hover" id="dataTableBuilder" >
		          <thead>
			          <tr>
			            <th>行号</th>
                          <th>ID</th>
			            <th>设备型号id</th>
                        <th>供应厂商id</th>
			            <th>维保厂商id</th>
                        <th>财务批号</th>
                        <th>设备条型码</th>
                        <th>设备序列号</th>
                        <th>设备IP信息</th>
                        <th>设备MAC信息</th>
                        <th>软件配置</th>
                        <th>用途</th>
                        <th>购买价格</th>
                        <th>购买日期</th>
                        <th>保修期限</th>
                        <th>保修到期日</th>
                        <th>运行状态</th>
                        <th>保管状态</th>
                        <th>使用地址</th>
                        <th>使用人</th>
                        <th>登记人</th>
                          <th>设备管理人</th>
                          <th>所在机构</th>
                        <th>仓库编号</th>
                        <th>备注</th>


			            <th>操作</th>
			          </tr>
		          </thead>
		          <tbody>
		          </tbody>
	          </table>
          </div>
        </div>
      </div>
  	</div>
  </div>
</div>
@endsection
@section('js')

<script src="/vendors/dataTables/datatables.min.js"></script>
<script src="/vendors/layer/layer.js"></script>
<script type="text/javascript">
  $(document).on('click','.destroy_item',function() {
    var _item = $(this);
    layer.confirm('设备删除提示', {
      btn: ['确定', '取消'],
      icon: 5,
    },function(index){
      _item.children('form').submit();
      layer.close(index);
    });
  });
  $(document).on('click','.reset_password',function() {
    var item = $(this);
    layer.confirm('重置', {
      btn: ['确定', '取消'], //按钮
    }, function(){
      var _id = item.attr('data-id');
      $.ajax({
        url:'/admin/devicewarehouse/'+_id+'/reset',
        success:function (response) {
          layer.msg(response.msg);
          layer.close(index);
        }
      });
    });
  });
</script>
<script type="text/javascript">

            $(function () {
                //var c_token = {{csrf_token()}};
                //console.log("{{csrf_token()}}");
                
                var cid = 0;
                var table = $("#dataTableBuilder").DataTable({
                    language: {
                        'url': '/vendors/dataTables/language/zh.json',       
                    },
                    "lengthMenu": [[ 10,15,20,30, -1], [10, 15, 20, 30, "全部"]],

                     "searchDelay": 800,

                    order: [[1, "asc"]],
                    //"lengthChange": true,
                    'processing':true,
                    'serverSide': true,
                     "bJQueryUI": true,
                         // "searching" : true,
                         // "stateSave":true,

     
      //"search": {
      //  "regex": true
      //},

                    //ajax:function(data,callback,settings) {
                    //    $.ajax({
                    //        url: '/admin/permission/index',
                    //        type: 'get',
                    //        headers: {
                    //        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    //        },
                    //        cache : false, //禁用缓存
                    //        data: {jgh : cid},   //自定义传递的参数
                    //       dataType : "json",
                    //       success : function(res){
                    //            console.log(res);
                    //            console.log(res);
                    //            callback(res);  
                    //        }
                    //    });

                        //data: {jgh : cid},   //自定义传递的参数
                        
                    //},
                 
                      ajax:{
 //封装请求参数  
 //var param = {};  
 //param.length =data.length;//页面显示记录条数，在页面显示每页显示多少项的时候  
 //param.start = data.start;//开始的记录序号  
 //param.page = (data.start / data.length)+1;//当前页码  
 //ajax请求数据  
 //$.ajax({  
                      url: "/admin/devicewarehouse",
                      type: 'get',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      },
                      cache : false, //禁用缓存
                      //data: param, //传入组装的参数
                      dataType:"json",  
                      //success: function (result) {  
  //封装返回数据  
  //var returnData = {};  
  //returnData.draw = data.draw;//这里直接自行返回了draw计数器,应该由后台返回  
  //returnData.recordsTotal = result.recordsTotal;//返回数据全部记录  
  //returnData.recordsFiltered = result.recordsFiltered;//后台不实现过滤功能，每次查询均视作全部结果  
  //returnData.data = result.data;//返回的数据列表  
  //console.log(returnData);  
  //调用DataTables提供的callback方法，代表数据已封装完成并传回DataTables进行渲染  
  //此时的数据需确保正确无误，异常判断应在执行此回调前自行处理完毕  
  //callback(returnData);  
  //}  
// });  
                      },
                
                         // "spagingType": "full_numbers",
     // "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgtip',
                    "buttons": [
        {extend: 'copy',title: '设备信息表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        {extend: 'csv',title: '设备信息表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        {extend: 'excel', title: '设备信息表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        //{extend: 'pdf', title: 'permission'},
        {extend: 'print',title:'设备信息表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }}
        ],
                    "columns": [
                        //{"data": null,"searchable": false,"orderable": false}, //行号列
                        {"data": 'row_no',"searchable": false,"orderable": false}, //行号列
                        {"data": "id"},
                        {"data": "device_model_id"},
                        {"data": "device_supplier_id"},
                        {"data": "device_maintenaceprovier_id"},
                        {"data": "device_financialapproval_id"},
                        {"data": "barcode"},
                        {"data": "serial_number"},
                        {"data": "device_ipaddr"},
                        {"data": "device_macaddr"},
                        {"data": "device_software_config"},
                        {"data": "equipment_use_id"},
                        {"data": "device_price"},
                        {"data": "purchased_date"},
                        {"data": "expiry_date"},
                        {"data": "over_date"},
                        {"data": "device_work_state"},
                        {"data": "device_save_state"},
                        {"data": "device_save_addr"},
                        {"data": "device_user"},
                        {"data": "device_registrar"},
                        {"data": "device_trustee"},
                        {"data": "institution_id"},
                        {"data": "house_id"},
                        {"data": "comment"},


                        {"data": "action","type": "html","searchable": false,"orderable" : false}
                    ],


                    columnDefs: [
                        {


                            //'targets':0,"render": function (data, type,row) {
                            //    console.log(meta);
                            //    return meta.row + 1 + meta.settings._iDisplayStart; 
                            //},
                            //-1最后一列
                            'targets': -1, "render": function (data, type, row) {
                            var row_show = {{haspermission('devicewarehouse.show')? 1 : 0}};    
                            var row_edit = {{haspermission('devicewarehouse.edit')? 1 : 0}};
                            var row_delete = {{haspermission('devicewarehouse.destroy') ? 1 :0}};
                            var str = '';
                            //@if(getUser('admin')->id===1)
                            //    row_edit = 0;
                            //    row_delete =0;
                            //@endif    

                            //下级菜单
                            //if (cid == 0) {
                            //    str += '<a style="margin:3px;"  href="/admin/permission/' + row['id'] + '" class="X-Small btn-xs text-success "><i class="fa fa-adn"></i>下级菜单</a>';
                            //}
                            //查看
                            if (row_show) {
                                
                                //str += '<a style="margin:3px;" href="/admin/permission/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                str +='<a href="/admin/devicewarehouse/'+row['id']+'" class="btn btn-xs btn-info tooltips"  data-original-title="查看" data-placement="top"><i class="fa fa-eye"></i></a>';
                            }

                            //编辑
                            if (row_edit) {
                                //if(row['id']!=1){
                                //str += '<a style="margin:3px;" href="/admin/permission/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                str +='<a href="/admin/devicewarehouse/'+row['id']+'/edit" class="btn btn-xs btn-outline btn-warning tooltips" data-original-title="编辑" data-placement="top"><i class="fa fa-edit"></i></a>';
                                //}else{
                                //   str +=''; 
                                //}
                                
                            }

                            //删除
                            if (row_delete) {
                                  //if(row['id']!=1){
                                   
                               
                                //str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                  str +='<a href="javascript:;" onclick="return false" class="btn btn-xs btn-outline btn-danger tooltips destroy_item" data-original-title="删除"  data-placement="top"><i class="fa fa-trash"></i><form action="/admin/devicewarehouse/'+row['id']+'" method="POST" style="display:none"><input type="hidden" name="_token" value="{{csrf_token()}}"><input type="hidden" name="_method" value="delete"></form></a>';
                               //}else{
                               //    str +=''; 
                               // }
                           }

                            return str;

                        },

  
                          
                        }
                    ],

                    
                    
                                        'drawCallback':function() {
                                                table.$('.tooltips').tooltip( {
                                                    placement : 'top',
                                                    html : true
                                                });
                                                //增加选择框
                                                if($('#jgh').length===0){
                                                    let htmlable="";
              

                                                }else{
                                                    //console.log(cid);
                                                        $('#jgh').val(cid); //设置默认值
                                                }
                                                
                                                        //增加行号
                                                        //var api = this.api();
                                                        //var startIndex= api.context[0]._iDisplayStart;//获取到本页开始的条数
                                                        //api.column(0).nodes().each(function(cell, i) {
                                                        //        cell.innerHTML = startIndex + i + 1;
                                                        //}); 
                                        },
       

                });
                //关闭错误提示
                //$.fn.dataTable.ext.errMode = 'throw',
                
                //分页长度改变
                // table.on( 'length.dt',   function () { console.log(1111); });
                 //table.on( 'page.dt length.dt',   function () { console.log(1111); });

               //  ajax事件-当datatable发出ajax请求前
               // table.on('preXhr.dt', function () {
                    // console.log($('#jgh').val());
               //     loadShow();
                //});

                
                //table.on('draw.dt', function () {
                 //    console.log($('#jgh').val());
                 //   loadFadeOut();
                //});
                
                //table.on('order.dt search.dt', function () {
                 //   table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                 //       cell.innerHTML = i + 1;
                  //  });
                //}).draw();
                //table.on('stateSaveParams.dt', function () {
                //     console.log($('#jgh').val());
                //    loadFadeOut();
                //});

                //table.on('order.dt search.dt', function () {
                //    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                //        cell.innerHTML = i + 1;
                //    });
                //}).draw();

                 //let htmlable="<label>角色<select name='jgh' id='jgh'><option value='0'>所有</option><option value='1'>列表</option><option value='2'>超级管理员</option></select></label>";
                //$("#dataTableBuilder_length").prepend(htmlable);
                //table.search('').draw();//发送到后台        

            }); 
            
             //console.log($('meta[name="_token"]').attr('content'));
  </script>
@endsection