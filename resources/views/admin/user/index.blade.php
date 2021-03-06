@extends('layouts.admin')
@section('css')
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>用户管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li class="active">
            <a href="/admin/user"><strong>用户列表</strong></a>
        </li>
    </ol>
  </div>
   @if(haspermission('user.create'))
  <div class="col-lg-2">
    <div class="title-action">
      <a href="{{url('admin/user/create')}}" class="btn btn-info">增加用户</a>
    </div>
  </div>
  @endif
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>用户管理</h5>
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
			            <th>名称</th>
			            <th>email</th>
			            <th>绑定机构</th>

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
<script src="/js/plugins/dataTables/datatables.min.js"></script>
<script src="/js/plugins/layer/layer.js"></script>
<script type="text/javascript">
  $(document).on('click','.destroy_item',function() {
    var _item = $(this);
    layer.confirm('用户删除提示', {
      btn: ['确定', '取消'],
      icon: 5,
    },function(index){
      _item.children('form').submit();
      layer.close(index);
    });
  });
  $(document).on('click','.reset_password',function() {
    var item = $(this);
    layer.confirm('重置密码', {
      btn: ['确定', '取消'], //按钮
    }, function(){
      var _id = item.attr('data-id');
      $.ajax({
        url:'/admin/user/'+_id+'/reset',
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
                var cid = 0;
                var table = $("#dataTableBuilder").DataTable({
                    language: {
                        'url': '/css/plugins/dataTables/language/zh.json',
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

               //     ajax:function(data,callback,settings) {
               //         $.ajax({
               //             url: '/admin/permission/index',
               //             type: 'get',
               //             headers: {
               //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
               //             },
               //             cache : false, //禁用缓存
              //              data : data, //传入已封装的参数
               //             dataType : "json",
                            //success : function(res){
                            //    console.log(res);
                           // }
              //          });

                        //data: {jgh : cid},   //自定义传递的参数
                        
                   // },
                  ajax:{
                      url: "/admin/user",
                      type: 'get',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      },
                      cache : false, //禁用缓存
                      //data: {jgh : cid},   //自定义传递的参数
                      dataType:"json",
                      //success : function(res){
                      //          console.log(res);
                      //      },//success : function(res){
                            //    console.log(res);
                            //}
                  },
                         // "spagingType": "full_numbers",
     // "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgtip',
                    "buttons": [
        {extend: 'copy',title:'管理员表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        {extend: 'csv',title:'管理员表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        {extend: 'excel', title:'管理员表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }},
        //{extend: 'pdf', title: 'permission'},
        {extend: 'print',title:'管理员表',exportOptions:{
            columns:[0,2,3,4],//选择打印列
        }}
        ],
                    "columns": [
                        //{"data": null,"searchable": false,"orderable": false}, //行号列
                        {"data": 'row_no',"searchable": false,"orderable": false}, //行号列
                        {"data": "id"},
                        {"data": "name"},
                        {"data": "email"},
                        
                        {"data": "institution_name"},

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
                            var row_show = {{haspermission('user.show')? 1 : 0}};    
                            var row_edit = {{haspermission('user.edit')? 1 : 0}};
                            var row_delete = {{haspermission('user.destroy') ? 1 :0}};
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
                                str +='<a href="/admin/user/'+row['id']+'" class="btn btn-xs btn-info tooltips"  data-original-title="查看" data-placement="top"><i class="fa fa-eye"></i></a>';
                            }

                            //编辑
                            if (row_edit) {
                                //if(row['id']!=1){
                                //str += '<a style="margin:3px;" href="/admin/permission/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                str +='<a href="/admin/user/'+row['id']+'/edit" class="btn btn-xs btn-outline btn-warning tooltips" data-original-title="编辑" data-placement="top"><i class="fa fa-edit"></i></a>';
                                //}else{
                                //   str +=''; 
                                //}
                                
                            }

                            //删除
                            if (row_delete) {
                                  //if(row['id']!=1){
                                   
                               
                                //str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                  str +='<a href="javascript:;" onclick="return false" class="btn btn-xs btn-outline btn-danger tooltips destroy_item" data-original-title="删除"  data-placement="top"><i class="fa fa-trash"></i><form action="/admin/user/'+row['id']+'" method="POST" style="display:none"><input type="hidden" name="_token" value="{{csrf_token()}}"><input type="hidden" name="_method" value="delete"></form></a>';
                               //}else{
                               //    str +=''; 
                               // }
                           }

                            return str;

                        }
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

                                                @foreach($currinstitutions as $k=>$v)
                                                    htmlable=htmlable+'<option value="{{$k}}">{{$v['name']}}</option>';

                                                @endforeach
                                                //console.log(htmlable);

                                                htmlable="<label>菜单<select name='jgh' id='jgh'><option value='0' check>所有</option>"+htmlable+"</select></label>";
                                                $("#dataTableBuilder_length").prepend(htmlable);
                                                $('#jgh').change(function () {
                                                    //更改自已的参数值
                                                    table.context[0]['ajax']['data']={
                                                        jgh:$('#jgh').val()
                                                    };
                                                    cid = $('#jgh').val();//保存值
                                                    //console.log(table.context[0]['ajax']['data']);
                                                    table.search('').draw();//发送到后台
                                                });
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
  </script>
@endsection