@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
    @inject('devicemodelPresenter','App\Repositories\Presenters\DeviceModelPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>设备型号管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('devicemodel.index'))
            <a href="{{url('admin/devicemodel')}}">设备型号列表</a>
            @endif
        </li>
        <li class="active">
            <strong>修改设备型号</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>修改设备型号</h5>
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
          <form method="post" action="{{url('admin/devicemodel',$devicemodel->id)}}" class="form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            
            
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">设备型号</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name',$devicemodel->name)}}" placeholder="用户名称"> 
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('hardconfig') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">硬件配置</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="hardconfig" value="{{old('hardconfig',$devicemodel->hardconfig)}}" placeholder="硬件配置简介">
                      @if ($errors->has('hardconfig'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('hardconfig') }}</span>
                      @endif

                  </div>
              </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('brand_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">所属品牌</label>
              <div class="col-sm-10">
                  <select name="brand_id" class="form-control" id="brand_id">
                      {!!$devicemodelPresenter->topBrandList($brands,$devicemodel->brand->id) !!}

                  </select>
                 
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('class_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">所属设备分类</label>
              <div class="col-sm-10">
                  <select name="class_id" class="form-control">
                       {!!$devicemodelPresenter->topDeviceClasslList($curr_deviceclasses,$devicemodel->deviceclass->id) !!}

                  </select>
                 
              </div>
            </div>   
            

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('devicemodel.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('devicemodel.update'))
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
@include('admin.devicemodel.modal')
@endsection
@section('js')
<script type="text/javascript" src="/js/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/js/icheck.js"></script>

@endsection