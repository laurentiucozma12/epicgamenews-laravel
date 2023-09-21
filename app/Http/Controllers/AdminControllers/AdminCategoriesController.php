<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Category;

class AdminCategoriesController extends Controller
{
    private $rules = [
        'name' => 'required|min:2|max:30',
        'slug' => 'required|unique:others,slug',
        'thumbnail' => 'required|image|dimensions:max_width=1920,max_height=1080',
    ];
    
    public function index()
    {
        $categories = Category::with('user')->orderBy('id', 'DESC')->paginate(100);
        
        return view('admin_dashboard.categories.index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('admin_dashboard.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $validated['user_id'] = auth()->id();
        $category = Category::create($validated);
        
        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = $thumbnail->getClientOriginalName();
            $file_extension = $thumbnail->getClientOriginalExtension();
            $path = $thumbnail->store('categories', 'public');
    
            $category->image()->create([
                'name' => $filename,
                'extension' => $file_extension,
                'path' => $path,
            ]);
        }

        return redirect()->route('admin.categories.create')->with('success', 'Category has been Created');
    }

    public function show(Category $category)
    {
        $posts = $category->posts()->latest()->paginate(100);
        
        return view('admin_dashboard.categories.show', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function edit(Category $category)
    {
        return view('admin_dashboard.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $this->rules['thumbnail'] = 'nullable|image|dimensions:max_width=1920,max_height=1080';
        $this->rules['slug'] = ['required', Rule::unique('categories')->ignore($category)];
        $validated = $request->validate($this->rules);
        $category->update($validated);
        
        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = $thumbnail->getClientOriginalName();
            $file_extension = $thumbnail->getClientOriginalExtension();
            $path = $thumbnail->store('images', 'public');

            $category->image()->update([
                'name' => $filename,
                'extension' => $file_extension,
                'path' => $path,
            ]);
        }

        return redirect()->route('admin.categories.edit', $category)->with('success', 'Category has been Updated');
    }

    public function destroy(Category $category)
    {
        $default_category_id = Category::where('name', 'uncategorized')->first()->id;

        if ($category->name === 'uncategorized')
            abort('404');

        $category->posts()->update(['category_id' => $default_category_id]);

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category has been Deleted');
    }
}
