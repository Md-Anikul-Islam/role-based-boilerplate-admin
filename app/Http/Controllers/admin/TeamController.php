<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Toastr;
class TeamController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('team-list')) {
                return redirect()->route('unauthorized.action');
            }
            return $next($request);
        })->only('index');
    }

    public function index()
    {
        $teams = Team::latest()->get();
        return view('admin.pages.team.index', compact('teams'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ]);
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/team'), $imageName);
            $team = new Team();
            $team->name = $request->name;
            $team->email = $request->email;
            $team->phone = $request->phone;
            $team->designation = $request->designation;
            $team->linkedin_link = $request->linkedin_link;
            $team->fb_link = $request->fb_link;
            $team->details = $request->details;
            $team->order_no = $request->order_no;
            $team->image = $imageName;
            $team->save();
            Toastr::success('Team Member Added Successfully', 'Success');
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
                'name' => 'required',
                'email' => 'required',
            ]);
            $team = Team::find($id);
            $team->name = $request->name;
            $team->email = $request->email;
            $team->phone = $request->phone;
            $team->designation = $request->designation;
            $team->linkedin_link = $request->linkedin_link;
            $team->fb_link = $request->fb_link;
            $team->details = $request->details;
            $team->order_no = $request->order_no;
            $team->status = $request->status;
            if($request->image){
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('images/team'), $imageName);
                $team->image = $imageName;
            }
            $team->save();
            Toastr::success('Team Member Updated Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $team = Team::find($id);

            // Delete associated image from server
            $imagePath = public_path('images/team/' . $team->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the Team member
            $team->delete();

            Toastr::success('Team Member Deleted Successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


}
