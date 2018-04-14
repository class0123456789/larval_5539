

@inject('institutionPresenter','App\Repositories\Presenters\InstitutionPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>增加机构</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form method="post" action="{{url('admin/institution')}}" class="form-horizontal" id="createForm">
      {!!csrf_field()!!}
      <div class="form-group">
        <label class="col-sm-2 control-label">机构名称</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="机构名称" name="name">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">父级机构</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent_id">
              
            {!!$institutionPresenter->topInstitutionList($institutions)!!}
          </select>
        </div>
      </div>   

      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">机构级别</label>
        <div class="col-sm-10">

          <select data-placeholder="机构类型" data-live-search="true" class="selectpicker form-control" name="kind_id">
            {!!$institutionPresenter->kindList($kinds)!!}
          </select>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">柜面系统机构号</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="柜面系统机构号" name="xtjgdh">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">人行机构编码</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="人行机构编码" name="rhjgbb">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">地址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="机构地址" name="address">
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
 