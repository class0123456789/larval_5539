@extends('layouts.admin')
@section('css')
<link href="/admin/css/dropzone/basic.css" rel="stylesheet">
<link href="/admin/css/dropzone/dropzone.css" rel="stylesheet">
<link href="/admin/css/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="/admin/css/dropzone/style.css" rel="stylesheet">


@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>购置批文管理</h2>
    <ol class="breadcrumb">
        <li>
            @if(haspermission('admin/dash'))
            <a href="{{url('admin/dash')}}">主控制台</a>
            @endif
        </li>
        <li>
            @if(haspermission('financialapproval.index'))
            <a href="{{url('admin/financialapproval')}}">购置批文列表</a>
            @endif
        </li>
        <li class="active">
            <strong>增加购置批文</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>增加购置批文</h5>
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
          <form method="post" action="{{url('admin/financialapproval')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('file_no') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">文件编号</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="file_no" value="{{old('file_no')}}" placeholder="文件编号">
                @if ($errors->has('file_no'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('file_no') }}</span>
                @endif
              </div>
            </div>
              <div class="form-group{{ $errors->has('file_url') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">文件URL</label>
                  <div class="col-sm-10">



                      <div class="input-group">
                          <input type="text" class="form-control" name="file_url" value="{{old('file_url')}}" placeholder="文件URL">
                          @if ($errors->has('file_url'))
                              <span class="help-block m-b-none text-danger">{{ $errors->first('file_url') }}</span>
                          @endif

                      </div>
                  </div>
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="ibox">
                              <div class="ibox-title">
                                  <h5>Dropzone.js</h5>
                              </div>
                              <div class="ibox-content">

                                  <p>
                                      <strong>Dropzone.js</strong> is a light weight JavaScript library that turns an HTML element into a dropzone. This means that a user can drag and drop a file onto it, and the file gets uploaded to the server via AJAX.
                                  </p>

                                  <form action="#" class="dropzone" id="dropzoneForm">
                                      <div class="fallback">
                                          <input name="file" type="file" multiple />
                                      </div>
                                  </form>

                                  <p class="m-t-xs">
                                      HTML markup code for abowe example:
                                  </p>
                                  <textarea id="code3">
&lt;form action="#" class="dropzone" id="dropzoneForm"&gt;
    &lt;div class="fallback"&gt;
        &lt;input name="file" type="file" multiple /&gt;
    &lt;/div&gt;
&lt;/form&gt; </textarea>
                                  <p class="m-t-xs">All avalible options and full documentation you can find: <a href="http://www.dropzonejs.com/#configuration-options">http://www.dropzonejs.com/#configuration-options</a> </p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>




            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  @if(haspermission('financialapproval.index'))
                  <a class="btn btn-default" href="{{url()->previous()}}">返回</a>
                  @endif
                  @if(haspermission('financialapproval.store'))
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
@endsection
@section('js')
    <!-- Custom and plugin javascript -->

    <script src="/vendors/pace/pace.min.js"></script>
    <script src="/vendors/jasny/jasny-bootstrap.min.js"></script>
    <!-- DROPZONE -->
    <script src="/vendors/dropzone/dropzone.js"></script>
<script type="text/javascript" src="/vendors/iCheck/icheck.min.js"></script>
<script type="text/javascript" src="/admin/js/icheck.js"></script>
    <script type="text/javascript">


        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };


    </script>


@endsection