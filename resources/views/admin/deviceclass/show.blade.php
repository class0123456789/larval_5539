
@inject('deviceclassPresenter','App\Repositories\Presenters\DeviceClassPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="showBox">
  <div class="ibox-title">
    <h5>查看设备分类</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form class="form-horizontal" id="showForm">
      <div class="form-group">
        <label class="col-sm-3 control-label">设备分类名称</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$deviceclass->name}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">父级设备分类</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$deviceclassPresenter->topDeviceClassName($deviceclasses,$deviceclass->parent_id)}}</p>
        </div>
      </div>

      <div class="hr-line-dashed"></div>
      <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
              <a class="btn btn-white close-link">关闭</a>
          </div>
      </div>
    </form>
  </div>
</div>