@extends('layouts.admin')
@section('css')
<link href="/vendors/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>用途管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('equipment.index'))
            <a href="{{url('admin/equipment')}}">用途列表</a>
            @endif
        </li>
        <li class="active">
            <strong>修改用途</strong>
        </li>
    </ol>
  </div>
  
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>修改用途</h5>
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
          <form method="post" action="{{url('admin/equipment/'.$equipment->id)}}" class="form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">用途名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name',$equipment->name)}}" placeholder="用途名称">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('equipment.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('equipment.update'))
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
<script type="text/javascript" src="/vendors/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/admin/js/icheck.js"></script>
@endsection