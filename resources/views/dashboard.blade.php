@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="page-content">
        <!--<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">-->
        <!--    <div class="breadcrumb-title pe-3">Dashboard</div>-->
        <!--</div>-->
        <div class="row row-cols-1 row-cols-lg-4 row-cols-xxl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Banner</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                                <i class="lni lni-react"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format($BannerCount)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Category</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                                <i class="bx bx-spa"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format($CategoryCount)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Products</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
                                <i class="bx bx-cylinder"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format(0)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Store</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                                <i class="fadeIn animated bx bx-store-alt"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format(0)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Subscrible Plans</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                                <i class="fadeIn animated bx bx-paper-plane"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format(0)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Users</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format($UsersCount)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Payments</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
                                <ion-icon name="wallet-outline"></ion-icon>
                            </div>
                            
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format($payment_amount)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-2">
                            <div>
                                <p class="mb-0 fs-6">Total Orders</p>
                            </div>
                            <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                                <ion-icon name="bag-handle-outline"></ion-icon>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <div>
                                <h4 class="mb-0">{{number_format($payment_status)}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!--end row-->
        <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h6 class="mb-0">Recent Orders</h6>
                    <div class="fs-5 ms-auto dropdown">
                        <div class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer" data-bs-toggle="dropdown"><i
                                class="bi bi-three-dots"></i></div>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--<tr>-->
                            <!--    <td>#89742</td>-->
                            <!--    <td>-->
                            <!--        <div class="d-flex align-items-center gap-3">-->
                            <!--            <div class="product-box border">-->
                            <!--                <img src="{{ asset('backend/assets/images/products/11.png') }}" alt="">-->
                            <!--            </div>-->
                            <!--            <div class="product-info">-->
                            <!--                <h6 class="product-name mb-1">Smart Mobile Phone</h6>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </td>-->
                            <!--    <td>2</td>-->
                            <!--    <td>$214</td>-->
                            <!--    <td><span class="badge bg-success">Completed</span></td>-->
                            <!--    <td>Apr 8, 2021</td>-->
                            <!--    <td>-->
                            <!--        <div class="d-flex align-items-center gap-3 fs-6">-->
                            <!--            <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip"-->
                            <!--                data-bs-placement="bottom" title="" data-bs-original-title="View detail"-->
                            <!--                aria-label="Views">-->
                            <!--                <ion-icon name="eye-outline"></ion-icon>-->
                            <!--            </a>-->
                            <!--            <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip"-->
                            <!--                data-bs-placement="bottom" title="" data-bs-original-title="Edit info"-->
                            <!--                aria-label="Edit">-->
                            <!--                <ion-icon name="pencil-outline"></ion-icon>-->
                            <!--            </a>-->
                            <!--            <a href="javascript:;" class="text-danger" data-bs-toggle="tooltip"-->
                            <!--                data-bs-placement="bottom" title="" data-bs-original-title="Delete"-->
                            <!--                aria-label="Delete">-->
                            <!--                <ion-icon name="trash-outline"></ion-icon>-->
                            <!--            </a>-->
                            <!--        </div>-->
                            <!--    </td>-->
                            <!--</tr>-->
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
