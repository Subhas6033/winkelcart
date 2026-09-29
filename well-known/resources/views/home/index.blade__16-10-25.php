@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets_admin/css/dashboard.css') }}">
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" onclick="document.location='{{ url('admin/users') }}'" style="cursor: pointer;">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-cog"></i></span>
                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>Total Sellers</b></h5>
                            <span class="info-box-number">{{ $sellerCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" onclick="document.location='{{ url('admin/users') }}'" style="cursor: pointer;">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-cog"></i></span>
                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>Total Buyers</b></h5>
                            <span class="info-box-number">{{ $userCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box" onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>Total Orders</b></h5>
                            <span class="info-box-number mt-0">40</span>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-box-open"></i></span>
                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>Consumable</b></h5>
                            <span class="info-box-number">60</span>
                        </div>                        
                    </div>                    
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3" onclick="document.location='{{ url('admin/dashboard') }}'" style="cursor: pointer;">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-ticket-alt"></i></span>
                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>Closed</b></h5>
                            <span class="info-box-number">140</span>
                        </div>                        
                    </div>
                </div>
                
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tag"></i></span>

                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>No. of Rent</b></h5>
                            <span class="info-box-number">44</span>
                        </div>                        
                    </div>                    
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-tools"></i></span>

                        <div class="info-box-content text-center">
                            <h5 class="info-box-text mb-0"><b>No. of AMC</b></h5>
                            <span class="info-box-number">45</span>
                        </div>
                    </div>
                </div> -->
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