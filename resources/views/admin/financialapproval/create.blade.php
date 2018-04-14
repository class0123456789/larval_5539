@extends('layouts.admin')
@section('css')
<link href="/css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="/css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">




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
                          <input type="text" class="form-control" name="file_url" value="{{old('file_url')}}" readonly placeholder="请选择上传文件">
                          @if ($errors->has('file_url'))
                              <span class="help-block m-b-none text-danger">{{ $errors->first('file_url') }}</span>
                          @endif
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

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>设备审批文件上传</h5>
                </div>
                <div class="ibox-content">



                    <form action="#" class="dropzone" id="dropzoneForm">
                        <div class="fallback">
                            <input name="file" type="file"  />
                        </div>
                    </form>




                </div>
            </div>
        </div>
    </div>




</div>
@endsection
@section('js')
    <script type="text/javascript" src="/js/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/js/icheck.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="/js/plugins/pace/pace.min.js"></script>

    <!-- Jasny -->
    <script src="/js/plugins/jasny/jasny-bootstrap.min.js"></script>



    <!-- DROPZONE -->
    <script src="/js/plugins/dropzone/dropzone.js"></script>
    <!-- CodeMirror -->




    <script type="text/javascript">


            Dropzone.options.dropzoneForm = {
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 512, // MB
                maxFiles: 1,
                uploadMultiple: false,
                addRemoveLinks: true,
                dictRemoveFile: '删除',
                url: "/component/upload", // Set the url
                method: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                dictDefaultMessage: "<strong>拖动文件到这里或点击这里上传. </strong></br>",
                dictFallbackMessage: "浏览器不受支持",
                dictFileTooBig: "文件过大上传文件最大支持.",
                dictMaxFilesExceeded: "您最多只能上传1个文件！",
                dictResponseError: '文件上传失败!',
                // acceptedFiles: ".jpg,.gif,.png,.jpeg", //上传的类型
                // dictInvalidFileType: "文件类型只能是*.jpg,*.gif,*.png,*.jpeg。",
                 acceptedFiles: ".pdf", //上传的类型
                 dictInvalidFileType: "文件类型只能是*.pdf。",
                dictCancelUpload: "取消",
                init: function () {
                    this.on("addedfile", function (file) {
                        //上传文件时触发的事件
                        console.log('1_addedfile');
                    });
                    this.on("queuecomplete", function (file) {
                        //上传完成后触发的方法
                        console.log('2_queuecomplete');
                        //console.log(data);
                    });
                    this.on("success", function (file, data) {
                        //上传成功触发的事件
                        console.log('success');
                        console.log(file);
                        console.log(data.message);
                        $("input[name='file_url']").attr('value', data.message);
                    });
                    this.on("error", function (file, data) {
                        //上传失败触发的事件
                        console.log('fail');
                        var message = '';
                        //lavarel框架有一个表单验证，
                        //对于ajax请求，JSON 响应会发送一个 422 HTTP 状态码，
                        //对应file.accepted的值是false，在这里捕捉表单验证的错误提示
                        if (file.accepted) {
                            $.each(data, function (key, val) {
                                message = message + val[0] + ';';
                            });
                            //控制器层面的错误提示，file.accepted = true的时候；
                            console.log(message);
                        }
                    });
                    this.on("removedfile", function (file) {
                        console.log(file);
                        console.log('removefile');
                        console.log(this.files);

                        console.log('removefile');
                        if("success" === file.status) {//检查当前文件是成功传输了，才进行删除处理
                            //删除文件时触发的方法
                            var file_name = $("input[name='file_url']").val();

                            if (file_name) {


                                $.ajax({
                                    type: 'POST',
                                    url: '/component/delete',
                                    data: {file_name: file_name},
                                    dataType: 'html',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    },
                                    success: function (data) {
                                        //var rep = JSON.parse(data);
                                           console.log(data);
                                            $("input[name='file_url']").attr('value', '');

                                    }
                                });
                            }
                        }
                        //angular.element(appElement).scope().file_id = 0;
                        //$("input[name='file_url']").attr('value','');
                        //document.querySelector('div .dz-default').style.display = 'block';
                    });
                    this.on('maxfilesreached',function (file) {
                        console.log('maxfilesreached');
                    });
                }

            };


            <!-- inline scripts related to this page -->
//
//            jQuery(function($){
//
//                try {
//                    Dropzone.autoDiscover = false;
//
//                    var myDropzone = new Dropzone('#dropzone', {
//                        previewTemplate: $('#preview-template').html(),
//
//                        thumbnailHeight: 120,
//                        thumbnailWidth: 120,
//                        maxFilesize: 30,
//
//                        addRemoveLinks : true,
//                        dictRemoveFile: 'Remove',
//
//                        dictDefaultMessage :
//                            '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
//                            <span class="smaller-80 grey">(or click)</span> <br /> \
//                            <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>'
//                        ,
//
//                        thumbnail: function(file, dataUrl) {
//                            if (file.previewElement) {
//                                $(file.previewElement).removeClass("dz-file-preview");
//                                var images = $(file.previewElement).find("[data-dz-thumbnail]").each(function() {
//                                    var thumbnailElement = this;
//                                    thumbnailElement.alt = file.name;
//                                    thumbnailElement.src = dataUrl;
//                                });
//                                setTimeout(function() { $(file.previewElement).addClass("dz-image-preview"); }, 1);
//                            }
//                        }
//
//                    });
//
//
//                    //simulating upload progress
//                    var minSteps = 6,
//                        maxSteps = 60,
//                        timeBetweenSteps = 100,
//                        bytesPerStep = 100000;
//
//                    myDropzone.uploadFiles = function(files) {
//                        var self = this;
//
//                        for (var i = 0; i < files.length; i++) {
//                            var file = files[i];
//                            totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));
//
//                            for (var step = 0; step < totalSteps; step++) {
//                                var duration = timeBetweenSteps * (step + 1);
//                                setTimeout(function(file, totalSteps, step) {
//                                    return function() {
//                                        file.upload = {
//                                            progress: 100 * (step + 1) / totalSteps,
//                                            total: file.size,
//                                            bytesSent: (step + 1) * file.size / totalSteps
//                                        };
//
//                                        self.emit('uploadprogress', file, file.upload.progress, file.upload.bytesSent);
//                                        if (file.upload.progress == 100) {
//                                            file.status = Dropzone.SUCCESS;
//                                            self.emit("success", file, 'success', null);
//                                            self.emit("complete", file);
//                                            self.processQueue();
//                                        }
//                                    };
//                                }(file, totalSteps, step), duration);
//                            }
//                        }
//                    }
//
//
//                    //remove dropzone instance when leaving this page in ajax mode
//                    $(document).one('ajaxloadstart.page', function(e) {
//                        try {
//                            myDropzone.destroy();
//                        } catch(e) {}
//                    });
//
//                } catch(e) {
//                    alert('Dropzone.js does not support older browsers!');
//                }
//
//            });



    </script>


@endsection