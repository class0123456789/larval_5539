
@inject('menuPresenter','App\Repositories\Presenters\MenuPresenter')
<div class="ibox float-e-margins animated bounceIn formBox" id="createBox">
  <div class="ibox-title">
    <h5>修改菜单</h5>
    <div class="ibox-tools">
      <a class="close-link">
          <i class="fa fa-times"></i>
      </a>
    </div>
  </div>
  <div class="ibox-content">
    <form method="post" action="{{url('admin/menu',$menu->id)}}" class="form-horizontal" id="editForm">
      {!!csrf_field()!!}
      {{method_field('PUT')}}
      <input type="hidden" name="id" value="{{$menu->id}}">
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单名称</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="菜单名称" name="name" value="{{$menu->name}}">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">父级菜单</label>
        <div class="col-sm-10">
          <select class="form-control" name="pid">
            {!!$menuPresenter->topMenuList($menus,$menu->pid)!!}
          </select>
        </div>
      </div>
      <!-- Ionicons -->
    <link rel="stylesheet" href="/libs/ionicons/css/ionicons.min.css">
    
    <link rel="stylesheet" href="/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单图标</label>
        <div class="col-sm-10">
           <button class="btn btn-default" name="icon" data-iconset="fontawesome" data-icon="{{$menu->icon}}" role="iconpicker"></button>
          
        </div>
      </div>


    <script type="text/javascript" src="/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js"></script>
    <script type="text/javascript" src="/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>

      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单权限</label>
        <div class="col-sm-10">
         
          <select data-live-search="true" class="selectpicker form-control" name="slug">
            {!!$menuPresenter->permissionList($permissions, $menu->slug)!!}
          </select>
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">菜单链接</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="菜单链接" name="url" value="{{$menu->url}}">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">高亮地址</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="高亮地址" name="active" value="{{$menu->active}}">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">描述</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" placeholder="描述" name="description" value="{{$menu->description}}">
        </div>
      </div>
      <div class="hr-line-dashed"></div>
      <div class="form-group">
        <label class="col-sm-2 control-label">排序</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="sort"  name="sort" value="{{$menu->sort}}">
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
  $('#sort').ionRangeSlider({
      type: "single",
      min: 0,
      max: 100,
      from: "{{$menu->sort}}"
  });
</script>