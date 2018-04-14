<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="_token" content="{{ csrf_token() }}"> 
  <title>Laravel5.5后台</title>
  <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="/css/iconfont/iconfont.css" />
  <link href="{{asset('/css/animate.css')}}" rel="stylesheet">
  @yield('css')
  <link href="{{asset('/css/style.css')}}" rel="stylesheet">
</head>
<body class="">
  <div id="wrapper">
    @include('layouts.partials.sidebar')

    <div id="page-wrapper" class="gray-bg">
      <div class="row border-bottom">
        <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
          <div class="navbar-header">
              <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

          </div>
          <ul class="nav navbar-top-links navbar-right">
              <li>

                  <i class="fa fa-bell"></i><span class="m-r-sm text-muted welcome-message label label-primary">Hi,{{getUser('admin')->name}}.</span>
              </li>

              <li>
                 @if(haspermission("admin/logout"))
                  @endif
                  <a href="/admin/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                      <i class="fa fa-sign-out"></i> 退出
                      <form id="logout-form" action="/admin/logout" method="POST" style="display: none;">{{ csrf_field() }}</form>
                  </a>

              </li>
          </ul>
        </nav>
      </div>
      @yield('content')
      <div class="footer">
          <div class="pull-right">
              10GB of <strong>250GB</strong> Free.
          </div>
          <div>
              <strong>Copyright</strong> Laravel5.5后台 &copy; http://www.jmnsh.me
          </div>
      </div>

    </div>
  </div>
  <script type="text/javascript" src="/js/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
  <script type="text/javascript" src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="/js/inspinia.js"></script>
  <script type="text/javascript" src="/js/plugins/pace/pace.min.js"></script>
  
  @yield('js')
</body>
</html>
