<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class SiteController extends Controller
{
    //
    public function index(){
        return view('home-page'); 
    }
    public function page(Request $page){
        $pageValue=Page::where('id',$page->id)->first();
        // dd($pageValue[0]);
        if($pageValue){
            return view('pages', compact('pageValue'));
        }else{
            return '404';
        }
         
    }
}
