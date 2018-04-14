@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
@endsection
@section('content')
@inject('employeePresenter','App\Repositories\Presenters\EmployeePresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>员工管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('employee.index'))
            <a href="{{url('admin/employee')}}">员工列表</a>
            @endif
        </li>
        <li class="active">
            <strong>增加员工</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>增加员工</h5>
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
          <form method="post" action="{{url('admin/employee')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">员工名称</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="员工名称"> 
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">手机号码</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="mobile" value="{{old('mobile')}}" placeholder="手机号码"> 
                @if ($errors->has('mobile'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('mobile') }}</span>
                @endif
              </div>
            </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('sfz') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">身份证号</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="sfz" value="{{old('sfz')}}" placeholder="身份证号">
                      @if ($errors->has('sfz'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('sfz') }}</span>
                      @endif
                  </div>
              </div>
              <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('sex') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">性别</label>
              <div class="col-sm-10">
                {!!$employeePresenter->sexList()!!}
                @if ($errors->has('sex'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('sex') }}</span>
                @endif
              </div>
            </div>  
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('post') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">员工岗位</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="post" value="{{old('post')}}" placeholder="员工岗位"> 
                @if ($errors->has('post'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('post') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">所在机构</label>
              <div class="col-sm-10">
                <select name="institution_id" class="form-control">
                      {!!$employeePresenter->topInstitutionList($institutions) !!}
                 </select>
              </div>
            </div> 

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('employee.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('employee.store'))
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