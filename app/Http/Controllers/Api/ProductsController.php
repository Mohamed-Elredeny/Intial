<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Product::get();
        return $this->returnData(['records'], [$records],'Products Data');
    }
    public function productsCondition(Request $request){
        $state = $request->state;
        $price = $request->price;
        if($state == 'bigger'){
            $records = Product::where('price','>',$price)->get();
        }elseif($state  == 'smaller'){
            $records = Product::where('price','<',$price)->get();
        }elseif($state == 'equal'){
            $records = Product::where('price','=',$price)->get();
        }
        return $this->returnData(['records'], [$records],'Products Data');

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
            'image' => ['required'],
            'price' => ['required'],
            'description'=>['required'],
            'category_id'=>['required','exists:categories,id']
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        }
        $image = $this->uploadImages($request, 'products');
        Product::create([
            'name'=>$request->name,
            'image'=>$image,
            'price'=>$request->price,
            'description'=>$request->description,
            'active'=>$request->active,
            'category_id'=>$request->category_id
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
        $data = Product::find($id);
        return $this->returnData(['record'], [$data],'Product Data');
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
        $product = Product::find($id);

        if($request->image){
            $image = $this->uploadImages($request, 'image');
        }else{
            $image = $product->image;
        }
        $product->update([
            'name'=>$request->name,
            'image'=>$image,
            'price'=>$request->price,
            'description'=>$request->description,
            'active'=>$request->active,
            'category_id'=>$request->category_id
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
        $del = Product::destroy($id);
        if($del) {
            return $this->returnSuccessMessage('deleted Successfully', 200);
        }else{
            return $this->returnError(200,'There is data with this id');
        }
    }
}
