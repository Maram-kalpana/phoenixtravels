@extends('layouts.web')
@section('title','Travel Wallet | Phoenix Tours & Travels')

@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Travel Wallet</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Manage your wallet balance and transactions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


<div class="content">
        <div class="container">

            <div class="row">

                <!-- Sidebar -->
                <div class="col-xl-3 col-lg-4 theiaStickySidebar">
                    @include('user.siderbar')
                </div>
                <!-- /Sidebar -->

                <!-- Wallet -->
                <div class="col-xl-9 col-lg-8">
                   

                    <!-- Transactions -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap row-gap-3">
                                <div>
                                    <h6>Transactions</h6>
                                    <p class="fs-14">No of Transactions : 60</p>
                                </div>
                                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                                    <div class="input-icon-end position-relative">
                                        <span class="input-icon-addon">
                                            <i class="isax isax-calendar-edit"></i>
                                        </span>
                                        <input type="text" class="form-control date-range bookingrange"
                                            placeholder="dd/mm/yyyy - dd/mm/yyyy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Transactions -->

                    <!-- Transactions List -->
                    <div class="card hotel-list mb-0">
                        <div class="card-body p-0">
                            <div class="list-header d-flex align-items-center justify-content-between flex-wrap">
                                <h6>Transactions List</h6>
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="input-icon-start  me-2 position-relative">
                                        <span class="icon-addon">
                                            <i class="isax isax-search-normal-1 fs-14"></i>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                    <div class="dropdown me-3">
                                        <a href="#"
                                            class="dropdown-toggle text-gray-6 btn  rounded border d-inline-flex align-items-center"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Status
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end p-3">
                                            <li>
                                                <a href="#" class="dropdown-item rounded-1">Completed</a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item rounded-1">Pending</a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item rounded-1">Cancelled</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Payment Type</th>
                                            <th>Credit / Debit</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-gray-9 fw-medium">Card</td>
                                            <td>Debit</td>
                                            <td>15 May 2025, 10:00 AM</td>
                                            <td><span class="text-danger">-₹256</span></td>
                                            <td>₹11,569</td>
                                            <td>
                                                <span
                                                    class="badge badge-success rounded-pill d-inline-flex align-items-center fs-10"><i
                                                        class="fa-solid fa-circle fs-5 me-1"></i>Completed</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-9 fw-medium">Paypal</td>
                                            <td>Credit</td>
                                            <td>20 May 2025, 11:20 AM</td>
                                            <td><span class="text-success">+₹3000</span></td>
                                            <td>₹11,569</td>
                                            <td>
                                                <span
                                                    class="badge badge-secondary rounded-pill d-inline-flex align-items-center fs-10"><i
                                                        class="fa-solid fa-circle fs-5 me-1"></i>Pending</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-9 fw-medium">Stripe</td>
                                            <td>Credit</td>
                                            <td>22 May 2025, 02:40 PM</td>
                                            <td><span class="text-success">+₹4000</span></td>
                                            <td>₹12,497</td>
                                            <td>
                                                <span
                                                    class="badge badge-success rounded-pill d-inline-flex align-items-center fs-10"><i
                                                        class="fa-solid fa-circle fs-5 me-1"></i>Completed</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-9 fw-medium">Card</td>
                                            <td>Debit</td>
                                            <td>12 Jun 2025, 05:15 PM</td>
                                            <td><span class="text-danger">-₹600</span></td>
                                            <td>₹14,284</td>
                                            <td>
                                                <span
                                                    class="badge badge-danger rounded-pill d-inline-flex align-items-center fs-10"><i
                                                        class="fa-solid fa-circle fs-5 me-1"></i>Cancelled</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-9 fw-medium">Card</td>
                                            <td>Debit</td>
                                            <td>17 Jun 2025, 09:30 AM</td>
                                            <td><span class="text-success">+₹11,569</span></td>
                                            <td>₹13,025</td>
                                            <td>
                                                <span
                                                    class="badge badge-success rounded-pill d-inline-flex align-items-center fs-10"><i
                                                        class="fa-solid fa-circle fs-5 me-1"></i>Completed</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Transactions List -->

                </div>
                <!-- /Wallet -->

            </div>
        </div>
    </div>


@endsection