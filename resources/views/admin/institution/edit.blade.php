
@inject('institutionPresenter','App\Repositories\Presenters\InstitutionPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>修改机构</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form method="post" action="{{url('admin/institution',$institution->id)}}" class="form-horizontal" id="editForm">
      {!!csrf_field()!!}
      {{method_field('PUT')}}
      <input type="hidden" name="id" value="{{$institution->id}}">
      <div class="form-group">
        <label class="col-sm-2 control-label">机构名称</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="机构名称" name="name" value="{{$institution->name}}">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">父级机构</label>
        <div class="col-sm-10">
          <select class="form-control" name="parent_id" >
            
            {!!$institutionPresenter->topInstitutionList($institutions,$institution->parent_id)!!}
          </select>
        </div>
      </div>   
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">机构类型</label>
        <div class="col-sm-10">
          
           <select data-placeholder="机构类型" data-live-search="true" class="selectpicker form-control" name="kind_id" >
            {!!$institutionPresenter->kindList($kinds,$institution->kind_id)!!}
          </select>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
              <a class="btn btn-white close-link">关闭</a>
              <button class="btn btn-primary editButton ladda-button"  data-style="zoom-in">保存</button>
          </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $('.selectpicker').selectpicker();

</script>