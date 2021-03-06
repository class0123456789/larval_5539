var DeviceClassList = function() {
  var deviceclassInit = function(){
    $('#nestable').nestable({
      "maxDepth":2
    });
    var deviceclass = {
      box:'.deviceclassBox',
      createDeviceClass:'.create_DeviceClass',
      close:'.close-link',
      createForm:'#createBox',
      middleBox:'.middle-box',
      createButton:'.createButton'
    };
    /**
     * 添加菜单
     * @author 晚黎
     * @date   2016-11-04T10:07:56+0800               [description]
     */
    $(deviceclass.box).on('click',deviceclass.createDeviceClass,function () {
        
      $.ajax({
        url:'/admin/deviceclass/create',
        dataType:'html',
        success:function (htmlData) {
          $(deviceclass.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          $(deviceclass.box).append(htmlData);
        }
      });
    });

    $(deviceclass.box).on('click',deviceclass.close,function () {
      $('.formBox').removeClass('bounceIn').addClass('bounceOut').remove();
      $(deviceclass.middleBox).removeClass('bounceOut').addClass('bounceIn').show();
    });

    $('.tooltips').tooltip();
    /**
     * 添加菜单
     * @author 晚黎
     * @date   2016-11-04T16:12:58+0800
     */
    $(deviceclass.box).on('click','.createButton',function () {
      var l = $(this).ladda();
      var _item = $(this);
      var _form = $('#createForm');
      $.ajax({
        url:'/admin/deviceclass',
        type:'post',
        dataType: 'json',
        data:_form.serializeArray(),
        headers : {
          'X-CSRF-TOKEN': $("input[name='_token']").val()
        },
        beforeSend : function(){
          l.ladda( 'start' );
          _item.attr('disabled','true');
        },
        success:function (response) {
           
          layer.msg(response.message);
          setTimeout(function(){
            window.location.href = '/admin/deviceclass';
          }, 2000);
        }
      }).fail(function(response) {
           // console.log(response.responseText);
        if(response.status === 422){
          var data = $.parseJSON(response.responseText);
          //console.log(data);
          var dataa = data.errors;
          var layerStr = "";
          for(var i in dataa){
            layerStr += "<div>"+dataa[i]+"</div>";
          }
          layer.msg(layerStr);
        }
      }).always(function () {
        l.ladda('stop');
        _item.removeAttr('disabled');
      });;
    });
    /**
     * 修改菜单表单
     * @author 晚黎
     * @date   2016-11-04T16:13:20+0800
     */
    $('#nestable').on('click','.editDeviceClass',function () {
      var _item = $(this);
      $.ajax({
        url:_item.attr('data-href'),
        dataType:'html',
        success:function (htmlData) {
          var box = $(deviceclass.middleBox);
          if (box.is(':visible')) {
            $(deviceclass.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(deviceclass.box).append(htmlData);
        }
      });
    });
    /**
     * 修改菜单数据
     * @author 晚黎
     * @date   2016-11-04T16:51:00+0800
     */
    $(deviceclass.box).on('click','.editButton',function () {
      var l = $(this).ladda();
      var _item = $(this);
      var _form = $('#editForm');

      $.ajax({
        url:_form.attr('action'),
        type:'post',
        dataType: 'json',
        data:_form.serializeArray(),
        headers : {
          'X-CSRF-TOKEN': $("input[name='_token']").val()
        },
        beforeSend : function(){
          l.ladda( 'start' );
          _item.attr('disabled','true');
        },
        success:function (response) {
          layer.msg(response.message);
          setTimeout(function(){
            window.location.href = '/admin/deviceclass';
          }, 2000);
        }
      }).fail(function(response) {

        if(response.status == 422){

          var data = $.parseJSON(response.responseText);
            var dataa = data.errors;
          var layerStr = "";
          for(var i in dataa ){
            console.log(dataa[i]);
            layerStr += "<div>"+dataa[i]+"</div>";
          }
          layer.msg(layerStr);
        }
      }).always(function (response) {
        l.ladda('stop');
        _item.removeAttr('disabled');
      });;
    });
    /**
     * 查看菜单详细信息
     * @author 晚黎
     * @date   2016-11-04
     */
    $('#nestable').on('click','.showInfo',function () {
      var _item = $(this);
      $.ajax({
        url:_item.attr('data-href'),
        dataType:'html',
        success:function (htmlData) {
          var box = $(deviceclass.middleBox);
          if (box.is(':visible')) {
            $(deviceclass.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(deviceclass.box).append(htmlData);
        }
      });
    });
  };

  return {
    init : deviceclassInit
  };
}();
$(function () {
  DeviceClassList.init();
});