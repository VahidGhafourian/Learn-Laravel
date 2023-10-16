<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $data;

    /**
     * @param $data
     */
    public function __construct() {
        $this->data = [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'inventory' => 'required',
            'categories' => 'required',
            'attributes' => ['array', 'required'],
        ];
    }


    public function index()
    {
        $products = Product::query();

        if($keyword = request('search')){
            $products->where('title', 'LIKE', "%{$keyword}%")->orwhere('id', 'LIKE', "%{$keyword}%");
        }

        $products = $products->latest()->paginate(20);
        return view('admin.products.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validData = $request->validate($this->data);

        $product = auth()->user()->products()->create($validData);
        $product->categories()->sync($validData['categories']);

        $this->attachAttributesToProduct($validData['attributes'], $product);

        alert()->success('محصول مورد نظر با موفقیت ثبت شد', 'با تشکر');
        return redirect(route('admin.products.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validData = $request->validate($this->data);

        $product->update($validData);
        $product->categories()->sync($validData['categories']);

        $product->attributes()->detach();

        $this->attachAttributesToProduct($validData['attributes'], $product);

        alert()->success('محصول مورد نظر با موفقیت ویرایش شد', 'با تشکر');
        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        alert()->success('محصول مورد نظر با موفقیت حذف شد', 'با تشکر');
        return back();
    }

    /**
     * @param $attributes1
     * @param Product $product
     */
    protected function attachAttributesToProduct($attributes1, Product $product): void {
        $attributes = collect($attributes1);
        $attributes->each(function ($item) use ($product) {
            if (is_null($item['name']) || is_null($item['value'])) return;

            $attr = Attribute::firstOrCreate([
                'name' => $item['name'],
            ]);

            $attr_value = $attr->values()->firstOrCreate([
                'value' => $item['value']
            ]);

            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);
        });
    }
}
