<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">


<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">角色查看</h4>
</div>
<div class="modal-body">
    <form class="form-horizontal">
                <div class="hr-line-dashed no-margins"></div>
              <div class="form-group">
                <label class="col-sm-12 control-label">文件编号</label>
                <div class="col-sm-12">
                    <p  class="form-control-static"> {{$financialapproval->file_no}} </p>

                </div>
              </div>
                <div class="hr-line-dashed no-margins"></div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文件编号</label>
                <div class="col-sm-8">
                    <p class="form-control-static" >{{$financialapproval->file_url}} </p>

                </div>
              </div>


         <div class="form-group">
             <div class="col-sm-12">



                    <div class="text-center pdf-toolbar">

                        <div class="btn-group">
                            <button id="prev" class="btn btn-white"><i class="fa fa-long-arrow-left"></i> <span class="hidden-xs">上页</span></button>
                            <button id="next" class="btn btn-white"><i class="fa fa-long-arrow-right"></i> <span class="hidden-xs">下页</span></button>
                            <button id="zoomin" class="btn btn-white"><i class="fa fa-search-plus"></i> <span class="hidden-xs">放大</span></button>
                            <button id="zoomout" class="btn btn-white"><i class="fa fa-search-minus"></i> <span class="hidden-xs">缩小</span> </button>
                            <button id="zoomfit" class="btn btn-white"> 100%</button>
                            <span class="btn btn-white hidden-xs">页码: </span>

                            <div class="input-group">
                                <input type="text" class="form-control" id="page_num">

                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-white" id="page_count">/ 22</button>
                                </div>
                            </div>

                        </div>
                    </div>
             </div>
             <div class="col-sm-12">

                    <div class="text-center m-t-md">
                        <canvas id="the-canvas" class="pdfcanvas border-left-right border-top-bottom b-r-md"></canvas>
                    </div>
             </div>

         </div>
</form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
</div>
<script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script type="text/javascript" src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script  type="text/javascript" src="/js/inspinia.js"></script>
  <script type="text/javascript" src="/js/plugins/pdfjs/pdf.js"></script>
<script type="text/javascript" src="/js/plugins/pdfjs/pdf.worker.js"></script>

  <script type="text/javascript">
      $(function(){


      //
      // If absolute URL from the remote server is provided, configure the CORS
      // header on that server.
      //
      //var url = './pdf/example.pdf';

      var url = "/storage/uploadfile/example.pdf";
      console.log(url);



      var pdfDoc = null,
          pageNum = 1,
          pageRendering = false,
          pageNumPending = null,
          scale = 1.5,
          zoomRange = 0.25,
          canvas = document.getElementById('the-canvas'),
          ctx = canvas.getContext('2d');
          PDFJS.workerSrc = '/js/plugins/pdfjs/pdf.worker.js';//加载核心库

      /**
       * Get page info from document, resize canvas accordingly, and render page.
       * @param num Page number.
       */
      function renderPage(num, scale) {
          pageRendering = true;
          // Using promise to fetch the page
          pdfDoc.getPage(num).then(function(page) {
              var viewport = page.getViewport(scale);
              canvas.height = viewport.height;
              canvas.width = viewport.width;

              // Render PDF page into canvas context
              var renderContext = {
                  canvasContext: ctx,
                  viewport: viewport
              };
              var renderTask = page.render(renderContext);

              // Wait for rendering to finish
              renderTask.promise.then(function () {
                  pageRendering = false;
                  if (pageNumPending !== null) {
                      // New page rendering is pending
                      renderPage(pageNumPending);
                      pageNumPending = null;
                  }
              });
          });

          // Update page counters
          document.getElementById('page_num').value = num;
      }

      /**
       * If another page rendering in progress, waits until the rendering is
       * finised. Otherwise, executes rendering immediately.
       */
      function queueRenderPage(num) {
          if (pageRendering) {
              pageNumPending = num;
          } else {
              renderPage(num,scale);
          }
      }

      /**
       * Displays previous page.
       */
      function onPrevPage() {
          if (pageNum <= 1) {
              return;
          }
          pageNum--;
          var scale = pdfDoc.scale;
          queueRenderPage(pageNum, scale);
      }
      document.getElementById('prev').addEventListener('click', onPrevPage);

      /**
       * Displays next page.
       */
      function onNextPage() {
          if (pageNum >= pdfDoc.numPages) {
              return;
          }
          pageNum++;
          var scale = pdfDoc.scale;
          queueRenderPage(pageNum, scale);
      }
      document.getElementById('next').addEventListener('click', onNextPage);

      /**
       * Zoom in page.
       */
      function onZoomIn() {
          if (scale >= pdfDoc.scale) {
              return;
          }
          scale += zoomRange;
          var num = pageNum;
          renderPage(num, scale)
      }
      document.getElementById('zoomin').addEventListener('click', onZoomIn);

      /**
       * Zoom out page.
       */
      function onZoomOut() {
          if (scale >= pdfDoc.scale) {
              return;
          }
          scale -= zoomRange;
          var num = pageNum;
          queueRenderPage(num, scale);
      }
      document.getElementById('zoomout').addEventListener('click', onZoomOut);

      /**
       * Zoom fit page.
       */
      function onZoomFit() {
          if (scale >= pdfDoc.scale) {
              return;
          }
          scale = 1;
          var num = pageNum;
          queueRenderPage(num, scale);
      }
      document.getElementById('zoomfit').addEventListener('click', onZoomFit);


      /**
       * Asynchronously downloads PDF.
       */
      PDFJS.getDocument(url).then(function (pdfDoc_) {
          pdfDoc = pdfDoc_;
          var documentPagesNumber = pdfDoc.numPages;
          document.getElementById('page_count').textContent = '/ ' + documentPagesNumber;

          $('#page_num').on('change', function() {
              var pageNumber = Number($(this).val());

              if(pageNumber > 0 && pageNumber <= documentPagesNumber) {
                  queueRenderPage(pageNumber, scale);
              }

          });

          // Initial/first page rendering
          renderPage(pageNum, scale);
      });


      });
  </script>

