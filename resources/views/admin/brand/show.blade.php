<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">品牌查看</h4>
</div>
<div class="modal-body">
  <form class="form-horizontal">
    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">品牌名称</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{$brand->name}}</p>
      </div>
    </div>
    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">联系人</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{$brand->contact}}</p>
      </div>
    </div>
    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">联系电话</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{$brand->phone}}</p>
      </div>
    </div>
    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">联系地址</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{$brand->address}}</p>
      </div>
    </div>

  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
