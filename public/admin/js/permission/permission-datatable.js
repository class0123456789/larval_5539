function getBaseUrl() {
    var currentUrl = window.location.toString().split('/');
    var baseUrl = currentUrl[0];
    for (i = 1; i < currentUrl.length - 2; ++i) {
        baseUrl += '/' + currentUrl[i];
    }
    return baseUrl;
}
var TableDatatablesAjax = function() {
  var datatableAjax = function(){
      var cid = $('#cid').attr('attr');
    dt = $('.dataTablesAjax');
		ajax_datatable = dt.DataTable({
			"processing": true,
      "serverSide": true,
      "searching" : true,
      "searchDelay": 800,
      "search": {
        "regex": true
      },
      "ajax": {
        'url' : '/admin/permission/index',
        
         data: function (d) {
                            d.cid = cid;
                        },
         headers: {
           'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
      },
      "pagingType": "full_numbers",
      "orderCellsTop": true,
      "dom" : '<"html5buttons"B>lTfgitp',
      "buttons": [
        {extend: 'copy',title: 'permission'},
        {extend: 'csv',title: 'permission'},
        {extend: 'excel', title: 'permission'},
        {extend: 'pdf', title: 'permission'},
        {extend: 'print',
         customize: function (win){
            $(win.document.body).addClass('white-bg');
            $(win.document.body).css('font-size', '10px');
            $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');
          }
        }
      ],
      "columns": [
        {
        	"data": "id",
        	"name" : "id",
      	},
        {
        	"data": "name",
        	"name" : "name",
        	"orderable" : false,
        },
        {
        	"data": "label",
        	"name": "label",
        	"orderable" : false,
        },
        {
          "data": "description",
          "name": "description",
          "orderable" : false,
        },
        {
        	"data": "created_at",
        	"name": "created_at",
        	"orderable" : true,
        },
        {
        	"data": "updated_at",
        	"name": "updated_at",
        	"orderable" : true,
        },
        {
          "data": "cid",
          "name": "cid",
          "type": "html",
          "orderable" : false,
        },
    	],

      "drawCallback": function( settings ) {
        ajax_datatable.$('.tooltips').tooltip( {
          placement : 'top',
          html : true
        });
      },
      "language": {
        url: '/admin/i18n'
      }
    });
  };
	return {
		init : datatableAjax
	}
}();
$(function () {
  TableDatatablesAjax.init();
});
