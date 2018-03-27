

@inject('deviceclassPresenter','App\Repositories\Presenters\DeviceClassPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>增加设备分类</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form method="post" action="{{url('admin/deviceclass')}}" class="form-horizontal" id="createForm">
      {!!csrf_field()!!}
      <div class="form-group">
        <label class="col-sm-2 control-label">设备分类名称</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="设备分类名称" name="name">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">父级设备分类</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent_id">
              
            {!!$deviceclassPresenter->topDeviceClassList($deviceclasses)!!}
          </select>
        </div>
      </div>   

      <div class="hr-line-dashed"></div>
      <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <a class="btn btn-white close-link">关闭</a>
            <button class="btn btn-primary createButton ladda-button"  data-style="zoom-in">确定</button>
          </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $('.selectpicker').selectpicker();
</script>
 