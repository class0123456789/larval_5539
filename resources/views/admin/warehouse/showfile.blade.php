<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>查看文件</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="ibox-content">
                    <div  class="form-horizontal">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">文件编号</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="file_no" readonly value="{{$financialapproval->file_no}}" placeholder="文件编号">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">文件编号</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" readonly name="file_url" value="{{$financialapproval->file_url}}" placeholder="文件名称">

                            </div>
                        </div>

                        <div class="text-center pdf-toolbar">

                            <div class="btn-group">
                                <button id="prev" class="btn btn-white prev"><i class="fa fa-long-arrow-left"></i> <span class="hidden-xs">上页</span></button>
                                <button id="next" class="btn btn-white next"><i class="fa fa-long-arrow-right"></i> <span class="hidden-xs">下页</span></button>
                                <button id="zoomin" class="btn btn-white zoomin"><i class="fa fa-search-plus"></i> <span class="hidden-xs">放大</span></button>
                                <button id="zoomout" class="btn btn-white zoomout" ><i class="fa fa-search-minus"></i> <span class="hidden-xs">缩小</span> </button>
                                <button id="zoomfit" class="btn btn-white zoomfit"> 100%</button>
                                <span class="btn btn-white hidden-xs">页码: </span>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="page_num">

                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-white" id="page_count">/22</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-center m-t-md">
                            <canvas id="the-canvas" class="pdfcanvas border-left-right border-top-bottom b-r-md"></canvas>

                        </div>
                        <div class="text-center pdf-toolbar">
                        <div class="btn-group">
                            <button id="prev1" class="btn btn-white"><i class="fa fa-long-arrow-left"></i> <span class="hidden-xs">上页</span></button>
                            <button id="next1" class="btn btn-white"><i class="fa fa-long-arrow-right"></i> <span class="hidden-xs">下页</span></button>
                            <button id="zoomin1" class="btn btn-white"><i class="fa fa-search-plus"></i> <span class="hidden-xs">放大</span></button>
                            <button id="zoomout1" class="btn btn-white" ><i class="fa fa-search-minus"></i> <span class="hidden-xs">缩小</span> </button>
                            <button id="zoomfit1" class="btn btn-white"> 100%</button>
                            <span class="btn btn-white hidden-xs">页码: </span>

                            <div class="input-group">
                                <input type="text" class="form-control" id="page_num1">

                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-white" id="page_count1">/22</button>
                                </div>
                            </div>
                        </div>
                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>


</div>

</body>

<!-- Mainly scripts -->
<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>

<script src="/js/plugins/pdfjs/pdf.js"></script>

<script src="/js/plugins/pace/pace.min.js"></script>
<script src="/js/plugins/wow/wow.min.js"></script>


<script>

    $(document).ready(function () {

        var url = "{{$financialapproval->file_url ? $financialapproval->file_url:'/storage/uploadfile/example.pdf'}}";
        console.log(url);



        var pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            zoomRange = 0.25,
            canvas = document.getElementById('the-canvas'),
            ctx = canvas.getContext('2d');

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
            document.getElementById('page_num1').value = num;
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
        document.getElementById('prev1').addEventListener('click', onPrevPage);
        //document.getElementByClass
        //document.getElementByClass('prev').addEventListener('click', onPrevPage);

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
        document.getElementById('next1').addEventListener('click', onNextPage);
        //document.getElementByClass('next').addEventListener('click', onNextPage);

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
        document.getElementById('zoomin1').addEventListener('click', onZoomIn);
        //document.getElementByClass('zoomin').addEventListener('click', onZoomIn);

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
        document.getElementById('zoomout1').addEventListener('click', onZoomOut);
        //document.getElementByClass('zoomout').addEventListener('click', onZoomOut);

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
        document.getElementById('zoomfit1').addEventListener('click', onZoomFit);
        //document.getElementByClass('zoomfit').addEventListener('click', onZoomFit);


        /**
         * Asynchronously downloads PDF.
         */
        PDFJS.getDocument(url).then(function (pdfDoc_) {
            pdfDoc = pdfDoc_;
            var documentPagesNumber = pdfDoc.numPages;
            document.getElementById('page_count').textContent = '/ ' + documentPagesNumber;
            document.getElementById('page_count1').textContent = '/ ' + documentPagesNumber;

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


</html>
