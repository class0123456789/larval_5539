<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>后台登录</title>
  <link rel="stylesheet" type="text/css" href="{{asset('/css/login.css')}}" />
  <style>
  body{height:100%;background:#16a085;overflow:hidden;}
  canvas{z-index:-1;position:absolute;}
  </style>
</head>
<body>
<form role="form" method="POST" action="{{url('/admin/login')}}">
  {{ csrf_field() }}
  <dl class="admin_login">
    <dt>
    <strong>站点后台管理系统</strong>
    <em>Management System</em>
    </dt>
    <dd class="user_icon">
    <input type="text" placeholder="用户名" name="email" class="login_txtbx" value="{{old('email')}}" />
    @if ($errors->has('email')))
    <dt class="error">
      <em>{{ $errors->first('email') }}</em>
    </dt>
    @endif
    </dd>
    <dd class="pwd_icon">
    <input type="password" placeholder="密码" name="password" class="login_txtbx" />
    @if ($errors->has('password'))
    <dt class="error">
      <em>{{ $errors->first('password') }}</em>
    </dt>
    @endif
    </dd>
    <dd>
    <input type="submit" value="立即登陆" class="submit_btn"/>
    </dd>
    <dd>
    <p>荆门农商银行后台管理系统</p>
    <p></p>
    </dd>
  </dl>
</form>
  <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('js/Particleground.js')}}"></script>
  <script>
  $(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
  dotColor: '#5cbdaa',
  lineColor: '#5cbdaa'
  });
  });
  </script>
</body>
</html>