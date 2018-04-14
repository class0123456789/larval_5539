@inject('devicemodelPresenter','App\Repositories\Presenters\DeviceModelPresenter')
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">型号详情</h4>
</div>
<div class="modal-body">
    <form class="form-horizontal">
        <div class="hr-line-dashed no-margins"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label">型号名称</label>
            <div class="col-sm-8">
                <p class="form-control-static">{{$devicemodel->name}}</p>
            </div>
        </div>
        <div class="hr-line-dashed no-margins"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label">硬件配置</label>
            <div class="col-sm-8">
                <p class="form-control-static">{{$devicemodel->hardconfig}}</p>
            </div>
        </div>
        <div class="hr-line-dashed no-margins"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label">所属品牌</label>
            <div class="col-sm-8">
                <p class="form-control-static">{{$devicemodel['brand']->name}}</p>
            </div>
        </div>
        <div class="hr-line-dashed no-margins"></div>
        <div class="form-group">
            <label class="col-sm-3 control-label">所属分类</label>
            <div class="col-sm-8">
                <ul class="unstyled">
                    {!! $devicemodelPresenter->topDeviceClassDisplay($deviceclasses,$devicemodel->class_id) !!}
                </ul>
            </div>
        </div>

    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
