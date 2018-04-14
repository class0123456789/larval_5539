
@inject('institutionPresenter','App\Repositories\Presenters\InstitutionPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="showBox">
  <div class="ibox-title">
    <h5>查看机构</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form class="form-horizontal" id="showForm">
      <div class="form-group">
        <label class="col-sm-3 control-label">机构名称</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institution->name}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">父级机构</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institutionPresenter->topInstitutionName($institutions,$institution->parent_id)}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">机构级别</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institutionPresenter->topKindName($kinds,$institution->kind_id)}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">柜面系统机构号</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institution->xtjgdh}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">人行机构编码</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institution->rhjgbb}}</p>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-3 control-label">地址</label>
        <div class="col-sm-9">
          <p class="form-control-static">{{$institution->address}}</p>
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