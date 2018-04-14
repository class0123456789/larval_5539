@extends('layouts.admin')
@section('css')
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
@inject('devicewarehousePresenter','App\Repositories\Presenters\DeviceWareHousePresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>设备登记管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('devicewarehouse.index'))
            <a href="{{url('admin/devicewarehouse')}}">设备信息列表</a>
            @endif
        </li>
        <li class="active">
            <strong>登记设备</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>登记设备</h5>
          <div class="ibox-tools">
              <a class="collapse-link">
                  <i class="fa fa-chevron-up"></i>
              </a>
              <a class="close-link">
                  <i class="fa fa-times"></i>
              </a>
          </div>
        </div>
        <div class="ibox-content">
          <form method="post" action="{{url('admin/devicewarehouse')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group">
              <label class="col-sm-1 control-label">品牌</label>
              <div class="col-sm-3">
                  <select name="device_brand_id" class="form-control" id="device_brand_id">
                      {!!$devicewarehousePresenter->topDeviceBrandList($devicebrands) !!}
                  </select><a href="#" id="disp_brand_id"  class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal"  data-original-title="查看" data-placement="top">品牌详情</a>
              </div>
                <label class="col-sm-1 control-label">分类</label>
                <div class="col-sm-3">
                    <select name="device_class_id" class="form-control" id="device_class_id">
                        {!!$devicewarehousePresenter->topDeviceClassList($deviceclasses) !!}
                    </select>
                </div>
                <label class="col-sm-1 control-label">型号</label>
                <div class="col-sm-3">
                    <select name="device_model_id" class="form-control" id="device_model_id">
                        {!!$devicewarehousePresenter->topDeviceModelList($devicemodels) !!}
                    </select>

                    <a href="#" id="disp_model_id"  class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal"  data-original-title="查看" data-placement="top">型号详情</a>
                </div>
            </div>

              <div class="hr-line-dashed"></div>
              <div class="form-group">
                  <label class="col-sm-1 control-label">供应商</label>
                  <div class="col-sm-3">
                      <select name="device_supplier_id" class="form-control" id="device_supplier_id">
                          {!!$devicewarehousePresenter->topDeviceSupplierList($devicesuppliers) !!}
                      </select><a href="#" id="disp_supplier_id"  class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal"  data-original-title="查看" data-placement="top">供应商详情</a>

                  </div>
                  <label class="col-sm-1 control-label">维保厂商</label>
                  <div class="col-sm-3">
                      <select name="device_provider_id" class="form-control" id="device_provider_id">
                          {!!$devicewarehousePresenter->topMaintenanceProviderList($maintenanceproviders) !!}
                      </select><a href="#" id="disp_provider_id"  class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal"  data-original-title="查看" data-placement="top">维保厂商详情</a>
                  </div>
                  <label class="col-sm-1 control-label">审批文号</label>
                  <div class="col-sm-3">
                      <select name="device_financialapproval_id" class="form-control" id="device_financialapproval_id">
                          {!!$devicewarehousePresenter->topDeviceFinancialapprovalList($financialapprovals) !!}
                      </select><a class="btn btn-xs btn-info tooltips" target="_blank" id="disp_financialapproval_id"  href="#" >查看文件</a>
                  </div>
              </div>

              <div class="hr-line-dashed"></div>
              <div class="form-group">
                  <label class="col-sm-1 control-label">序列号</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="serial_number" value="{{old('serial_number')}}" placeholder="设备序列号">
                      @if ($errors->has('serial_number'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('serial_number') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">IP址址</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_ipaddr" data-mask="999.999.999.999" value="{{old('device_ipaddr')}}" placeholder="设备IP址址">
                      <span class="help-block">192.168.100.200</span>
                      @if ($errors->has('device_ipaddr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_ipaddr') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">MAC地址</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_macaddr" data-mask="**-**-**-**-**-**"  value="{{old('device_macaddr')}}" placeholder="设备MAC地址">
                      <span class="help-block">ec-55-f9-a5-54-c6</span>
                      @if ($errors->has('device_macaddr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_macaddr') }}</span>
                      @endif


                  </div>
              </div>


              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_software_config') ? ' has-error' : '' }}">
                  <label class="col-sm-1 control-label">接入类型</label>
                  <div class="col-sm-3">
                      <select name="equipment_use_id" class="form-control" id="equipment_use_id">
                          {!!$devicewarehousePresenter->topDeviceEquipmentUseList($equipmentuses) !!}
                      </select>

                  </div>
                  <label class="col-sm-1 control-label">软件配置</label>
                  <div class="col-sm-7">
                      <input type="text" class="form-control" name="device_software_config" value="{{old('device_software_config')}}" placeholder="软件配置情况">
                      @if ($errors->has('device_software_config'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_software_config') }}</span>
                      @endif

                  </div>
              </div>



              <div class="form-group">
                  <label class="col-sm-1 control-label">购买日期</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="purchased_date" value="{{old('purchased_date')}}" placeholder="设备购买日期">
                      @if ($errors->has('purchased_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('purchased_date') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">维保期限</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="expiry_date" value="{{old('expiry_date')}}" placeholder="设备维保期限，填写数字">
                      @if ($errors->has('expiry_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('expiry_date') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">维保到期日</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="over_date" value="{{old('over_date')}}" placeholder="设备维保到期日">
                      @if ($errors->has('over_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('over_date') }}</span>
                      @endif

                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-1 control-label">购买价</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_price" value="{{old('device_price')}}" placeholder="设备购买价">
                      @if ($errors->has('device_price'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_price') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">设备状态</label>
                  <div class="col-sm-3">
                      <select name="device_work_state" class="form-control" id="device_work_state">

                      </select>

                  </div>
                  <label class="col-sm-1 control-label">保管状态</label>
                  <div class="col-sm-3">
                      <select name="device_save_state" class="form-control" id="device_save_state">

                      </select>

                  </div>

              </div>



              <div class="hr-line-dashed"></div>
              <div class="form-group">
                  <label class="col-sm-1 control-label">管理人</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_trustee" value="{{old('device_trustee')}}" placeholder="设备管理人">
                      @if ($errors->has('device_trustee'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_trustee') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">使用人</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_user')}}" placeholder="设备使用人">
                      @if ($errors->has('device_user'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_user') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">登记人</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_registrar')}}" placeholder="设备登记人">
                      @if ($errors->has('device_registrar'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_registrar') }}</span>
                      @endif

                  </div>
              </div>




              <div class="form-group">

                  <label class="col-sm-1 control-label">所在机构</label>
                  <div class="col-sm-3">
                      <select name="institution_id" class="form-control" id="institution_id">

                      </select>

                  </div>
                  <label class="col-sm-1 control-label">安装位置</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_save_addr')}}" placeholder="设备安放地址简介">
                      @if ($errors->has('device_save_addr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_save_addr') }}</span>
                      @endif

                  </div>
                  <label class="col-sm-1 control-label">所在仓库</label>
                  <div class="col-sm-3">
                      <input type="text" class="form-control" name="comment" value="{{old('house_id')}}" placeholder="设备所在仓库id，数字">
                      @if ($errors->has('house_id'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('house_id') }}</span>
                      @endif

                  </div>
              </div>

              <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                  <label class="col-sm-1 control-label">备注信息</label>
                  <div class="col-sm-11">
                      <input type="text" class="form-control" name="comment" value="{{old('comment')}}" placeholder="备注信息">
                      @if ($errors->has('comment'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('comment') }}</span>
                      @endif

                  </div>
              </div>


            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('devicewarehouse.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('devicewarehouse.store'))
                  <button class="btn btn-primary" type="submit">保存</button>
                  @endif
              </div>
            </div>
          </form>
        </div>
    </div>
  	</div>
  </div>
</div>
@include('admin.warehouse.modal')
@endsection
@section('js')
<script type="text/javascript" src="/js/plugins/iCheck/icheck.min.js"></script>
<!-- Input Mask-->
<script src="/js/plugins/jasny/jasny-bootstrap.min.js"></script>

<script type="text/javascript" src="/js/icheck.js"></script>
    <script type="text/javascript">
        $(function ()  {
            $('#disp_brand_id').attr('href',"/admin/brand/"+$('#device_brand_id').val());
            $('#disp_model_id').attr('href',"/admin/devicemodel/"+$('#device_model_id').val());
            $('#disp_financialapproval_id').attr('href',"/admin/devicewarehouse/showfile?id="+$('#device_financialapproval_id').val());
            //console.log($('#device_provier_id').val());
            $('#disp_provider_id').attr('href',"/admin/maintenanceprovider/"+$('#device_provider_id').val());
            $('#disp_supplier_id').attr('href',"/admin/supplier/"+$('#device_supplier_id').val());



            $('#device_brand_id').on('change',function () {
                //$('.modal-content').html('');
                let brand_id =  this.value;
                $('#disp_brand_id').attr('href',"/admin/brand/"+brand_id);
            });
            $('#device_model_id').on('change',function () {
                //$('.modal-content').html('');
                let model_id =  this.value;
                $('#disp_model_id').attr('href',"/admin/devicemodel/"+model_id);
            });
            $('#device_financialapproval_id').on('change',function () {
                //$('.modal-content').html('');
                let financialapproval_id =  this.value;
                $('#disp_financialapproval_id').attr('href',"/admin/devicewarehouse/showfile?id="+financialapproval_id);
            });
            $('#device_provider_id').on('change',function () {
               // $('.modal-content').html('');
                let provider_id =  this.value;
                $('#disp_provider_id').attr('href',"/admin/maintenanceprovider/"+provider_id);
            });
            $('#device_supplier_id').on('change',function () {
                //$('.modal-content').html('');
                let supplier_id =  this.value;
                $('#disp_supplier_id').attr('href',"/admin/supplier/"+supplier_id);
            });





            $("#myModal").on("hidden.bs.modal", function() { //bs3 才能使用
                $(this).removeData("bs.modal");//关闭时删除数据，这样才会重新加载数据
            });


            $('#device_brand_id').on('change',function () {//设置设备型号的选项值
                //取当前值
                let brand_id =  this.value;
                let class_id =  $('#device_class_id').val();
                //let class_idd = $('#device_class_id').find("option:selected").attr('value');
                console.log(brand_id,class_id);
                //let token =$('meta[name="_token"]').attr('content');

                ajax_model(brand_id,class_id);
            });

            $('#device_class_id').on('change',function () {//设置设备型号的选项值
                //取当前值

                let class_id =  this.value;
                let brand_id =  $('#device_brand_id').val();
                //let class_idd = $('#device_class_id').find("option:selected").attr('value');
                console.log(brand_id,class_id);
                //let token =$('meta[name="_token"]').attr('content');

                ajax_model(brand_id,class_id);


            });















        });


        function ajax_model(brand_id,class_id){
            $.ajax({
                url:'/admin/devicewarehouse/ajaxbrand',
                type:'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                },
                cache : false, //禁用缓存
                data:{brand_id:brand_id,class_id:class_id},
                success:function (data) {
                    // console.log(data);

                    console.log(data.data);//取数据
                    if(data.data.length>0){
                        $('#device_model_id').html('');
                        $('#disp_model_id').attr('href',"#");
                        let html = '';
                        let models = data.data;
                        if(models){
                        models.forEach(function (currentValue, index, arr) {
                            html = html+"<option value='"+currentValue['id']+"\'>"+ currentValue['name']+"</option>";
                        });
                        //console.log(html);
                        $('#device_model_id').html(html);

                            $('#disp_model_id').attr('href',"/admin/devicemodel/"+models[0]['id']);
                        }

                        //$('#device_class_id').val(1); //设置选中值为第一项
                    }else{
                        $('#device_model_id').html('');
                        $('#disp_model_id').attr('href',"#");
                    }

                }
            }) ;
        }
    </script>



@endsection