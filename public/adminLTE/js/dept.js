var DeptList = function() {
  var deptInit = function(){
    $('#nestable').nestable({
      "maxDepth":2
    });
    var dept = {
      box:'.deptBox',
      createDept:'.create_dept',
      close:'.close-link',
      createForm:'#createBox',
      middleBox:'.middle-box',
      createButton:'.createButton',
    };
    /**
     * 添加菜单
     * @author 晚黎
     * @date   2016-11-04T10:07:56+0800               [description]
     */
    $(dept.box).on('click',dept.createDept,function () {
      $.ajax({
        url:'/admin/institution/create',
        dataType:'html',
        success:function (htmlData) {
          $(dept.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          $(dept.box).append(htmlData);
        }
      });
    });

    $(dept.box).on('click',dept.close,function () {
      $('.formBox').removeClass('bounceIn').addClass('bounceOut').remove();
      $(dept.middleBox).removeClass('bounceOut').addClass('bounceIn').show();
    });

    $('.tooltips').tooltip();
    /**
     * 添加菜单
     * @author 晚黎
     * @date   2016-11-04T16:12:58+0800
     */
    $(dept.box).on('click','.createButton',function () {
      var l = $(this).ladda();
      var _item = $(this);
      var _form = $('#createForm');
      $.ajax({
        url:'/admin/institution',
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
            window.location.href = '/admin/institution';
          }, 1000);
        }
      }).fail(function(response) {
        if(response.status == 422){
          //console.log(response.errors)
          var data = $.parseJSON(response.responseText);
            //console.log(data.errors);
            var dataa = data.errors;
          //  var data = $.parseJSON(response.errors);
          var layerStr = "";
          for(var i in dataa){
            //console.log(dataa[i]);
            layerStr += "<div>"+dataa[i]+"</div>";
          }
          layer.msg(layerStr);
           // layer.msg('1111');
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
    $('#nestable').on('click','.editDept',function () {
      var _item = $(this);
      $.ajax({
        url:_item.attr('data-href'),
        dataType:'html',
        success:function (htmlData) {
          var box = $(dept.middleBox);
          if (box.is(':visible')) {
            $(dept.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(dept.box).append(htmlData);
        }
      });
    });
    /**
     * 修改菜单数据
     * @author 晚黎
     * @date   2016-11-04T16:51:00+0800
     */
    $(dept.box).on('click','.editButton',function () {
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
            window.location.href = '/admin/institution';
          }, 1000);
        }
      }).fail(function(response) {
        if(response.status == 422){
          var data = $.parseJSON(response.responseText);
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
          var box = $(dept.middleBox);
          if (box.is(':visible')) {
            $(dept.middleBox).removeClass('fadeInRightBig').addClass('bounceOut').hide();
          }else{
            var _createForm = $('.formBox');
            // 创建表单存在的情况下
            if (_createForm.length > 0) {
              _createForm.removeClass('bounceIn').addClass('bounceOut').remove();
            }
          }
          $(dept.box).append(htmlData);
        }
      });
    });
  };

  return {
    init : deptInit
  }
}();
$(function () {
  DeptList.init();
});