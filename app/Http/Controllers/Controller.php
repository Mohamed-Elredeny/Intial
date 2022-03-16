<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests,GeneralTrait;
    #region paginate
    public function paginate(Request $request,$array){
       if($request->header('limit') && $request->header('active')) {
           $limit = $request->header('limit');
           $active = $request->header('active');
       }else{
           return $this->returnError(200,'you have to enter limit and active in header active starts from 0');
       }
        $all_records = count($array);
        $pages = ceil($all_records / $limit);
        if ($active < $pages) {
            $paginate_response = $this->paginate_redo($array, $limit, $active);
            $count_paginate_response = count($this->paginate_redo($array, $limit, $active));
            $data = [
                'posts' => $paginate_response,
                'pages' => $pages,
                'page_records' => $count_paginate_response,
                'active' => intval($active),
                'count'=>$all_records
            ];
            return $this->returnData(['records'], [$data],'success');
        } else {
            $data = [
                'posts' => [],
                'pages' => $pages,
                'page_records' => 0,
                'active' => -1,
                'count'=>$all_records
            ];
            return $this->returnData(['records'], [$data],'error');


        }
    }
    public function paginate_redo($data, $limit, $page)
    {
        $count_records = count($data);
        if ($limit > $count_records) {
            $limit = $count_records;
        }
        $res = [];
        for ($i = 0 + ($limit * $page); $i < $limit + ($limit * $page); $i++) {
            if ($i > $count_records - 1) {
                break;
            }
            $res[] = $data[$i];
        }
        return $res;
    }
    #endregion
    #region
    public function uploadImage(Request $request,$filename)
    {

        $filename = strval($filename);
        if ($request->hasFile($filename)) {
            //  Let's do everything here
            $extension = $request->file($filename)->extension();
            $image = time() . '.' . $request->file($filename)->getClientOriginalExtension();
            $request->file($filename)->move(public_path('/assets/images'), $image);
            return $image;
        }
    }
    public function uploadImages(Request $request,$path)
    {
        if ($request->hasFile('image')) {

            $image_ext = ['jpg', 'png', 'jpeg'];

            $video_ext = ['mpeg', 'ogg', 'mp4', 'webm', '3gp', 'mov', 'flv', 'avi', 'wmv', 'ts'];

            $files = $request->file('image');

            $image = [];
            foreach ($files as $file) {

                $fileextension = $file->getClientOriginalExtension();


                $filename = $file->getClientOriginalName();
                $file_to_store = time() . '_' . explode('.', $filename)[0] . '_.' . $fileextension;

                $test = $file->move(public_path('assets/images/'. $path), $file_to_store);

                if ($test) {
                    $images [] = $file_to_store;
                }
            }
            $images = implode('|', $images);
            return $images;
        }

    }

    #endregion
}
