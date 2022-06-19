<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrls;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ShortUrlsController extends Controller
{
   /**  
     * It is used to show the resource list.  
     *  
     * @return \Illuminate\Http\Response  
     */  
    public function index(Request $request)  
    {  
        if ($request->ajax()) {
            $data = ShortUrls::orderBy('id','desc');
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_at', function($row){
                        $date = date('d M Y',strtotime($row->created_at));
                        $time = date('h:i A',strtotime($row->created_at));
                        return  $date." ".$time;
                    })
                    ->editColumn('short_url', function($row){
                       return '<a href="'.route('shorten.link', $row->short_url).'" target="_blank">'.$row->short_url.'</a>';
                    })
                    ->addColumn('action', function($row){
                        $btn = '<a href="javascript:0;"><button class="btn btn-success copylink" link="'.$row->url.'"><i class="fa fa-clipboard" ></i></button></a>';
                        return $btn;
                    })
                    ->rawColumns(['action','short_url','created_at'])
                    ->make(true);
        } 
        return view('short_urls');  
    }  
       
    /**  
     * It is used to show the resource list.  
     *  
     * @return \Illuminate\Http\Response  
     */  
    public function store(Request $request)  
    {  
        if($request->ajax()){
            $rules = [
                'title' => 'required',
                'link' => 'required|url'
            ];
            $messages = [
                'link.required' => 'Please Provide a Valid URL!',
                'title.required' => 'Please Provide a Title.'
            ];
            $validator = Validator::make(request()->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
            try {
                $url = new ShortUrls();
                $url->title = $request->title;
                $url->url = $request->link;
                $url->short_url =  Str::random(10); 
                $url->save();
            } catch (\Throwable $th) {
                return response()->json(['status'=> false, 'message' => $th]);
            }

            return response()->json(['status'=> true,'message'=> 'Shorten Link Generated Successfully!']); 
        }
    }  
     
    /**  
     * It is used to show the resource list.  
     *  
     * @return \Illuminate\Http\Response  
     */  
    public function shortenLink($url)  
    {  
        $find = ShortUrls::where('short_url', $url)->first();  
        return redirect($find->url);  
    }  
}

