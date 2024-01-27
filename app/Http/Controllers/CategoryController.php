<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryAddRequest;
use App\Http\Requests\CategoryEditRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        View::share('main_menu', 'Category');
        View::share('sub_menu', 'Category');
    }

    public function index()
    {
        return view('backend.pages.category.index');
    }

    public function fetchData()
    {
        return $this->categoryService->fetchData();
    }

    public function create()
    {
        return view('backend.pages.category.create');
    }
    public function validate_inputs(Request $request)
    {
        return $this->categoryService->validateInputs($request->all());
    }

    public function store(CategoryAddRequest $request)
    {
        try {
            $response = $this->categoryService->create($request->validated());
            if(!$response)
                return redirect('category')->with('error', 'Failed to add category');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('category')->with('success', 'Category saved successfully.');
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategory($id);
        return view('backend.pages.category.edit', compact('category'));
    }

    public function validate_name(Request $request, int $id)
    {
        return $this->categoryService->validateName($request->all(),$id);
    }

    public function update(CategoryEditRequest $request)
    {
        try {
            $category = $this->categoryService->update($request->validated());
            if(!$category)
                return redirect('category')->with('error', 'Failed to update category');
            return redirect('/category')->with('success', 'category updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        try{
            if($this->categoryService->delete($id))
                return redirect('category/')->with('success', "category deleted successfully.");
            return redirect('category/')->with('error', "category not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
