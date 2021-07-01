<?php

namespace App\Http\Controllers\Backend;

use App\AdminUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;


class AdminUserController extends Controller
{
    public function index(){
    	$users = AdminUser::all();
    	return view('backend.admin-user.index', compact('users'));
    }

    // server side data
    public function ssd() {
    	$admin_users = AdminUser::query();
    	return Datatables::of($admin_users)->make(true);
    }

    public function create() {
    	return view('backend.admin-user.create');
    }

    public function store(StoreAdminUser $request) {

        $admin_user = new AdminUser;
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = Hash::make($request->password);
        $admin_user->save();

        return redirect()->route('admin.admin-user.index')->with('create', 'Successfully Created');
    }
}
