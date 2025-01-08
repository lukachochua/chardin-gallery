<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::defaultOrder()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rootCategories = Category::whereIsRoot()->with('children')->get();
        return view('admin.categories.create', compact('rootCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category = $this->categoryService->create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        // Add the "No Parent" option
        $categories = ['' => 'No Parent (Make Root Category)'] + $categories;

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->update($category, $request->validated());
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $this->categoryService->delete($category);
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function getChildren($parent_id)
    {
        $parentCategory = Category::findOrFail($parent_id);

        $children = $parentCategory->children;

        return response()->json($children);
    }

    public function rebuildTree()
    {
        try {
            Category::fixTree();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category tree has been rebuilt successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Failed to rebuild the category tree. Please try again.');
        }
    }
}
