<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Cat;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Auth;

class AdminBlogController extends Controller
{
    /**
     * ブログ一覧を表示
     */
    public function index()
    {
        //$user = Illuminate\Support\Facades\Auth::user();
        $blogs = Blog::latest('updated_at')->paginate(10);
        return view('admin.blogs.index', ['blogs' => $blogs]);
    }

    /**
     * ブログ投稿画面
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * 投稿するブログを保存する処理
     */
    public function store(StoreBlogRequest $request)
    {
        $savedImagePath = $request->file('image')->store('blogs', 'public');

        $blog = new Blog($request->validated());
        $blog->image = $savedImagePath;
        $blog->save();

        return to_route('admin.blogs.index')->with('success', 'ブログを投稿しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, string $id)
    {
        $blog = Blog::findorFail($id);
        $updateData = $request->validated();

        // 画像を変更するときの処理
        if($request->has('image')){
            // 変更前の画像を削除
            Storage::disk('public')->file('image')->delete($blog->image);
            // 変更後の画像をアップロード、保存パスを更新対象データにセット
            $updateData['image'] = $request->file('image')->store('blogs'. 'public');
        }
        $blog->category()->associate($updateData['category_id']);
        $blog->cats()->sync($updateData['cats'] ?? []);
        $blog->update($updateData);

        return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    /**
     * 指定したIDのブログを削除
     */
    public function destroy(string $id)
    {
        $blog = Blog::findorFail($id);
        $blog->delete();
        Storage::disk('public')->delete($blog->image);

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');
    }
}
