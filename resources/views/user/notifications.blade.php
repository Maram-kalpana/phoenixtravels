@extends('layouts.web')
@section('title','Notifications | Phoenix Tours & Travels')

@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Notifications</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Stay updated with your bookings and offers</li>
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

                <!-- Notifications -->
                <div class="col-xl-9 col-lg-8">
                    <div class="card shadow-none mb-0">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap row-gap-3">
                                <h6>Notifications</h6>
                                <div class="d-flex">
                                    <a href="#" class="btn btn-light btn-sm d-flex align-items-center me-2"><i
                                            class="isax isax-tick-square me-2"></i>Mark all as Read</a>
                                    <a href="#" class="btn btn-primary btn-sm d-flex align-items-center"
                                        data-bs-toggle="modal" data-bs-target="#delete_modal"><i
                                            class="isax isax-trash me-2"></i>Delete All</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                           @forelse($notifications as $notification)
                                <div class="card notification-card mb-3 {{ $notification->status == 0 ? 'bg-light' : '' }}">
                                    <div class="card-body d-sm-flex align-items-center">
                            
                                        {{-- Icon Section --}}
                                        <span class="avatar avatar-lg rounded-circle flex-shrink-0 me-sm-3 mb-3 mb-sm-0
                                            @if($notification->type == 'booking') bg-primary
                                            @elseif($notification->type == 'payment') bg-success
                                            @else bg-warning
                                            @endif">
                            
                                            @if($notification->type == 'booking')
                                                <i class="isax isax-calendar-edit5"></i>
                                            @elseif($notification->type == 'payment')
                                                <i class="isax isax-card"></i>
                                            @else
                                                <i class="isax isax-gift"></i>
                                            @endif
                            
                                        </span>
                            
                                        {{-- Content --}}
                                        <div class="flex-fill">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                            
                                                <h6 class="fs-16 mb-0">
                                                    {{ $notification->title }}
                                                </h6>
                            
                                                {{-- Actions --}}
                                                <div class="d-flex gap-2">
                                                    @if($notification->status == 0)
                                                        <a href="{{ route('user.notification.read',$notification->id) }}"
                                                           class="btn btn-success btn-sm">
                                                            Mark Read
                                                        </a>
                                                    @endif
                            
                                                    <a href="#"
                                                       class="notification-delete-btn btn btn-primary btn-sm"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#delete_modal">
                                                        Delete
                                                    </a>
                                                </div>
                            
                                            </div>
                            
                                            <p class="mb-1 text-muted">
                                                {{ $notification->message }}
                                            </p>
                            
                                            <p class="text-gray-9 mb-0">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                            
                                    </div>
                                </div>
                            
                            @empty
                                <div class="p-4 text-center text-muted">
                                    No notifications found
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- /Notifications -->

            </div>
        </div>
    </div>
@endsection