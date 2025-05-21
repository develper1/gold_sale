<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index(){
        $products=Product::latest()->with("images")->get();

        return view('admin.products.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $product=null;

        return view("admin.products.add_edit",compact("product"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "product_cat"=>"required|string",
            "product_brand"=>"nullable|string",
            "product_tags"=>"nullable|string",
            "product_sku"=>"nullable|string",
            "inventory_manage"=>"nullable|string",
            "stock_available"=>"nullable|string",
            "up_sells"=>"nullable|string",
            "cross_sells"=>"nullable|string",
            "related_products"=>"nullable|string",
            "sticky_id"=>"nullable|string",
            "description"=>"nullable|string",
            // "show_ingrediants"=>"nullable",
            "product_type"=>"required|string",
            'billing_model_id'=>'required',
            "straight_sale_multi_price"=>"nullable",
            "straight_sale_prices"=>"nullable",
            "max_quantity_enable"=>"nullable",
            "custom_checkout_enable"=>"nullable",
            "max_quantity"=>"nullable",
            // "size_option"=>"nullable",
            // "image"=>"required|image",
            'images' => 'required|array',
            'images.*' => 'required|json',
            "active"=>"nullable",
            "advertising_company_id"=>"nullable",
            "digitalFile" => "nullable|max:1024000",
        ]);
    }

}
