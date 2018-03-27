@extends('layouts.admin')
@section('css')
<link href="/vendors/dataTables/datatables.min.css" rel="stylesheet">
<link href="/adminLTE/css/load/load.css" rel="stylesheet">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>权限管理</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">主控制台</a>
        </li>
        <li class="active">
           <strong>权限列表</strong>
        </li>
    </ol>
  </div>
  <div class="col-lg-2">
    <div class="title-action">
       @if(Gate::forUser(auth('admin')->user())->check('admin.permission.create'))
      <a href="{{url('admin/permission/create')}}" class="btn btn-info"><i class="fa fa-plus"></i>增加权限</a>
      @endif
    </div>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>权限管理</h5>
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

          
         <table  class="table table-striped table-bordered table-hover" id="dataTableBuilder">
             <thead>
                 <tr>
                     <th >序号</th>
                     <th >权限</th>
                     <th >权限名称</th>
                     <th >描述</th>
                     <th >增加时间</th>
                     <th >修改时间</th>
                     <th >操作</th>
                 </tr>
             </thead>
         </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="/vendors/dataTables/datatables.min.js"></script>
  <script src="/vendors/layer/layer.js"></script>
  <script src="/adminLTE/js/common.js"></script>
  
  <script type="text/javascript">
    $(document).on('click','.destroy_item',function() {
      var _item = $(this);
      var title = "权限删除提示";
      layer.confirm(title, {
        btn: ['确定', '取消'],
        icon: 5
      },function(index){
        _item.children('form').submit();
        layer.close(index);
      });
    });
  </script>
  <script type="text/javascript">

            $(function () {
                var cid = 0;
                var table = $("#dataTableBuilder").DataTable({
                    language: {
                        'url': '/vendors/dataTables/language/zh.json'
                        
                    },
                    //order: [[5, "asc"]],
                    //"lengthChange": true,
                    //'processing':true,
                    serverSide: true,
                         // "searching" : true,
                         // "stateSave":true,

      "searchDelay": 800,
      //"search": {
      //  "regex": true
      //},

                    ajax: {
                        url: '/admin/permission/index',
                        type: 'get',
                        data: {jgh : cid},   //自定义传递的参数
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    },
                          //"pagingType": "full_numbers",
     // "orderCellsTop": true,
    //  "dom" : '<"html5buttons"B>lTfgtip',
    //                "buttons": [
    //    {extend: 'copy',title: 'permission'},
    //    {extend: 'csv',title: 'permission'},
    //    {extend: 'excel', title: 'permission'},
        //{extend: 'pdf', title: 'permission'},
    //    {extend: 'print',
     //   }
     // ],
                    "columns": [
                        {"data": "id"},
                        {"data": "name"},
                        {"data": "label"},
                        {"data": "description"},
                        {"data": "created_at"},
                        {"data": "updated_at"},
                        {"data": "action"}
                    ],


                    columnDefs: [
                        {
                            'targets': -1, "render": function (data, type, row) {
                            var row_edit = {{Gate::forUser(auth('admin')->user())->check('admin.permission.edit') ? 1 : 0}};
                            var row_delete = {{Gate::forUser(auth('admin')->user())->check('admin.permission.destroy') ? 1 :0}};
                            var str = '';

                            //下级菜单
                            //if (cid == 0) {
                            //    str += '<a style="margin:3px;"  href="/admin/permission/' + row['id'] + '" class="X-Small btn-xs text-success "><i class="fa fa-adn"></i>下级菜单</a>';
                            //}

                            //编辑
                            if (row_edit) {
                                //str += '<a style="margin:3px;" href="/admin/permission/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                str +='<a href="/admin/permission/'+row['id']+'/edit" class="btn btn-xs btn-outline btn-warning tooltips" data-original-title="编辑" data-placement="top"><i class="fa fa-edit"></i></a>';
                            }

                            //删除
                            if (row_delete) {
                                //str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                str +='<a href="javascript:;" onclick="return false" class="btn btn-xs btn-outline btn-danger tooltips destroy_item" data-original-title="删除"  data-placement="top"><i class="fa fa-trash"></i><form action="/admin/permission/'+row['id']+'" method="POST" style="display:none"><input type="hidden" name="_token" value="{{csrf_token()}}"><input type="hidden" name="_method" value="delete"></form></a>';
                           }

                            return str;

                        }
                        }
                    ],

                    
                    
                                        'drawCallback':
					function() {
				        table.$('.tooltips').tooltip( {
				          placement : 'top',
				          html : true
				        });
                                        if($('#jgh').length===0){
                                        let htmlable="<label>菜单<select name='jgh' id='jgh'><option value='0' check>所有</option><option value='1'>一级菜单</option><option value='2'>二级菜单</option><option value='-1'>非菜单</option></select></label>";
                                        $("#dataTableBuilder_length").prepend(htmlable);
                                        $('#jgh').change(function () {
                                            // console.log($('#jgh').val());
                                             //let jgh=$('#jgh').val();
                                             //更改自已的参数值
                                             table.context[0]['ajax']['data']={
                                                 jgh:$('#jgh').val()
                                             };
                                             cid = $('#jgh').val();//保存值
                                             console.log(table.context[0]['ajax']['data']);
                                             table.search('').draw();//发送到后台
                                             
                                             
                                        });
                                    }else{
                                            //console.log(cid);
                                             $('#jgh').val(cid); //设置默认值
                                     }
                                                
                                        
                                        console.log(table.context[0]['ajax']['data']);
                                    },
                                    //if($('#jgh').length>0){
                                       //console.log($('#jgh'));
                                    //}
                  //  $('#jgh').change(function () {
                                   // LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh'] = $('#jgh').val();
                    //console.log(window.LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh']);
                     //   });
			       

                });
                
                //分页长度改变
                // table.on( 'length.dt',   function () { console.log(1111); });
                 //table.on( 'page.dt length.dt',   function () { console.log(1111); });

               //  ajax事件-当datatable发出ajax请求前
                table.on('preXhr.dt', function () {
                    // console.log($('#jgh').val());
                    loadShow();
                });

                
                table.on('draw.dt', function () {
                 //    console.log($('#jgh').val());
                    loadFadeOut();
                });
                
                table.on('order.dt search.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
                table.on('stateSaveParams.dt', function () {
                     console.log($('#jgh').val());
                //    loadFadeOut();
                });

                //table.on('order.dt search.dt', function () {
                //    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                //        cell.innerHTML = i + 1;
                //    });
                //}).draw();

                 //let htmlable="<label>角色<select name='jgh' id='jgh'><option value='0'>所有</option><option value='1'>列表</option><option value='2'>超级管理员</option></select></label>";
                //$("#dataTableBuilder_length").prepend(htmlable);

            });
        
  </script>
  <script type="text/javascript">
      $(function () {
       
      //自写
                        //$('#jgh').change(function () {
                            //console.log($('#jgh').val());
                            //console.log(table.context[0]['ajax']['data']['jgh']);
                            //console.log(window.LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh']);
                            //通过change来重设置table中要给ajax来传递的这个值
                            //console.log($("#dataTableBuilder").DataTable);
                            //window.LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh'] = $('#jgh').val();
                            //cid = $('#jgh').val();
                            //console.log(1111);
                            //console.log(window.LaravelDataTables["dataTableBuilder"].context[0]['oAjaxData']['jgh']);

                            //console.log(window.LaravelDataTables["dataTableBuilder"]);



                            //window.LaravelDataTables["dataTableBuilder"].search('').draw();
                           // console.log(window.LaravelDataTables["dataTableBuilder"].context[0]['aoData']);
                            //console.log(window.LaravelDataTables["dataTableBuilder"].context[0]);
                            //console.log($("#dataTableBuilder"));
                            //table.search($('#jgh').val()).draw();
                          //  console.log({{Gate::forUser(auth('admin')->user())->check('admin.permission.create')}});
                       // });

      //window.LaravelDataTables["dataTableBuilder"].search($('#jgh').val()).draw();
      //table.search($('#jgh').val()).draw();
      //});
//      function ready(fn){
//        if(document.addEventListener){
//            document.addEventListener('DOMContentLoaded',function(){
//                document.removeEventListener('DOMContentLoaded',arguments.callee);
//                fn();
//            });
//        }else if(document.attachEvent){
//            document.attachEvent('onreadystatechange',function(){
//                if(document.readystate=='complete'){
//                    document.dispatchEvent('onreadystatechange',arguments.callee);
//                    //fn();
//                    let htmlable="<label>角色<select name='jgh' id='jgh'><option value='0'>所有</option><option value='1'>列表</option><option value='2'>超级管理员</option></select></label>";
//                $("#dataTableBuilder_length").prepend(htmlable);
//                }
//            })
//        }
    })
  </script>
  
@endsection