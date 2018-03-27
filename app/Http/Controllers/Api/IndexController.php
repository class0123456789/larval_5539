<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApiUser;
use App\Http\Resources\ApiUserCollection;

class IndexController extends ApiController
{
    public function index(){

        //return $this->message('请求成功');
        return ApiUserCollection::collection(ApiUser::paginate(Input::get('limit') ?: 20));
    }
}
