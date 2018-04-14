@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/plugins/bootstrap-iconpicker/icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/css/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
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
           <strong>修改权限</strong>
        </li>
    </ol>
  </div>
  <div class="col-lg-2">
    <div class="title-action">
      @if(haspermission('permission.index'))
      <a href="{{route('permission.index')}}" class="btn btn-info"><i class="fa fa-plus"></i>返回</a>
      @endif
      
    </div>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>修改权限</h5>
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
          <form method="post" action="{{route('permission.update',$id)}}" class="form-horizontal">
            {{csrf_field()}}
            {{ method_field('PUT') }}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">权限名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{$name}}" placeholder="权限名称"> 
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">权限规则</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" name="slug"  value="{{$slug}}" placeholder="权限规则"> 
                @if ($errors->has('slug'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('slug') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <label class="col-sm-2 control-label">权限描述</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="description" value="{{old('description', $description)}}" placeholder="权限描述">
              </div>
            </div>
            <!--
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                 <label class="col-sm-2 control-label">菜单类型</label>
                 <div class="col-sm-10">
                    <div class="i-checks">
                        <label> <input type="radio" name='cid' value="1"   ><i></i>一级菜单项</label>
                        <label> <input type="radio" name='cid' value="2"  ><i></i>二级菜单项</label>
                        <label> <input type="radio" name='cid' value="-1" ><i></i>非菜单项</label>
                    </div>
                </div>
            </div>-->
            <!--<div class="hr-line-dashed"></div>
            <div class="form-group">
                <label for="tag" class="col-sm-2 control-label">菜单图标</label>
                <div class="col-sm-10">
                 Button tag 
                <button class="btn btn-default" name="icon" data-iconset="fontawesome" data-icon="" role="iconpicker"></button>
                </div>
            </div>-->
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('permission.index'))
                  <a class="btn btn-default" href="{{route('permission.index')}}">返回</a>
                  @endif
                  
                  @if(haspermission('permission.update'))
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
    <script type="text/javascript" src="/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js"></script>
    <script type="text/javascript" src="/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>
@endsection