<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\ImageOptimizer\OptimizerChainFactory;

use App\Models\Category;
use App\Models\Platform;
use App\Models\Other;
use App\Models\Post;
use App\Models\Tag;

class AdminPostsController extends Controller
{
    private $rules = [
        'title' => 'required|max:200',
        'slug' => 'required|max:200',
        'excerpt' => 'required|max:1000',
        'category_id' => 'required|numeric',
        'platform_id' => 'required|numeric',
        'other_id' => 'required|numeric',
        'thumbnail' => 'required|file|dimensions:max_width=1800,max_height=900',
        'body' => 'required',
    ];

    public function index()
    {
        return view('admin_dashboard.posts.index', [
            'posts' => Post::latest()->with(['category', 'platform', 'other'])->get(),
        ]);
    }

    public function create()
    {
        return view('admin_dashboard.posts.create', [
            'categories' => Category::pluck('name', 'id'),
            'platforms' => Platform::pluck('name', 'id'),
            'others' => Other::pluck('name', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $validated['user_id'] = auth()->id();
        $post = Post::create($validated);

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = $thumbnail->getClientOriginalName();
            $file_extension = $thumbnail->getClientOriginalExtension();
            $path = $thumbnail->store('images', 'public');
    
            // Optimize and convert to WebP
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($path);
    
            // Create WebP version
            $webPPath = str_replace($file_extension, 'webp', $path);
            $optimizerChain->executeCommand("cwebp -q 80 $path -o $webPPath");
    
            $post->image()->create([
                'name' => $filename,
                'extension' => $file_extension,
                'path' => $path,
                'webp_path' => $webPPath, // Store the WebP path in your database
            ]);
        }

        $tags = explode(',', $request->input('tags'));
        $tags_ids = [];
        foreach($tags as $tag){
            $tag_ob = Tag::create(['name' => trim($tag)]);
            $tags_ids[] = $tag_ob->id;
        }
        
        if(count($tags_ids) > 0)
            $post->tags()->sync( $tags_ids );

        return redirect()->route('admin.posts.create')->with('success', 'Post has been created.');    
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Post $post)
    {
        $tags = '';
        foreach ($post->tags as $key => $tag)
        {
                $tags .= $tag->name;
                if ($key !== count($post->tags) - 1) 
                    $tags .= ', ';

        }

        return view('admin_dashboard.posts.edit', [
            'post' => $post,
            'tags' => $tags,
            'categories' => Category::pluck('name', 'id'),
            'platforms' => Platform::pluck('name', 'id'),
            'others' => Other::pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->rules['thumbnail'] = 'nullable|file|dimensions:max_width=1800,max_height=900';
        $validated = $request->validate($this->rules);

        $post->update($validated);

        if ($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = $thumbnail->getClientOriginalName();
            $file_extension = $thumbnail->getClientOriginalExtension();
            $path = $thumbnail->store('images', 'public');
    
            // Optimize and convert to WebP
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($path);
    
            // Create WebP version
            $webPPath = str_replace($file_extension, 'webp', $path);
            $optimizerChain->executeCommand("cwebp -q 80 $path -o $webPPath");
    
            $post->image()->update([
                'name' => $filename,
                'extension' => $file_extension,
                'path' => $path,
                'webp_path' => $webPPath, // Update the WebP path in your database
            ]);
        }
        
        $tags = explode(',', $request->input('tags'));
        $tags_ids = [];
        foreach($tags as $tag){
            $tag_exist = $post->tags()->where('name', trim($tag))->count();
            if($tag_exist == 0) {
                $tag_ob = Tag::create(['name' => $tag]);
                $tags_ids[] = $tag_ob->id;
            }
        }
        
        if(count($tags_ids) > 0)
            $post->tags()->sync( $tags_ids );

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Post has been updated');    
    }

    public function destroy(Post $post)
    {
        $post->tags()->delete();
        $post->delete();
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post has been deleted');
    }
}
