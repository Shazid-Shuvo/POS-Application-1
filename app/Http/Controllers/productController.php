<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class productController extends Controller
{
   function productPage(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
   {
       return view('auth.dashboard.product-page');
   }

   function createProduct(Request $request)
{
    $user_id=$request->header('id');

    // Prepare File Name & Path
    $img=$request->file('image');

    $t=time();
    $file_name=$img->getClientOriginalName();
    $img_name="{$user_id}-{$t}-{$file_name}";
    $img_url="uploads/{$img_name}";


    // Upload File
    $img->move(public_path('uploads'),$img_name);


    // Save To Database
    return Product::create([
        'name'=>$request->input('name'),
        'price'=>$request->input('price'),
        'unit'=>$request->input('unit'),
        'quantity'=>$request->input('quantity'),
        'image'=>$img_url,
        'category'=>$request->input('category'),
        'user_id'=>$user_id
    ]);
}

    function deleteProduct(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        $filePath=$request->input('file_path');
        File::delete($filePath);
        return Product::where('id',$product_id)->where('user_id',$user_id)->delete();

    }


    function productByID(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');
        return Product::where('id',$product_id)->where('user_id',$user_id)->first();
    }

    function productList(Request $request)
    {
        $user_id=$request->header('id');
        return Product::where('user_id',$user_id)->get();
    }


    function updateProduct(Request $request)
    {
        $user_id=$request->header('id');
        $product_id=$request->input('id');

        if ($request->hasFile('image')) {

            // Upload New File
            $img=$request->file('image');
            $t=time();
            $file_name=$img->getClientOriginalName();
            $img_name="{$user_id}-{$t}-{$file_name}";
            $img_url="uploads/{$img_name}";
            $img->move(public_path('uploads'),$img_name);

            // Delete Old File
            $filePath=$request->input('file_path');
            File::delete($filePath);

            // Update Product

            return Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'quantity'=>$request->input('quantity'),
                'image'=>$img_url,
                'category'=>$request->input('category')
            ]);

        }

        else {
            return Product::where('id',$product_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'price'=>$request->input('price'),
                'unit'=>$request->input('unit'),
                'quantity'=>$request->input('quantity'),
                'category'=>$request->input('category'),
            ]);
        }
    }

    public function updateProductQuantity(Request $request) {
        $product = Product::find($request->id);
        if ($product) {
            $product->quantity -= $request->quantity_sold;

            if ($product->quantity < 0) {
                return response()->json(['status' => 'error', 'message' => 'Not enough stock available'], 400);
            }

            $product->save();
            return response()->json(1);
        }

        return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
    }

}
