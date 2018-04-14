@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
<!-- Ladda style -->
<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">

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
            <strong>员工信息下载</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>员工信息下载</h5>
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
          <form id='download' method="post" action="/admin/employee/download" class="form-horizontal">
            {{csrf_field()}}
              <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">选择机构</label>
              <div class="col-sm-10">
                  <select name="institution_id" class="form-control" id="institution">
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
                  @if(haspermission('admin/employee/download'))
                    <button class="ladda-button ladda-button-demo btn btn-primary"  data-style="zoom-in" >确定下载</button>
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
<!-- Ladda -->
<script src="/js/plugins/ladda/spin.min.js"></script>
<script src="/js/plugins/ladda/ladda.min.js"></script>
<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>
<script>

    $(document).ready(function (){

        // Bind normal buttons
        Ladda.bind( '.ladda-button',{ timeout: 1000 });




        var l = $( '.ladda-button-demo' ).ladda();

        l.click(function(){
            // Start loading
            l.ladda( 'start' );
            //console.log($('#download').attr('action'));
            //let data =$('#download').serialize();
            //手动提交完成后，按钮ladda stop
            $.when($('#download').submit()).done(function(data){
                l.ladda('stop');
                //console.log(data);
            });

            // Timeout example
            // Do something in backend and then stop ladda
            //setTimeout(function(){
            //    l.ladda('stop');
            //},12000)


        });


    });

</script>




@endsection