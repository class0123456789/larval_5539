@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
@inject('rolePresenter','App\Repositories\Presenters\RolePresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>角色管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('role.index'))
            <a href="{{url('admin/role')}}">角色列表</a>
            @endif
        </li>
        <li class="active">
            <strong>增加角色</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>增加角色</h5>
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
          <form method="post" action="{{url('admin/role')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">角色名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="角色名称"> 
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">角色</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="slug" value="{{old('slug')}}" placeholder="角色"> 
                @if ($errors->has('slug'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('slug') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <label class="col-sm-2 control-label">描述</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="description" value="{{old('description')}}" placeholder="角色描述">
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <label class="col-sm-2 control-label">角色权限</label>
              <div class="col-sm-10">
                <div class="ibox float-e-margins">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                          <th class="col-md-1 text-center">类别</th>
                          <th class="col-md-10 text-center">权限</th>
                      </tr>
                    </thead>
                    <tbody>
                      {!! $rolePresenter->permissionList($permissions) !!}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('role.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('role.store'))
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
@endsection
@section('js')
<script type="text/javascript" src="/js/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/js/icheck.js"></script>

@endsection