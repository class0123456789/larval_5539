{{--@inject('menuPresenter','App\Presenters\Admin\MenuPresenter')--}}

<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header text-center">
          <div class="dropdown profile-element">
            <h1 class="text-info"><strong class="font-bold">Any</strong></h1>
          </div>
          <div class="logo-element">
            Any
          </div>
      </li>
      
      {{--{!!$menuPresenter->sidebarMenuList($sidebarMenu)!!}--}}
      <?php $comData=Request::get('comData_menu'); //dd($comData)?>
      
            <!-- Optionally, you can add icons to the links -->
            <li class=""><a href="/admin/index"><i class="fa fa-dashboard"></i> <span class="nav-label" >管理日志</span></a></li>
            <li class=""><a href="/admin/dash"><i class="fa fa-dashboard"></i> <span class="nav-label">主控台</span></a></li>
            
            
            @foreach($comData['top'] as $v)
                <li class="  @if(in_array($v['id'],$comData['openarr'])) active @endif">
                    <a href="#"><i class="fa {{ $v['icon'] }}"></i> <span class="nav-label">{{$v['label']}}</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="nav nav-second-level collapse">
                        @foreach($comData[$v['id']] as $vv)
                            <li @if(in_array($vv['id'],$comData['openarr'])) class="active" @endif><a href="{{URL::route($vv['name'])}}"><i class="fa fa-circle-o"></i>{{$vv['label']}}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
      
      
    </ul>
  </div>
</nav>