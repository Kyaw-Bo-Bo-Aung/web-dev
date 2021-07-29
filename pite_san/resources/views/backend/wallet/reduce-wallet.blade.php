@extends('backend.layouts.app')

@section('title', 'wallet-add')

@section('content')
<div class="app-page-title">
	<div class="page-title-wrapper">
		<div class="page-title-heading">
			<div class="page-title-icon">
				<i class="metismenu-icon icon-gradient bg-mean-fruit pe-7s-wallet"></i>
			</div>
			<div>Reduce Wallet</div>
		</div>   
	</div>
</div>

<div class="pt-3">
	<a href="{{ route('admin.wallet.index')}}" class="btn btn-secondary"><i class="pe-7s-back"></i> Back </a>
</div>

<div class="content pt-3">
	<div class="card">
		<div class="card-body">
			wallet reduce
		</div>
	</div>
</div>

@endsection
