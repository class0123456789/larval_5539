@extends('layouts.admin')
@section('css')
<link href="/vendors/iCheck/custom.css" rel="stylesheet">
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
            <div class="form-group{{ $errors->has('device_model_id') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">设备型号</label>
              <div class="col-sm-10">
                  <select name="device_model_id" class="form-control" id="device_model_id">
                      {!!$devicewarehousePresenter->topBrandList($brands) !!}
                  </select>
              </div>
            </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_supplier_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备供应商</label>
                  <div class="col-sm-10">
                      <select name="device_supplier_id" class="form-control" id="device_supplier_id">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_maintenaceprovier_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备维保厂商</label>
                  <div class="col-sm-10">
                      <select name="device_maintenaceprovier_id" class="form-control" id="device_maintenaceprovier_id">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_financialapproval_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">财务审批号</label>
                  <div class="col-sm-10">
                      <select name="device_financialapproval_id" class="form-control" id="device_financialapproval_id">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备条型码</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="barcode" value="{{old('barcode')}}" placeholder="设备条型码">
                      @if ($errors->has('barcode'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('barcode') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('serial_number') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备序列号</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="serial_number" value="{{old('serial_number')}}" placeholder="设备序列号">
                      @if ($errors->has('serial_number'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('serial_number') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_ipaddr') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备IP址址</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_ipaddr" value="{{old('device_ipaddr')}}" placeholder="设备IP址址">
                      @if ($errors->has('device_ipaddr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_ipaddr') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_macaddr') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备MAC地址</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_macaddr" value="{{old('device_macaddr')}}" placeholder="设备MAC地址">
                      @if ($errors->has('device_macaddr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_macaddr') }}</span>
                      @endif


                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_software_config') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">软件配置情况</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_software_config" value="{{old('device_software_config')}}" placeholder="软件配置情况">
                      @if ($errors->has('device_software_config'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_software_config') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('equipment_use_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备用途</label>
                  <div class="col-sm-10">
                      <select name="equipment_use_id" class="form-control" id="equipment_use_id">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_price') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备购买价</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_price" value="{{old('device_price')}}" placeholder="设备购买价">
                      @if ($errors->has('device_price'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_price') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('purchased_date') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备购买日期</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="purchased_date" value="{{old('purchased_date')}}" placeholder="设备购买日期">
                      @if ($errors->has('purchased_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('purchased_date') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('expiry_date') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备维保期限</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="expiry_date" value="{{old('expiry_date')}}" placeholder="设备维保期限，填写数字">
                      @if ($errors->has('expiry_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('expiry_date') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('over_date') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备维保到期日</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="over_date" value="{{old('over_date')}}" placeholder="设备维保到期日">
                      @if ($errors->has('over_date'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('over_date') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_work_state') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备工作状态</label>
                  <div class="col-sm-10">
                      <select name="device_work_state" class="form-control" id="device_work_state">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_save_state') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备保管状态</label>
                  <div class="col-sm-10">
                      <select name="device_save_state" class="form-control" id="device_save_state">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_save_addr') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备安放地</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_save_addr')}}" placeholder="设备安放地址简介">
                      @if ($errors->has('device_save_addr'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_save_addr') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_trustee') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备管理人</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_trustee" value="{{old('device_trustee')}}" placeholder="设备管理人">
                      @if ($errors->has('device_trustee'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_trustee') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_user') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备使用人</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_user')}}" placeholder="设备使用人">
                      @if ($errors->has('device_user'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_user') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('device_registrar') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备登记人</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="device_save_addr" value="{{old('device_registrar')}}" placeholder="设备登记人">
                      @if ($errors->has('device_registrar'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('device_registrar') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('institution_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备所在机构</label>
                  <div class="col-sm-10">
                      <select name="institution_id" class="form-control" id="institution_id">
                          {!!$devicewarehousePresenter->topBrandList($brands) !!}
                      </select>

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">备注信息</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="comment" value="{{old('comment')}}" placeholder="备注信息">
                      @if ($errors->has('comment'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('comment') }}</span>
                      @endif

                  </div>
              </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('house_id') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">设备所在仓库</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="comment" value="{{old('house_id')}}" placeholder="设备所在仓库id，数字">
                      @if ($errors->has('house_id'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('house_id') }}</span>
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
@include('admin.user.modal')
@endsection
@section('js')
<script type="text/javascript" src="/vendors/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/admin/js/icheck.js"></script>

@endsection