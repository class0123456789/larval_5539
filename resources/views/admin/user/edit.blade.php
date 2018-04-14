@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
@inject('userPresenter','App\Repositories\Presenters\UserPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>用户管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('user.index'))
            <a href="{{url('admin/user')}}">用户列表</a>
            @endif
        </li>
        <li class="active">
            <strong>修改用户</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>修改用户</h5>
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
          <form method="post" action="{{url('admin/user',$user->id)}}" class="form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name',$user->name)}}" placeholder="用户名称"> 
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户邮箱</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="email" value="{{old('email',$user->email)}}" placeholder="用户邮箱">
                @if ($errors->has('email'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">绑定机构</label>
              <div class="col-sm-10">
                  <select name="institution_id" class="form-control">
                      {!!$userPresenter->topInstitutionList($institutions,$user->institution->id) !!}
                  </select>
                 
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户密码</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" name="password" value="{{old('password')}}" placeholder="用户密码"> 
                <span class="help-block text-warning m-b-none">密码为空则不修改密码</span>
                @if ($errors->has('password'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
            </div>
  
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <label class="col-sm-2 control-label">用户角色</label>
              <div class="col-sm-10">
                {!!$userPresenter->roleList($roles,array_column($user->roles->toArray(),'id'))!!}
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <label class="col-sm-2 control-label">用户权限</label>
              <div class="col-sm-10">
                <div class="ibox float-e-margins">
                  <div class="alert alert-warning">
                    绑定用户权限
                  </div>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                          <th class="col-md-1 text-center">分类</th>
                          <th class="col-md-10 text-center">权限</th>
                      </tr>
                    </thead>
                    <tbody>
                      {!! $userPresenter->permissionList($permissions,array_column($user->permissions->toArray(),'id')) !!}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('user.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('user.update'))
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
@endsection