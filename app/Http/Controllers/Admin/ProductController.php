<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\SubCategory;


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
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('admin.products.add_edit', compact('categories', 'subCategories','product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($request->all());
        // die();
        $request->validate([
            "name"=>"required|string",
            "slug"=>"required|string",
            "product_type"=>"required|string",
            "pricing_type"=>"required|string",
            "inventory_type"=>"required|string",
            'images' => 'required|array',
            'images.*' => 'required|json',
        ]);
        
        if ($request->has('images')) {
            $images = $request->input('images');
            $decodedImages = array_map(function($image) {
                return json_decode($image, true);
            }, $images);
            

            $imagePath = "product-images";

            $lastImage = end($decodedImages);
            $firstImagePath = $this->storeBase64Image($lastImage['data'], $lastImage['name'], $imagePath);

        }
      
        $product=  Product::create([
            "name"=>$request->name,
            "slug"=>$request->slug,
            "description"=>$request->description,
            "product_type"=>$request->product_type,
            "pricing_type"=>$request->pricing_type,
            "fixed_price"=> $request->fixed_price !='' ? $request->fixed_price : 0,
            "blanket_markup_percentage"=> $request->blanket_markup_percentage !='' ? $request->blanket_markup_percentage : 0,
            "use_override_markup"=>$request->use_override_markup == "on" ? 1 : 0,
            "override_markup_percentage"=> $request->override_markup_percentage !='' ? $request->override_markup_percentage : 0,
            "inventory_type"=>$request->inventory_type,
            "quantity_available"=>$request->quantity_available,
            "low_inventory_threshold"=>$request->low_inventory_threshold,
            "is_active"=>1,
            "image_path"=>$firstImagePath,
            "category_id" => $request->category_id,
            "sub_category_id" => $request->sub_category_id,
        ]);

        // Store the remaining images 
        foreach ($decodedImages as $image) {
            $storedImagePath = $this->storeBase64Image($image['data'], $image['name'], $imagePath);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $storedImagePath,
            ]);
        }
        
        return redirect()->route("admin.products.index")->with("success","Product Created successfully");

    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with(['images'])->findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        return view('admin.products.add_edit', compact('product', 'categories', 'subCategories'));
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
        $request->validate([
            "name"=>"required|string",
            "slug"=>"required|string",
            "product_type"=>"required|string",
            "pricing_type"=>"required|string",
            "inventory_type"=>"required|string",
            'images' => 'required|array',
            'images.*' => 'required|json',
        ]);
        $product=Product::findOrFail($id);

        $imagePath = "product-images";

            if ($request->has('images')) {
                ProductImage::where('product_id', $product->id)->delete();
            }

            if ($request->has('images')) {
                $images = $request->input('images');
                $decodedImages = array_map(function ($image) {
                    return json_decode($image, true);
                }, $images);

                $lastImage = end($decodedImages);
                $lastImagePath = $this->storeBase64Image($lastImage['data'], $lastImage['name'], $imagePath);

                $product->update(['image_path' => $lastImagePath]);

                // Store the remaining images
                foreach ($decodedImages as $image) {
                    $storedImagePath = $this->storeBase64Image($image['data'], $image['name'], $imagePath);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $storedImagePath,
                        
                    ]);
                }
            }
            $product = $product->update([
                "name"=>$request->name,
                "slug"=>$request->slug,
                "description"=>$request->description,
                "product_type"=>$request->product_type,
                "pricing_type"=>$request->pricing_type,
                "fixed_price"=> $request->fixed_price !='' ? $request->fixed_price : 0,
                "blanket_markup_percentage"=> $request->blanket_markup_percentage !='' ? $request->blanket_markup_percentage : 0,
                "use_override_markup"=>$request->use_override_markup == "on" ? 1 : 0,
                "override_markup_percentage"=> $request->override_markup_percentage !='' ? $request->override_markup_percentage : 0,
                "inventory_type"=>$request->inventory_type,
                "quantity_available"=>$request->quantity_available,
                "low_inventory_threshold"=>$request->low_inventory_threshold,
                "is_active"=>1,
                "category_id" => $request->category_id,
                "sub_category_id" => $request->sub_category_id,
            ]);

            return redirect()->route("admin.products.index")->with("success","Product Updated successfully");
    }

    private function storeBase64Image($base64Image, $fileName, $path)
    {
        // Decode the base64 image
        $imageData = base64_decode($base64Image);
        $filePath = "{$path}/{$fileName}";

        Storage::disk('public')->put($filePath, $imageData);

        return $filePath;
    }
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $product->delete();
        return back()->with('success', 'Product deleted successfully!');
    }

    public function search(Request $request)
    {
        $search = $request->q;
        $products = Product::where('name', 'like', "%$search%")
            ->select('id', 'name')
            ->limit(20)
            ->get();

        return response()->json($products);
    }

}
