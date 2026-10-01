@extends('layouts.web')
@section('title','Dashboard | Phoenix Tours & Travels')
@section('content')

 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Customer Dashboard</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Manage your bookings and travel activities</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<div class="content content-two">
        <div class="container">

            <div class="row">

                <!-- Sidebar -->
                <div class="col-xl-3 col-lg-4 theiaStickySidebar">
                    @include('user.siderbar')
                </div>
                <!-- /Sidebar -->

                <div class="col-xl-9 col-lg-8">
                   
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xl rounded-circle bg-primary me-3">
                                            <i class="isax isax-calendar-15 fs-36"></i>
                                        </span>
                                        <div>
                                            <h3>80</h3>
                                            <p>Total Bookings</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xl rounded-circle bg-secondary me-3">
                                            <i class="isax isax-money-send5 fs-36"></i>
                                        </span>
                                        <div>
                                            <h3>₹5965</h3>
                                            <p>Total Transactions</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="card shadow-none">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-xl rounded-circle bg-purple me-3">
                                            <i class="isax isax-money-tick5 fs-36"></i>
                                        </span>
                                        <div>
                                            <h3>₹5965</h3>
                                            <p>Average Value</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 d-flex">
                            <div class="card shadow-none flex-fill">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h6>Recent Bookings</h6>
                                        <div class="dropdown ">
                                            <a href="#"
                                                class="dropdown-toggle btn bg-light-200 btn-sm text-gray-6 rounded-pill fw-normal fs-14 d-inline-flex align-items-center"
                                                data-bs-toggle="dropdown">
                                                <i class="isax isax-document-filter me-2 fs-14 text-gray-6"></i>Filter
                                            </a>
                                            <ul class="dropdown-menu  dropdown-menu-end p-3">
                                                <li>
                                                    <a href="#" class="dropdown-item rounded-1">
                                                        <i class="ti ti-point-filled me-1"></i>Completed
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item rounded-1">
                                                        <i class="ti ti-point-filled me-1"></i>Upcoming
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item rounded-1">
                                                        <i class="ti ti-point-filled me-1"></i>Inprogress
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    

                    
                </div>
            </div>
        </div>
    </div>
@endsection