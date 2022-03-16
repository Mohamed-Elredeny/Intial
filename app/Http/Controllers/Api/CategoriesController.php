<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $state = $request->header('state');
        if($state == 1){
            $records = Category::where('active',1)->get();
        }elseif($state == 0){
            $records = Category::where('active',0)->get();

        }else{
            $records = Category::get();
        }
        return $this->returnData(['records'], [$records],'Categories Data');
    }
    public function control(){
        return 1;
    }
    public function productsCondition(Request $request){
        $categories = Category::get();
        $records = [];
        $state = $request->state;
        $price = $request->price;
        foreach($categories as $record){
            $products = $record->products;
            $total = 0;
            foreach($products  as $pro){
                $total += $pro->price;
            }
            $record->total_price = $total;
            if($state == 'smaller'){
                if($total < $price){
                    $records []  = $record;
                }
            }elseif($state == 'bigger'){
                if($total > $price){
                    $records []  = $record;
                }
            }elseif($state == 'zero'){
                if(count($products) == 0){
                    $records [] = $record;
                }
            }elseif($state == 'all'){
                $records [] = $record;
            }

            }
        return $this->returnData(['records'], [$records],'Categories Data');
    }
    public function viewWithState(Request $request){
        $state = $request->state;
        switch($request->type){
            case 'products':
               $products = Product::where('active',$state)->get();
                return $this->returnData(['products'],[$products], 'Products Data');
            case 'categories':
                $categories = Category::where('active',$state)->get();
                return $this->returnData(['categories'],[$categories], 'Categories Data');
            default:
                return $this->returnError(200, 'Wrong inputs');


        }
    }
    public function allActive(){
        $categories = Category::where('active',1)->get();
        foreach($categories as $cat){
            $active_products = [];
            foreach($cat->products as $pro){
                if($pro->active == 1){
                    $active_products [] = $pro;
                }
            }
            unset($cat->products);
            $cat->products = $active_products;
        }

        return $categories;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:2', 'max:60', 'not_regex:/([%\$#\*<>]+)/'],
            'active' => ['required', 'boolean'],
            'image' => ['required', 'image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        }
        $image = $this->uploadImage($request, 'image');
        Category::create([
            'name'=>$request->name,
            'active'=>$request->active,
            'image' => $image
        ]);
        return $this->returnSuccessMessage('Added Successfully',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Category::find($id);
        return $this->returnData(['record'], [$data],'Categories Data');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if($request->image){
         $image = $this->uploadImage($request, 'image');
        }else{
            $image = $category->image;
        }
        $category->update([
            'name'=>$request->name,
            'active'=>$request->active,
            'image' => $image
        ]);
        return $this->returnSuccessMessage('updated Successfully',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Category::destroy($id);
        if($del) {
            return $this->returnSuccessMessage('deleted Successfully', 200);
        }else{
            return $this->returnError(200,'There is data with this id');
        }
    }
}
