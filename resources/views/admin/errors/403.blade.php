@extends('layouts.admin')
@section('content')



<div class="wrapper wrapper-content">
    <div class="middle-box text-center animated fadeInRightBig">
        <h3 class="font-bold">403错误</h3>
        <div class="error-desc">
            没有权限.此时你可以返回
            <br/>
            <a href="/admin/dash" class="btn btn-primary m-t">主控台</a>
            <a href="{{$previousUrl}}" class="btn btn-primary m-t"> 上一页 </a>
        </div>
    </div>
</div>
    <!-- /.error-page -->


@endsection
