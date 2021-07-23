<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transaction;
use App\Helpers\UUIDGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\TransferConfirmRequest;

class PageController extends Controller
{
    public function index() {
        $user = Auth::guard('web')->user();
    	return view('frontend.home', compact('user'));
    }
    public function profile() {
        $user = Auth::guard('web')->user();
        return view('frontend.profile', compact('user'));
    }
    public function changePassword() 
    {
        return view('frontend.change-password');
    }

    public function changePasswordUpdate(Request $request) {
        $user = Auth::guard('web')->user();
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|max:20'
        ]);
        
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->update();
            return redirect()->route('profile')->with('update', 'Password Updated Successfully');
        }
        return back()->withErrors(['old_password' => 'old_password is incorrect'])->withInput();
    }

    public function wallet() 
    {
        $user = Auth::guard('web')->user();
        return view('frontend.wallet', compact('user'));
    }

    public function realTimeWallet() 
    {
        $user = Auth::guard('web')->user();
        if ($user->wallet) {
            return number_format($user->wallet->amount,2);
        }
            return '-';
    }

    public function transfer() //form view
    {
        $user = Auth::guard('web')->user();
        return view('frontend.transfer', compact('user'));
    }


    public function transferConfirm(TransferConfirmRequest $request)
    {
        $from_user = Auth::guard('web')->user(); //from user
        $to_user = User::where('phone', $request->to_phone)->first(); //to user
        $to_phone = $request->to_phone;
        $amount = $request->transfer_amount;
        $description = $request->description;
        $string = $to_phone.$description.$amount;
        $hash2 = hash_hmac("sha256",$string,'kbba+ppm@$@');
        $hash_val = $request->hash_val;
        // hash security
        if ($hash2 !== $hash_val) {
            return back()->withErrors(['Fail' => "hash. Invalid data"])->withInput();
        }
        //transfer to yourself
        if ($to_phone == $from_user->phone) {
            return back()->withErrors(['to_phone' => "Can't transfer to yourself"])->withInput();
        }
        //To_user exist?
        if (!$to_user) {
            return back()->withErrors(['to_phone' => 'This account number does not exist'])->withInput();
        }
        //both user has wallet?
        if (!$from_user->wallet || !$to_user->wallet) {
             return back()->withErrors(['Fail' => 'Something Wrong. Invalid data'])->withInput();
        }
        //insufficient amount
        if ($amount > $from_user->wallet->amount) {
            return back()->withErrors(['wallet_amount' => 'Your account has insufficient amount'])->withInput();
        }
        //at least 1000 MMK
        if ($request->transfer_amount < 1000) {
            return back()->withErrors(['transfer_amount' => 'Amount must be at least 1,000 MMK'])->withInput();
        };
        
        return view('frontend.transfer-confirm', compact('from_user', 'to_user', 'amount', 'description', 'hash_val'));
    }
    public function transferCheckUser(Request $request) 
    {   
        $authUser = Auth::guard('web')->user();
        if ($authUser->phone != $request->phone) {
            $user = User::where('phone', $request->phone)->first();
            if ($user) {
               return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $user
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'invalid account number'
        ]);
    }

    public function passwordCheck(Request $request) 
    {
        $authUser = Auth::guard('web')->user();
        if (!$request->password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please fill your password'
            ]);
        }
        if (Hash::check($request->password, $authUser->password)) {
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

    public function transferComplete(TransferConfirmRequest $request){
        $from_user = Auth::guard('web')->user(); //from user
        $to_user = User::where('phone', $request->to_phone)->first(); //to user
        $to_phone = $request->to_phone;
        $amount = $request->transfer_amount;
        $description = $request->description;
        $string = $to_phone.$description.$amount;
        $hash2 = hash_hmac("sha256",$string,'kbba+ppm@$@');
        // hash security
        if ($hash2 !== $request->hash_val) {
            return back()->withErrors(['Fail' => "Something Wrong. Invalid data"])->withInput();
        }
        //transfer to yourself
        if ($to_phone == $from_user->phone) {
            return back()->withErrors(['to_phone' => "Can't transfer to yourself"])->withInput();
        }
        //both user has wallet?
        if (!$from_user->wallet || !$to_user->wallet) {
             return back()->withErrors(['Fail' => 'Something Wrong. Invalid data'])->withInput();
        }
        //insufficient amount
        if ($amount > $from_user->wallet->amount) {
            return back()->withErrors(['wallet_amount' => 'Your account has insufficient amount'])->withInput();
        }
        //at least 1000 MMK
        if ($request->transfer_amount < 1000) {
            return back()->withErrors(['transfer_amount' => 'Amount must be at least 1,000 MMK'])->withInput();
        };
        //To_user exist?
        if (!$to_user) {
            return back()->withErrors(['to_phone' => 'This account number does not exist'])->withInput();
        }

            DB::beginTransaction();
        try {
            $from_account_wallet = $from_user->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_user->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerator::refNumber();
            $from_account_transaction = new Transaction;
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerator::trxId();
            $from_account_transaction->user_id = $from_user->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_user->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction;
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerator::trxId();
            $to_account_transaction->user_id = $to_user->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_user->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();
            DB::commit();

        return redirect('/transactions/'.$from_account_transaction->trx_id)->with('success', 'Transaction successful!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Fail' => 'Something Wrong. Invalid data'])->withInput();
        }        
    }

    public function transaction(Request $request)
    {
        $authUser = Auth::guard('web')->user();
        $transactions = Transaction::with('user', 'source')->orderBy('created_at', 'desc')->where('user_id', $authUser->id);
        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        if ($request->date) {
            $date = implode('-', (array_reverse(explode("-", $request->date))));
            // dd($date);
            $transactions = $transactions->whereDate('created_at', $date);
        }
        $transactions = $transactions->paginate(5);
        return view('frontend.transaction-index', compact('transactions'));
    }

    public function transactionShow($id)
    {
        $authUser = Auth::guard('web')->user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $authUser->id)->where('trx_id', $id)->first();
        return view('frontend.transaction-show', compact('transaction'));
    }

    public function hashValue(Request $request) {
       $string = $request->phone.$request->description.$request->amount;
       $hash = hash_hmac("sha256",$string,'kbba+ppm@$@');
       return response()->json([
            "status" => "success",
            "data" => $hash
            ]);
    }
}
