@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets_admin/css/dashboard.css') }}">
<style>
    .dashboard-metric {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(94, 114, 228, 0.15);
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 30px;
        border: none;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .dashboard-metric:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px rgba(94, 114, 228, 0.25);
    }
    .dashboard-metric .info-box-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        background: rgba(255,255,255,0.12);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dashboard-metric .info-box-text {
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }
    .dashboard-metric .info-box-number {
        font-size: 1.7rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
    @media (max-width: 767px) {
        .dashboard-metric {
            min-height: 110px;
            font-size: 0.95rem;
        }
        .dashboard-metric .info-box-icon {
            font-size: 1.7rem;
            width: 40px;
            height: 40px;
        }
    }
</style>
@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @php
                    $roleName = auth()->user()->roles->pluck('name')->first();
                @endphp
                @if($roleName != 'Seller' )
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="dashboard-metric" onclick="document.location='{{ url("admin/users") }}'" style="cursor: pointer;">
                        <span class="info-box-icon"><i class="fas fa-user-cog"></i></span>
                        <div class="info-box-content text-center">
                            <div class="info-box-text">Total Sellers</div>
                            <span class="info-box-number">{{ $sellerCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="dashboard-metric" onclick="document.location='{{ url('admin/users') }}'" style="cursor: pointer;">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content text-center">
                            <div class="info-box-text">Total Buyers</div>
                            <span class="info-box-number">{{ $userCount }}</span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="dashboard-metric" onclick="document.location='{{ url('admin/orders') }}'" style="cursor: pointer;">
                        <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content text-center">
                            <div class="info-box-text">Total Orders</div>
                            <span class="info-box-number mt-0">{{ $orderCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="dashboard-metric" onclick="document.location='{{ url('admin/orders') }}'" style="cursor: pointer;">
                        <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content text-center">
                            <div class="info-box-text">Total Sell</div>
                            <span class="info-box-number mt-0">{{ $totalSales }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="dashboard-metric" onclick="document.location='{{ url('admin/orders') }}'" style="cursor: pointer;">
                        <span class="info-box-icon"><i class="fas fa-university"></i></span>
                        <div class="info-box-content text-center">
                            <div class="info-box-text">Total Received Amount</div>
                            <span class="info-box-number mt-0">{{ $totalReceivedAmount }}</span>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="info-box-text mb-0"><b onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">Total Orders</b></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="progress-group">
                                        <span onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">Toner Cartridge</span>
                                        <span class="float-right"><b>150</b>/200</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-primary" style="width: 25%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        <span onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">Drum</span>
                                        <span class="float-right"><b>56</b>/28</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" style="width: 74%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        <span onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">INK</span>
                                        <span class="float-right"><b>44</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: 67%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        <span onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">Maintainance Box</span>
                                        <span class="float-right"><b>32</b>/60</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: 26%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
</div>

@endsection
@push('js')
<script>
    $(".card-header.listing").click(function() {

        $(this).children(".arrowIcon").toggleClass("show");
        $(this).siblings(".card-body.listing").toggleClass("show");

    });
</script>
@endpush