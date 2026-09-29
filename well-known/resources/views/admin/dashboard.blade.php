@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="container-fluid py-4">
    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Admin Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here's what's happening with your business.</p>
        </div>
        <div class="text-muted">
            <i class="fas fa-calendar-alt mr-1"></i> {{ date('d M Y') }}
        </div>
    </div>

    <!-- E-Commerce Stats Section -->
    <h5 class="mb-3 text-muted"><i class="fas fa-shopping-cart mr-2"></i>E-Commerce Overview</h5>
    <div class="row">
        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #8b5cf6;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #8b5cf6;">
                                Total Orders
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(139, 92, 246, 0.1);">
                                <i class="fas fa-shopping-bag fa-2x" style="color: #8b5cf6;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #10b981;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #10b981;">
                                Total Sales
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalSales, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(16, 185, 129, 0.1);">
                                <i class="fas fa-rupee-sign fa-2x" style="color: #10b981;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #f59e0b;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #f59e0b;">
                                Total Products
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(245, 158, 11, 0.1);">
                                <i class="fas fa-box fa-2x" style="color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #6366f1;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #6366f1;">
                                Total Categories
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalCategories }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(99, 102, 241, 0.1);">
                                <i class="fas fa-th-large fa-2x" style="color: #6366f1;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users & Order Status Row -->
    <div class="row">
        <!-- Total Sellers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #ec4899;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #ec4899;">
                                Total Sellers
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalSellers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(236, 72, 153, 0.1);">
                                <i class="fas fa-store fa-2x" style="color: #ec4899;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Buyers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #14b8a6;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #14b8a6;">
                                Total Buyers
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalBuyers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(20, 184, 166, 0.1);">
                                <i class="fas fa-user-friends fa-2x" style="color: #14b8a6;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #eab308;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #eab308;">
                                Pending Orders
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(234, 179, 8, 0.1);">
                                <i class="fas fa-clock fa-2x" style="color: #eab308;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #22c55e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #22c55e;">
                                Completed Orders
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $completedOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(34, 197, 94, 0.1);">
                                <i class="fas fa-check-circle fa-2x" style="color: #22c55e;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Stats Section -->
    <h5 class="mb-3 mt-2 text-muted"><i class="fas fa-hotel mr-2"></i>Hotel & Booking Overview</h5>
    <div class="row">
        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #16a34a;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #16a34a;">
                                Total Bookings
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalBookings }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(22, 163, 74, 0.1);">
                                <i class="fas fa-calendar-check fa-2x" style="color: #16a34a;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #0ea5e9;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0ea5e9;">
                                Hotel Revenue
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(14, 165, 233, 0.1);">
                                <i class="fas fa-rupee-sign fa-2x" style="color: #0ea5e9;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Hotels -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #3b82f6;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #3b82f6;">
                                Total Hotels
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalHotels }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(59, 130, 246, 0.1);">
                                <i class="fas fa-hotel fa-2x" style="color: #3b82f6;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Rooms -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #6b7280;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #6b7280;">
                                Total Rooms
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalRooms }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(107, 114, 128, 0.1);">
                                <i class="fas fa-bed fa-2x" style="color: #6b7280;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hotel Booking Status Row -->
    <div class="row">
        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #f59e0b;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #f59e0b;">
                                Total Users
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(245, 158, 11, 0.1);">
                                <i class="fas fa-users fa-2x" style="color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #eab308;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #eab308;">
                                Pending Bookings
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $pendingBookings }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(234, 179, 8, 0.1);">
                                <i class="fas fa-clock fa-2x" style="color: #eab308;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmed Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #22c55e;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #22c55e;">
                                Confirmed Bookings
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $confirmedBookings }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(34, 197, 94, 0.1);">
                                <i class="fas fa-check-circle fa-2x" style="color: #22c55e;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #ef4444;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #ef4444;">
                                Cancelled Bookings
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $cancelledBookings }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(239, 68, 68, 0.1);">
                                <i class="fas fa-times-circle fa-2x" style="color: #ef4444;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Razorpay Payment Stats Section -->
    <h5 class="mb-3 mt-2 text-muted"><i class="fas fa-credit-card mr-2"></i>Online Payment Overview (Razorpay)</h5>
    <div class="row">
        <!-- Total Razorpay Payments -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #1a73e8;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a73e8;">
                                Total Transactions
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $totalRazorpayPayments }}</div>
                            <div class="small text-muted mt-1">Paid: {{ $razorpayPaidPayments }} | Pending: {{ $razorpayPendingPayments }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(26, 115, 232, 0.1);">
                                <i class="fas fa-credit-card fa-2x" style="color: #1a73e8;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Razorpay Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #0d9488;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0d9488;">
                                Razorpay Revenue
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">₹{{ number_format($razorpayPaidAmount / 100, 2) }}</div>
                            <div class="small text-muted mt-1">All successful Razorpay payments</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(13, 148, 136, 0.1);">
                                <i class="fas fa-rupee-sign fa-2x" style="color: #0d9488;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Online-Paid Orders -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #7c3aed;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #7c3aed;">
                                Online-Paid Orders
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $onlinePaidOrders }}</div>
                            <div class="small text-muted mt-1">Orders paid via Razorpay</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(124, 58, 237, 0.1);">
                                <i class="fas fa-shopping-cart fa-2x" style="color: #7c3aed;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Online Order Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #059669;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #059669;">
                                Online Order Revenue
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">₹{{ number_format($onlineOrderRevenue, 2) }}</div>
                            <div class="small text-muted mt-1">Revenue from online-paid orders</div>
                        </div>
                        <div class="col-auto">
                            <div class="rounded-circle p-3" style="background: rgba(5, 150, 105, 0.1);">
                                <i class="fas fa-chart-line fa-2x" style="color: #059669;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Module Overview -->
    <h5 class="mb-3 mt-2 text-muted"><i class="fas fa-shield-alt mr-2"></i>Admin Module Overview</h5>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #06b6d4;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #06b6d4;">Support Tickets</div>
                        <i class="fas fa-life-ring" style="color: #06b6d4;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalSupportTickets }}</div>
                    <div class="small text-muted">Open/In Progress: {{ $openSupportTickets }} | Resolved: {{ $resolvedSupportTickets }}</div>
                    <a href="{{ route('admin.support_tickets.index') }}" class="small font-weight-bold d-inline-block mt-2">View tickets <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #f97316;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #f97316;">Return/Refund Requests</div>
                        <i class="fas fa-undo-alt" style="color: #f97316;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalReturnRefundRequests }}</div>
                    <div class="small text-muted">Pending/Review: {{ $pendingReturnRefundRequests }} | Completed: {{ $completedReturnRefundRequests }}</div>
                    <a href="{{ route('admin.return_refunds.index') }}" class="small font-weight-bold d-inline-block mt-2">View requests <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #7c3aed;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #7c3aed;">Seller KYC</div>
                        <i class="fas fa-id-card" style="color: #7c3aed;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $pendingSellerKyc + $notSubmittedSellerKyc }}</div>
                    <div class="small text-muted">Pending: {{ $pendingSellerKyc }} | Not Submitted: {{ $notSubmittedSellerKyc }}</div>
                    <a href="{{ route('admin.seller_kyc.index') }}" class="small font-weight-bold d-inline-block mt-2">View KYC queue <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #16a34a;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #16a34a;">Seller Settlements</div>
                        <i class="fas fa-wallet" style="color: #16a34a;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalSellerSettlements }}</div>
                    <div class="small text-muted">Pending: {{ $pendingSellerSettlements }} | Processing: {{ $processingSellerSettlements }}</div>
                    <a href="{{ route('admin.seller_settlements.index') }}" class="small font-weight-bold d-inline-block mt-2">View settlements <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #0284c7;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #0284c7;">Contact Queries</div>
                        <i class="fas fa-envelope-open-text" style="color: #0284c7;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalContactQueries }}</div>
                    <div class="small text-muted">Pending: {{ $pendingContactQueries }} | Solved: {{ $solvedContactQueries }}</div>
                    <a href="{{ route('admin.contact_queries.index') }}" class="small font-weight-bold d-inline-block mt-2">View contact queries <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #0ea5e9;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #0ea5e9;">Order Timelines</div>
                        <i class="fas fa-stream" style="color: #0ea5e9;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalOrderTimelineEvents }}</div>
                    <div class="small text-muted">Timeline updates today: {{ $todayOrderTimelineEvents }}</div>
                    <a href="{{ route('admin.order_timelines.index') }}" class="small font-weight-bold d-inline-block mt-2">View timeline manager <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #334155;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #334155;">Audit Logs</div>
                        <i class="fas fa-history" style="color: #334155;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $totalAuditLogEntries }}</div>
                    <div class="small text-muted">Audit events today: {{ $todayAuditLogEntries }}</div>
                    <a href="{{ route('admin.audit_logs.index') }}" class="small font-weight-bold d-inline-block mt-2">View audit trail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-12 mb-4">
            <div class="card h-100 py-2" style="border-left: 5px solid #6366f1;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-xs font-weight-bold text-uppercase" style="color: #6366f1;">CMS and Policies</div>
                        <i class="fas fa-file-alt" style="color: #6366f1;"></i>
                    </div>
                    <div class="h4 mb-1 font-weight-bold text-gray-800">{{ $activeCmsPages }}/{{ $totalCmsPages }}</div>
                    <div class="small text-muted">Active pages | Active policy pages: {{ $activePolicyPages }}</div>
                    <a href="{{ route('admin.cms_pages.index') }}" class="small font-weight-bold d-inline-block mt-2">Manage CMS pages <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Admin Activity -->
    <div class="row">
        <div class="col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #1e3a5f;">Recent Contact Queries</h6>
                    <a href="{{ route('admin.contact_queries.index') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentContactQueries as $contactQuery)
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">#{{ $contactQuery->id }}</small>
                                    <span class="badge {{ ($contactQuery->status ?? 'Pending') === 'Solved' ? 'badge-success' : 'badge-warning' }}">{{ $contactQuery->status ?? 'Pending' }}</span>
                                </div>
                                <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($contactQuery->business_name, 42) }}</div>
                                <small class="text-muted">{{ $contactQuery->email }}</small>
                                <div><small class="text-muted">{{ optional($contactQuery->created_at)->diffForHumans() }}</small></div>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">No contact queries yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #1e3a5f;">Recent Support Tickets</h6>
                    <a href="{{ route('admin.support_tickets.index') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentSupportTickets as $ticket)
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">#{{ $ticket->id }} @if($ticket->order_number) | {{ $ticket->order_number }} @endif</small>
                                    @php
                                        $ticketBadge = $ticket->status === 'Open'
                                            ? 'badge-warning'
                                            : ($ticket->status === 'In Progress'
                                                ? 'badge-info'
                                                : ($ticket->status === 'Resolved' ? 'badge-success' : 'badge-secondary'));
                                    @endphp
                                    <span class="badge {{ $ticketBadge }}">{{ $ticket->status }}</span>
                                </div>
                                <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($ticket->subject, 56) }}</div>
                                <small class="text-muted">{{ optional($ticket->created_at)->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">No support tickets yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #1e3a5f;">Recent Return/Refund Requests</h6>
                    <a href="{{ route('admin.return_refunds.index') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentReturnRefundRequests as $requestItem)
                            <li class="list-group-item py-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">#{{ $requestItem->id }} @if($requestItem->order_number) | {{ $requestItem->order_number }} @endif</small>
                                    <span class="badge badge-light">{{ $requestItem->status }}</span>
                                </div>
                                <div class="font-weight-bold">{{ $requestItem->request_type }} request by {{ optional($requestItem->user)->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ optional($requestItem->created_at)->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">No return/refund requests yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #1e3a5f;">Recent Audit Logs</h6>
                    <a href="{{ route('admin.audit_logs.index') }}" class="small">View all</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentAuditLogs as $log)
                            <li class="list-group-item py-3">
                                <div class="font-weight-bold">{{ \Illuminate\Support\Str::limit($log->action, 48) }}</div>
                                <small class="text-muted">Entity: {{ $log->entity_type }} | By: {{ optional($log->user)->name ?? 'System' }}</small>
                                <div><small class="text-muted">{{ optional($log->created_at)->diffForHumans() }}</small></div>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">No audit logs yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Razorpay Payments -->
    <div class="row">
        <div class="col-xl-12 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold" style="color: #1a73e8;"><i class="fas fa-credit-card mr-1"></i> Recent Razorpay Payments</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-flush mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Razorpay Order ID</th>
                                    <th>Payment ID</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRazorpayPayments as $rp)
                                    <tr>
                                        <td><code class="small">{{ $rp->razorpay_order_id }}</code></td>
                                        <td><code class="small">{{ $rp->razorpay_payment_id ?? '—' }}</code></td>
                                        <td>{{ optional($rp->user)->name ?? 'N/A' }} <small class="text-muted">({{ optional($rp->user)->email ?? '' }})</small></td>
                                        <td><span class="badge badge-light">{{ ucfirst(str_replace('_', ' ', $rp->type ?? 'N/A')) }}</span></td>
                                        <td>₹{{ number_format($rp->amount / 100, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $rp->status === 'paid' ? 'success' : ($rp->status === 'created' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($rp->status) }}
                                            </span>
                                        </td>
                                        <td><small>{{ optional($rp->created_at)->format('d M Y, h:i A') }}</small></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">No Razorpay transactions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex align-items-center">
                    <i class="fas fa-bolt mr-2" style="color: #16a34a;"></i>
                    <h6 class="m-0 font-weight-bold" style="color: #1e3a5f;">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('orders.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-shopping-bag"></i> <span>Orders</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('products.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-box"></i> <span>Products</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('categories.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-th-large"></i> <span>Categories</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('admin.hotels.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-hotel"></i> <span>Hotels</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-calendar-check"></i> <span>Bookings</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <a href="{{ route('users.index') }}" class="btn btn-lg w-100 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #ec4899, #db2777); color: #fff; border-radius: 10px; padding: 15px;">
                                <i class="fas fa-users"></i> <span>Users</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
