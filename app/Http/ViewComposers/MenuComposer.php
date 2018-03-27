<?php
namespace App\Http\ViewComposers;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view)
    {
        //$this->cacheClear();
        //$view->with('sidebarMenu', $this->getMenuList());
        $view->with('sidebarMenu', getMenuList());
    }
    

}
