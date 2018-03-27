@extends('layouts.admin')
@section('css')
<link href="/vendors/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
@inject('userPresenter','App\Repositories\Presenters\UserPresenter')
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
            @if(haspermission('fuser.index'))
            <a href="{{url('admin/fuser')}}">前台用户列表</a>
            @endif
        </li>
        <li class="active">
            <strong>查看前台用户</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>查看前台用户</h5>
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
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">用户名称</label>
              <div class="col-sm-10">
                <p class="form-control-static">{{$user->name}}</p>
              </div>
            </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户邮箱</label>
              <div class="col-sm-10">
                <p class="form-control-static">{{$user->email}}</p>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用户邮箱</label>
              <div class="col-sm-10">
                <p class="form-control-static">{{$user->email}}</p>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">绑定机构</label>
              <div class="col-sm-10">
                <p class="form-control-static">[{{$user->employee->institution()->first()->id}}]{{$user->employee->institution()->first()->name}}</p>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">绑定员工</label>
              <div class="col-sm-10">
                <p class="form-control-static">[{{$user->employee->id}}]{{$user->employee->name}}</p>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('fuser.index'))
                  <a class="btn btn-white" href="{{url()->previous()}}">关闭</a>
                  @endif
              </div>
            </div>
          </form>
        </div>
    </div>
  	</div>
  </div>
</div>
@include('admin.fuser.modal')
@endsection
@section('js')
<script type="text/javascript" src="/vendors/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/admin/js/icheck.js"></script>
@endsection