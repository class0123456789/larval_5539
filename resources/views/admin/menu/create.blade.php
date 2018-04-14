
@inject('menuPresenter','App\Repositories\Presenters\MenuPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>增加菜单</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form method="post" action="{{url('admin/menu')}}" class="form-horizontal" id="createForm">
      {!!csrf_field()!!}
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单名称</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="菜单名称" name="name">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">父级菜单</label>
        <div class="col-sm-10">
          <select class="form-control" name="pid">
            {!!$menuPresenter->topMenuList($menus)!!}
          </select>
        </div>
      </div>
   <!-- Ionicons -->
    <link rel="stylesheet" href="/ionicons/css/ionicons.min.css">
    
    <link rel="stylesheet" href="/css/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单图标</label>
        <div class="col-sm-10">
           <button class="btn btn-default" name="icon" data-iconset="fontawesome" data-icon="fa-sliders" role="iconpicker"></button>
          
        </div>
      </div>


    <script type="text/javascript" src="/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js"></script>
    <script type="text/javascript" src="/js/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>


      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单权限</label>
        <div class="col-sm-10">
          
           <select data-placeholder="菜单权限,这里显示的是权限的名称,必须在权限表中存在" data-live-search="true" class="selectpicker form-control" name="slug">
            {!!$menuPresenter->permissionList($permissions)!!}
          </select>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单链接</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="菜单链接,为顶级菜单时可不输入,如果输入必须在权限表中存在" name="url">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">高亮地址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="高亮地址,必须输入如：admin/logout*,星号必须输入，多个必须带逗号分隔" name="active">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">描述</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="描述，必须输入" name="description">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">排序</label>
        <div class="col-sm-10">
          <input type="text" id="sort"  name='sort' class="form-control"/>
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
  $('#sort').ionRangeSlider({
      type: "single",
      min: 0,
      max: 100,
      from: 0
  });
</script>
 