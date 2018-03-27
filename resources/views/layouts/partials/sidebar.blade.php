@inject('menuPresenter','App\Repositories\Presenters\MenuPresenter')

<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
      <li class="nav-header text-center">
          <div class="dropdown profile-element">
              
            <h2 class="text-info"><i class="icon iconfont icon-ruralcredit"></i></h2>
            <h4 style="color:whitesmoke"><strong class="font-bold">荆门农商银行</strong></h4>
          </div>
          <div class="logo-element">
              <i class="icon iconfont icon-ruralcredit "></i>
          </div>
      </li>
        
      
      {!!$menuPresenter->sidebarMenuList($sidebarMenu)!!}
    </ul>
  </div>
</nav>