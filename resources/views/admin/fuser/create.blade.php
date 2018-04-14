@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
@inject('fuserPresenter','App\Repositories\Presenters\FuserPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>前台用户管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('user.index'))
            <a href="{{url('admin/fuser')}}">前台用户列表</a>
            @endif
        </li>
        <li class="active">
            <strong>增加前台用户</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>增加前台用户</h5>
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
          <form method="post" action="{{url('admin/fuser')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="用户名称不要使用中文">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户邮箱</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="email" value="{{old('email')}}" placeholder="用户邮箱"> 
                @if ($errors->has('email'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">密码</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" name="password" value="{{old('password')}}" placeholder="密码"> 
                @if ($errors->has('password'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
            </div>
            <div class="form-group">
            <label class="col-sm-2 control-label">确认密码</label>
              <div class="col-sm-10">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  placeholder="确认密码"> 

              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">绑定机构</label>
              <div class="col-sm-10">
                  <select name="institution_id" class="form-control" id="institution">
                      {!!$fuserPresenter->topInstitutionList($institutions) !!}
                  </select>
                 
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">绑定员工</label>
              <div class="col-sm-10">
                  <select name="employee_id" class="form-control">
                       {!!$fuserPresenter->topEmployeeList($employees) !!}
                  </select>
                 
              </div>
            </div>            
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('fuser.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('fuser.store'))
                  <button class="btn btn-primary" type="submit">保存</button>
                  @endif
              </div>
            </div>
          </form>
        </div>
    </div>
  	</div>
  </div>
</div>
@include('admin.user.modal')
@endsection
@section('js')
<script type="text/javascript" src="/js/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/js/icheck.js"></script>
<script type="text/javascript">
    $(function () {
        if($("select[name=employee_id] >option").length===0){//选项的个数为0
            $("button[type=submit]").hide();
        };
    $('#institution').on('change',function(){
          
           //console.log($(this).val()); 
           //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}});
           $.ajax({
               url:'/admin/fuser/getemployees',
               type:'post',
               data:{id:$(this).val()},
               dataType: 'json',
               headers : {
               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
              },
               success:function (response) {
                    //console.log(response.data);
                   if(response.data.length>0){
                        $("select[name=employee_id]").empty();
                        $sele=$("select[name=employee_id]");
                    for(var i in response.data){
                        $option = "<option value='"+response.data[i].id+ "'>"+response.data[i].name+"</option>";
                        $sele.append($option);//增加元素
                        console.log(response.data[i].id+" "+response.data[i].name);
                        $("button[type=submit]").show();
                    }
                   }else{
                       console.log('值为空');
                       $("select[name=employee_id]").empty();
                       $("button[type=submit]").hide();
                   }
               },
               error:function(err){
                   console.log(err); 
               },
           });
    });
	// 关闭modal清空内容
  
});
</script>
@endsection