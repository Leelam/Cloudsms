<?php namespace Leelam\Cloudsms\Http\Controllers;


class CloudsmsController extends BaseController{

    public function index()
    {
        return view('cloudsms::cloudsmses.index');
    }

}