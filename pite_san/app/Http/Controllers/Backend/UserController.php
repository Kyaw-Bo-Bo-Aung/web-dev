<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use Jenssegers\Agent\Agent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('admin.user.index')->with('user_create', 'User created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('backend.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        dd($request);
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = $request->password ? Hash::make($request->password) : $admin_user->password;
        $admin_user->update();
        return redirect()->route('admin.admin-user.index')->with('update', 'Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function ssd () 
    {
        $users = User::query();
        return Datatables::of($users)
            ->editColumn('user_agent', function($user) {
                if (!$user->user_agent) {
                    return '-';
                }
                $agent = new Agent();
                $agent->setUserAgent($user->user_agent);
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();
                return '<table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th><small>Device</small></th>
                                <td><small>'.$device.'</small></td>
                            </tr>
                            <tr>
                                <th><small>Platform</small></th>
                                <td><small>'.$platform.'</small></td>
                            </tr>
                            <tr>
                                <th><small>Browser</small></th>
                                <td><small>'.$browser.'</small></td>
                            </tr>
                        </tbody>
                        </table>';
            })
            ->addColumn('action', function ($user) {
                $action_btn =  "<a class='p-2 mx-1' href='". route('admin.user.edit', $user->id) ."''><i class='text-warning fa fa-edit'></i></a><a data-id='".$user->id."' class='delete_btn p-2 mx-1' href=''><i class='text-danger fa fa-trash'></i></a>";
                return '<div class="action_btn">'. $action_btn . '</div>';
            })
            ->rawColumns(['user_agent', 'action'])
            ->make(true);
    }
}
