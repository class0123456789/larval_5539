<?php

namespace App\Http\Controllers\Component;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Response;
use Response;

class UploadController extends Controller
{
    public function  upload(Request $request)
    {

        $upload = $request->file;
        //dd ($upload);
        if($upload->isValid()){

//1

              $clientName = $upload->getClientOriginalName();//得到原文件名称
//2            $path = $upload->move('attachment',$clientName);

            //$tmpName = $upload->getFileName(); // 缓存在tmp文件夹中的文件名 例如
            $extension = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
            //dd($extension,$clientName);

            //$path = $upload->store(date('ym'),'attachment');
            $path = $upload->store('uploadfile/'.$extension,'public');
            $url =  Storage::url($path); //返回路径
            return ['status'=>1,'message'=>$url];
            //return ['status'=>1,'message'=>asset($url)];
            //return ['status'=>1,'message'=>asset($path)];
            //$path = Storage::disk('public')->putFile(date('Y/m'), $upload);
           // $path = $upload->store(date('ym'),'public');
           // return ['status'=>1,'message'=>asset($path)];

        }
        return ['status'=>0,'message'=>'上传文件大小不能超过2MB!'];
    }

    public function filesLists()
    {
        return ['data'=>[],'page'=>''];
    }

    public function  delete(Request $request)
    {
        //dd($request->all());
        $deletefile = $request->all(['file_name']);
       // dd ($delete);
        if($deletefile){

            //$clientName = $upload->getClientOriginalName();//得到原文件名称

            //$tmpName = $upload->getFileName(); // 缓存在tmp文件夹中的文件名 例如
            //$path = $upload->store(date('ym'),'attachment');
            //$path = $upload->move('attachment',$clientName);
            Storage::disk('public')->delete($deletefile);//删除指定磁盘中的文件
            return ['status'=>1,'message'=>'已删除'];
            //$path = Storage::disk('public')->putFile(date('Y/m'), $upload);
            // $path = $upload->store(date('ym'),'public');
            // return ['status'=>1,'message'=>asset($path)];

        }
        return ['status'=>0,'message'=>'删除文件失败!'];
    }

    //文件下载
    public function downloadfile(Request $request)
    {
        $file_url = $request->file_url;

        //if ($file_url){
            //$filePath= storage_path().str_replace('/storage','/app/public',$file_url);
            $filePath = public_path() . $file_url;
            if(!file_exists($filePath)){
                return response('没有找到文件');
            }
            $file_name = basename($file_url);
        //$filePath= public_path() ."/".$file_name;
        //dump($file_url,$filePath ,public_path(),storage_path());
        //dd($file_url,storage_path() ,$filePath);

        return response()->download($filePath, $file_name);
        //return response()->download($filePath, $file_name)->deleteFileAfterSend(true);//下载完成后删除
        //}
//        [
//
//            "Content-Type"=>application/vnd.ms-excel",
//            "Content-Disposition"=>"attachment;filename=".$file_name,
//            "Cache-Control"=>"max-age=0",
//
//        ]
        //return response()->download(public_path()."/hello_world.xlsx","hello_world.xlsx");
        // $file = public_path().'/test.xls';
       // $response->headers->set("Pragma", "No-cache");
       // $response->headers->set("Cache-Control", "No-cache");
       // $response->headers->set("Expires", 0);
        //response.setHeader("Cache-Control", "No-cache");
        //response.setDateHeader("Expires", 0);

      //  $response->headers->set('Access-Control-Allow-Origin' , '*');
      //  $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
      //  $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
      //  $response->headers->set("Content-Disposition","attachment;filename=".$file_name);
       // return $response->download($file_url);
        //return $response;
    }
}


