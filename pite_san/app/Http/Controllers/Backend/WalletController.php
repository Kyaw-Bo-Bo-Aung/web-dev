<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Wallet;

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
}
