@extends('layouts.web')
@section('title', $blog_details->meta_title.'|  Phoenix Tours & Travels')
@section('content')
  <div class="breadcrumb-bar breadcrumb-bg-01 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Blog Details</h2>
                    
                </div>
            </div>
        </div>
    </div>
  <div class="content">
        <div class="container">

            <!-- Blog Details -->
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8 col-md-12">
                    <div class="card blog-details mb-4 mb-lg-0">
                        <div class="card-body">
                            <div class="blog-content">
                                <div class="blog-image mb-3">
                                    <img src="{{asset($blog_details->image)}}" alt="image" class="img-fluid rounded">
                                </div>
                                
                                <div class="mb-3">
                                    <h2>{{$blog_details->title}}</h2>
                                </div>
                                <div class="mb-3">
                                    <p class="text-gray-6">{!! $blog_details->description !!}</p>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <!-- /Blog Details -->

        </div>
    </div>

@endsection