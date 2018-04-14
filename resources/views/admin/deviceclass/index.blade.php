@extends('layouts.admin')
@section('css')
<link rel="stylesheet" type="text/css" href="/css/plugins/nestable/nestable.css">
<link rel="stylesheet" type="text/css" href="/css/plugins/bootstrap-select/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="/css/plugins/ionRangeSlider/ion.rangeSlider.css">
<link rel="stylesheet" type="text/css" href="/css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css">
<link rel="stylesheet" type="text/css" href="/css/plugins/ladda/ladda-themeless.min.css">

@endsection
@section('content')
@inject('deviceclassPreseter','App\Repositories\Presenters\DeviceClassPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>设备分类管理</h2>
    <ol class="breadcrumb">
      <li>
           @if(haspermission('admin/dash'))
          <a href="{{url('admin/dash')}}">主控制台</a>
           @endif
      </li>
      <li class="active">
          <strong>设备分类管理</strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-2">
    <div class="title-action">
        @if(haspermission('admin/deviceclass/clear'))
        <a href="/admin/deviceclass/clear" class="btn btn-info"><i class="fa fa-cancel"></i>清除设备分类缓存</a>
        @endif
        
    </div>
  </div>    
</div>
<div class="wrapper wrapper-content  animated fadeInRight">
  <div class="row">
    @include('flash::message')
    <div class="col-lg-6">
      <div class="ibox animated fadeInRightBig">
        <div class="ibox-title">
            <h5>设备分类列表</h5>
        </div>
        <div class="ibox-content">
          <div class="dd" id="nestable">
              <ol class="dd-list">
                {!!$deviceclassPreseter->deviceclassNestable($deviceclasses)!!}
              </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 deviceclassBox">
        <div class="middle-box text-center animated fadeInRightBig">
            <h3 class="font-bold">Welcome ！</h3>
            <div class="error-desc">
                 
                @if(haspermission('deviceclass.create'))
                你可以操作左侧设备分类列表，或者点击下面添加按钮新增设备分类！<br><a href="javascript:;" class="btn btn-primary m-t create_DeviceClass">添加设备分类</a>
                @endif
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script src="/js/plugins/nestable/jquery.nestable.js/"></script>
<script src="/js/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="/js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
<script src="/js/plugins/ladda/spin.min.js"></script>
<script src="/js/plugins/ladda/ladda.min.js"></script>
<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>
<script src="/js/plugins/layer/layer.js"></script>
<script src="/js/admin/deviceclass/deviceclass.js"></script>


<script type="text/javascript">
  $('#nestable').on('click','.destroy_item',function() {
    var _item = $(this);
    var title = "设备分类删除提示";
    layer.confirm(title, {
      btn:['确定', '取消'],
      icon: 5
    },function(index){
      _item.children('form').submit();
      layer.close(index);
    });
  });
</script>

@endsection