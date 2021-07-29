<?php

namespace App\Http\Controllers\Backend;

use App\Wallet;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WalletController extends Controller
{
    public function index() {
        return view('backend.wallet.index');
    }

    public function ssd() {
        $wallets = Wallet::query();
        return Datatables::of($wallets)
                ->addColumn('account_user', function($each){
                    $user = $each->user;
                    if(!$user){
                        return '-';
                    }
                    return '<p>Name: '.$user->name.'</p><p>Email: '.$user->email.'</p><p>Phone: '.$user->phone.'</p>';
                })
                ->editColumn('amount', function($each) {
                    return number_format($each->amount,2);
                })
                ->rawColumns(['account_user'])
                ->make(true);
    }
    public function addWalletForm() {
        $users = User::with('wallet')->get();
        return view('backend.wallet.add-wallet', compact('users'));
    }
    public function reduceWalletForm() {
        $users = auth()->guard('web')->user();
        return view('backend.wallet.reduce-wallet', compact('users'));
    }
    public function checkPassword($password) 
    {
        $authUser = Auth::guard('admin_user')->user();
        if (!$password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please fill your password'
            ]);
        }
        if (Hash::check($password, $authUser->password)) {
            return response()->json([
                    'status' => 'success',
                    'message' => 'success'
                ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'invalid password'
        ]);
    }
    public function addWallet(Request $request){
        return $request->all();
    }
    public function reduceWallet(){
        return "reduce wallet posst";
    }
}
