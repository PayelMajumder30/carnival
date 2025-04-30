<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Lead;
use App\Models\Admin;

class AuthController extends Controller
{
    public function check(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'username' => 'required|exists:admins,username',
            'password' => 'required',
        ], [
            'username.*' => 'Invalid credential'
        ]);

        $creds = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($creds)) {
            return redirect()->route('admin.dashboard')->with('success', 'Login successfull');
            // return redirect()->intended()->with('success', 'Login successfull');
        } else {
            return redirect()->back()->with('failure', 'Invalid credential')->withInputs('request');
        }
    }

    public function dashboard()
    {
        if (Auth::guard('admin')->check()) {
            $data = (object)[];
            // $data->categories = Category::where('status', 1)->count();
            // $data->products = Product::where('status', 1)->count();
            $data->leads = Lead::count();

            return view('admin.dashboard.index', compact('data'));
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function profile()
    {
        if (Auth::guard('admin')->check()) {
            return view('admin.profile.index');
        } else {
            return redirect()->route('admin.login');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', 'Logout successfull');
        // return redirect()->intended()->with('success', 'Logout successfull');
    }

<<<<<<< HEAD
=======

    public function PasswordEdit(){
        return view('admin.dashboard.changePassword');
    }
    public function PasswordUpdate(Request $request){
        $request->validate([
            'password'  => 'required|confirmed',
            'password_confirmation'  => 'required',
        ]);

        $user = Auth::guard('admin')->user();

        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('admin.dashboard.changePassword')->with('success', 'Password changed successfully.');
}
>>>>>>> 1eeaa93410e5d2652ca5f1c1868747da5c6fb8a1
    public function profileEdit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.dashboard.edit',compact('admin'));
    }

    public function profileUpdate(Request $request){

        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'mobile_no' => 'required|digits:10',
            ], [
            'mobile_no.digits' => 'Mobile number should be exactly 10 digits.',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
        ]);
        //dd($admin);
        return back()->with('success','Profile Update Successfully');
    }
}
