<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Http\Requests\CategoryAddRequest;
use App\Http\Requests\CategoryEditRequest;
use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductEditRequest;
use App\Http\Requests\ProductImportRequest;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Excel;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        View::share('main_menu', 'Product');
        View::share('sub_menu', 'Product');
    }

    public function index()
    {
        return view('backend.pages.product.index');
    }

    public function fetchData()
    {
        return $this->productService->fetchData();
    }

    public function create()
    {
        $category = $this->productService->getCategories();
        return view('backend.pages.product.create', compact('category'));
    }

    public function store(ProductAddRequest $request)
    {
        try {
            $response = $this->productService->create($request->validated());
            if(!$response)
                return redirect('product')->with('error', 'Failed to add product');
            return redirect('product')->with('success', 'product saved successfully.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $product = $this->productService->getProduct($id);
        $category = $this->productService->getCategories();
        return view('backend.pages.product.edit', compact('category','product'));
    }


    public function update(ProductEditRequest $request)
    {
        try {
            $category = $this->productService->update($request->validated());
            if(!$category)
                return redirect('product')->with('error', 'Failed to update product');
            return redirect('/product')->with('success', 'product updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function viewImport()
    {
        return view('backend.pages.product.viewImport');
    }

    public function import( ProductImportRequest $request)
    {
        try{
            $product = $this->productService->import($request->all());
            if($product)
                return redirect('product/')->with('success', "product Updated");
            return redirect()->back()->with('error', "Sorry! product could not be updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function export()
    {
        $products = $this->productService->getAllProducts();
        $filename = 'products.csv';
        return Excel::download(new ProductExport($products), $filename);
    }

    public function delete($id)
    {
        try{
            if($this->productService->delete($id))
                return redirect('product/')->with('success', "product deleted successfully.");
            return redirect('product/')->with('error', "product not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
