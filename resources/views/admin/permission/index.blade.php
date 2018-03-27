@extends('layouts.admin')
@section('css')
<link href="/vendors/dataTables/datatables.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>权限管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li class="active">
           <strong>权限列表</strong>
        </li>
    </ol>
  </div>
  <div class="col-lg-2">
    <div class="title-action">
       @if(haspermission('permission.create'))
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
                     <th >行号</th>
                     <th >ID</th>
                     <th >权限名称</th>
                     <th >权限规则</th>
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
                      url: "/admin/permission",
                      type: 'get',
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                      },
                      cache : false, //禁用缓存
                      data: {jgh : cid},   //自定义传递的参数
                      //success : function(res){
                      //          console.log(res);
                      //      }//success : function(res){
                            //    console.log(res);
                            //}
                  },
                         // "spagingType": "full_numbers",
     // "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgtip',
                    "buttons": [
        {extend: 'copy',title: 'permission'},
        {extend: 'csv',title: 'permission'},
        {extend: 'excel', title: 'permission'},
        //{extend: 'pdf', title: 'permission'},
        {extend: 'print',}
        ],
                    "columns": [
                        //{"data": null,"searchable": false,"orderable": false}, //行号列
                        {"data": 'row_no',"searchable": false,"orderable": false}, //行号列
                        {"data": "id"},
                        {"data": "name"},
                        {"data": "slug"},
                        {"data": "description"},
                        {"data": "created_at"},
                        {"data": "updated_at"},
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
                            var row_edit = {{haspermission('permission.edit')? 1 : 0}};
                            var row_delete = {{haspermission('permission.destroy') ? 1 :0}};
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

                                        //关闭错误提示
                    //$.fn.dataTable.ext.errMode = 'throw',
                    
                                        'drawCallback':function() {
                                                table.$('.tooltips').tooltip( {
                                                    placement : 'top',
                                                    html : true
                                                });
                                                //增加选择框
                                                if($('#jgh').length===0){
                                                        let htmlable="<label>菜单<select name='jgh' id='jgh'><option value='0' check>所有</option><option value='1'>一级菜单</option><option value='2'>二级菜单</option><option value='-1'>非菜单</option></select></label>";
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

            });      
  </script>
    @endsection 
