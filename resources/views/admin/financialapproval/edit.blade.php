@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>购置批文管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('financialapproval.index'))
            <a href="{{url('admin/financialapproval')}}">购置批文列表</a>
            @endif
        </li>
        <li class="active">
            <strong>修改审批文</strong>
        </li>
    </ol>
  </div>
  
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>修改审批文件</h5>
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
          <form method="post" action="{{url('admin/financialapproval/'.$financialapproval->id)}}" class="form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            
            <div class="form-group{{ $errors->has('file_no') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">文件编号</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="file_no" value="{{old('file_no',$financialapproval->file_no)}}" placeholder="文件编号">
                @if ($errors->has('file_no'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('file_no') }}</span>
                @endif
              </div>
            </div>
              <div class="form-group{{ $errors->has('file_url') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">文件编号</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" readonly name="file_url" value="{{old('file_url',$financialapproval->file_url)}}" placeholder="文件名称">
                      @if ($errors->has('file_url'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('file_url') }}</span>
                      @endif
                  </div>
              </div>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('financialapproval.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('financialapproval.update'))
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