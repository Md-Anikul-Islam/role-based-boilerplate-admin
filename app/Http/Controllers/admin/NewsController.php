<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Toastr;
class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('news-list')) {
                return redirect()->route('unauthorized.action');
            }
            return $next($request);
        })->only('index');
    }
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.pages.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/news'), $imageName);
            $news = new News();
            $news->title = $request->title;
            $news->title_bn = $request->title_bn;
            $news->link = $request->link;
            $news->date = $request->date;
            $news->details = $request->details;
            $news->details_bn = $request->details_bn;
            $news->image = $imageName;
            $news->save();
            Toastr::success('News Added Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            // Handle the exception here
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);
            $news = News::find($id);
            $news->title = $request->title;
            $news->title_bn = $request->title_bn;
            $news->link = $request->link;
            $news->date = $request->date;
            $news->details = $request->details;
            $news->details_bn = $request->details_bn;
            $news->status = $request->status;

            if($request->image){
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/news'), $imageName);
                $news->image = $imageName;
            }

            $news->save();
            Toastr::success('News Updated Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $news = News::find($id);
            $imagePath = public_path('images/news/' . $news->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $news->delete();
            Toastr::success('News Deleted Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
