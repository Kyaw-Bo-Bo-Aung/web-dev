@extends('backend.layouts.app')

@section('title', 'wallet-add')

@section('content')
<div class="app-page-title">
	<div class="page-title-wrapper">
		<div class="page-title-heading">
			<div class="page-title-icon">
				<i class="metismenu-icon icon-gradient bg-mean-fruit pe-7s-wallet"></i>
			</div>
			<div>Add Wallet</div>
		</div>   
	</div>
</div>

<div class="pt-3">
	<a href="{{ route('admin.wallet.index')}}" class="btn btn-secondary"><i class="pe-7s-back"></i> Back </a>
</div>

<div class="content py-3">
	<div class="card m-auto">
		<div class="card-body">
			<form action="{{route('admin.wallet.add.post')}}" method="POST" id="addWalletForm">
				@csrf
				<div class="form-group">
					<select name="user_id" class="form-control select_user" required>
						<option></option>
						@foreach($users as $user)
							<option value="{{$user->id}}">{{ $user->phone }} ({{ $user->name }})</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="amount">Amount :</label>
					<input type="text" name="amount" id="amount" class="form-control" required='required'>
				</div>
				<div class="form-group">
					<label for="amount">Description :</label>
					<textarea class="form-control" rows="3"></textarea>
				</div>
				<div class="form-group wallet_btn">
					<button type="submit" class="btn btn-primary mx-1">Confirm</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection
@section('scripts')
<script>
	$(document).ready(function() {
	    $('.select_user').select2({
	    	placeholder: "-- Choose User --",
    		allowClear: true,
	    	theme: 'bootstrap4',
	    });
	    $('.wallet_btn').on('click',function(e) {
	    	e.preventDefault();
	    	Swal.fire({
                title: 'Fill your password',
                icon: 'info',
                html:
                '<input type="password" name="password" class="form-control text-center" id="check_password" autocomplete="off" autofocus />',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                confirmButtonColor: '#FF756B',
                cancelButtonColor: '#aaa'
            }).then(result => {
                if (result.isConfirmed) {
                    let password = $('#check_password').val(); 
                    $.ajax({
                        url: '/admin/add-wallet/password-check/'+password,
                        type: 'POST',
                        success: function(res){
                            if (res.status == 'success') {
                                $('#addWalletForm').submit();     
                            }else{
                                 Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: res.message
                                })
                            }
                        }
                    })
                }
            })
	    });
	});
</script>
@endsection
