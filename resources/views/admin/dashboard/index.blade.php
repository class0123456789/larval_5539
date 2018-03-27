@extends('layouts.admin')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-sm-4">
      <h2>这里是主标题</h2>
      <ol class="breadcrumb">
          <li>
              @if(haspermission("admin/dash"))
              <a href="/admin/dash">控制台</a>
              @endif
          </li>
          <li class="active">
              <strong>栏目</strong>
          </li>
      </ol>
  </div>
  <div class="col-sm-8">
      <div class="title-action">
          <a href="" class="btn btn-primary">操作</a>
      </div>
  </div>
</div>
<div class="wrapper wrapper-content">
    <div class="middle-box text-center animated fadeInRightBig">
        <h3 class="font-bold">这里放页面内容</h3>
        <div class="error-desc">
            您可以在这里创建任何网格布局和任何你想象的变化布局。:) 检查主控台和其他站点，它使用许多不同的布局。
            <br/><a href="/admin/dash" class="btn btn-primary m-t">主控台</a>
        </div>
    </div>
</div>
@endsection