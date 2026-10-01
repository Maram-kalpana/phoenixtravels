@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels')
@php
use App\Models\ToursModel;

@endphp
@section('content')
  <section class="hero-section">
        <div class="banner-slider banner-sec owl-carousel">
            @foreach($get_banner_images as $key => $val)
            <div class="slider-img">
                <img src="{{ asset($val->image) }}" alt="Img">
            </div>
            @endforeach
            
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-md-12 mx-auto wow fadeInUp" data-wow-delay="0.3s">
                        <div class="banner-content mx-auto">
                            <h1 class="text-white display-5 mb-2">Get Closer to the Dream: Your Tour
                                Essentials Await</h1>
                            <h6 class="text-light mx-auto">Your ultimate destination for all things help you celebrate &
                                remember tour experience.</h6>
                        </div>
                        <div class="banner-form card mb-0">
                            <div class="card-header">
                                <ul class="nav">
                                    <li>
                                        <a href="#" class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#flight">
                                            <i class="isax isax-airplane5 me-2"></i>Flights
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#Hotels">
                                            <i class="isax isax-buildings5 me-2"></i>Hotels
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#Cars">
                                            <i class="isax isax-car5 me-2"></i>Cars
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#Cruise">
                                            <i class="isax isax-ship5 me-2"></i>Cruise
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#Tour">
                                            <i class="isax isax-camera5 me-2"></i>Tour
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-link" data-bs-toggle="tab" data-bs-target="#Bus">
                                            <i class="isax isax-bus5 me-2"></i>Bus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="flight">
                                            <form action="">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="Radio" id="oneway" value="oneway" checked>
                                                            <label class="form-check-label fs-14 ms-2" for="oneway">
                                                                Oneway
                                                            </label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="Radio" id="roundtrip" value="roundtrip">
                                                            <label class="form-check-label fs-14 ms-2" for="roundtrip">
                                                                Round Trip
                                                            </label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="Radio" id="multiway" value="multiway">
                                                            <label class="form-check-label fs-14 ms-2" for="multiway">
                                                                Multi Trip
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <span class="fw-medium fs-16 mb-2 text-dark">Millions of cheap
                                                        flights. One simple search</span>
                                                </div>
                                                <div class="normal-trip">
                                                    <div class="d-lg-flex">
                                                        <div class="d-flex  form-info">
                                                            <div class="form-item change-drop booking-dropdown dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">From</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Newyork">
                                                                    <p class="fs-12 mb-0">Ken International Airport</p>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Newyork</span>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Boston</span>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">NorthernVirginia</span>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">LosAngeles</span>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Orlando</span>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item change-drop booking-dropdown dropdown ps-2 ps-sm-3">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">To</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Las Vegas">
                                                                    <p class="fs-12 mb-0">Martini International Airport
                                                                    </p>
                                                                    <span
                                                                        class="way-icon badge badge-primary rounded-pill translate-middle">
                                                                        <i
                                                                            class="fa-solid fa-arrow-right-arrow-left"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Newyork</span>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Boston</span>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">NorthernVirginia</span>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">LosAngeles</span>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" href="#">
                                                                                <span
                                                                                    class="fs-16 fw-medium text-dark dropdown-name">Orlando</span>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Departure</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="21-10-2024">
                                                                <p class="fs-12 mb-0">Monday</p>
                                                            </div>
                                                            <div class="form-item round-drip">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Return</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="23-10-2024">
                                                                <p class="fs-12 mb-0">Wednesday</p>
                                                            </div>
                                                            <div class="form-item dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">Travellers
                                                                        and cabin class</label>
                                                                    <div class="home-eight-title text-dark member-count">4 <span
                                                                            class="fw-normal fs-14">Persons</span></div>
                                                                    <p class="fs-12 mb-0"><span class="adult">1</span> Adult, <span class="class-name">Economy</span></p>
                                                                </div>
                                                                <div
                                                                    class="dropdown-menu dropdown-menu-end dropdown-xl">
                                                                    <div class="mb-3 home-eight-title text-dark">Select
                                                                        Travelers & Class</div>
                                                                    <div class="mb-3 border br-10 info-item pb-1">
                                                                        <div class="fs-16 fw-medium mb-2 text-dark">
                                                                            Travellers</div>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Adults
                                                                                        <span
                                                                                            class="text-default fw-normal">(
                                                                                            12+ Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="adult">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="" >
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Childrens
                                                                                        <span
                                                                                            class="text-default fw-normal">(
                                                                                            2-12 Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="children">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Infants<span
                                                                                            class="text-default fw-normal">(
                                                                                            0-12 Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="infant">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3 border br-10 info-item pb-1">
                                                                        <span
                                                                            class="fs-16 fw-medium mb-2 text-dark">Travellers</span>
                                                                        <div
                                                                            class="d-flex align-items-center flex-wrap">
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Economy"
                                                                                    name="cabin-class" id="economy"
                                                                                    checked>
                                                                                <label class="form-check-label"
                                                                                    for="economy">
                                                                                    Economy
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Economy"
                                                                                    name="cabin-class"
                                                                                    id="premium-economy">
                                                                                <label class="form-check-label"
                                                                                    for="premium-economy">
                                                                                    Premium Economy
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Business"
                                                                                    name="cabin-class" id="business2">
                                                                                <label class="form-check-label"
                                                                                    for="business2">
                                                                                    Business
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="First Class"
                                                                                    name="cabin-class" id="first-class">
                                                                                <label class="form-check-label"
                                                                                    for="first-class">
                                                                                    First Class
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end">
                                                                        <a href="#"
                                                                            class="btn btn-light btn-sm me-2">Cancel</a>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm apply-btn">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary search-btn rounded">Search</button>
                                                    </div>
                                                </div>
                                                <div class="multi-trip">
                                                    <div class="d-lg-flex">
                                                        <div class="d-flex  form-info">
                                                            <div class="form-item change-drop booking-dropdown dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">From</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Newyork">
                                                                    <p class="fs-12 mb-0">Ken International Airport</p>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item change-drop booking-dropdown dropdown ps-2 ps-sm-3">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">To</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Las Vegas">
                                                                    <p class="fs-12 mb-0">Martini International Airport
                                                                    </p>
                                                                    <span
                                                                        class="way-icon badge badge-primary rounded-pill translate-middle">
                                                                        <i
                                                                            class="fa-solid fa-arrow-right-arrow-left"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Departure</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="21-10-2024">
                                                                <p class="fs-12 mb-0">Monday</p>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary search-btn rounded">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="Hotels">
                                            <form action="">
                                                <div class="fw-medium fs-16 mb-2 text-dark">Book Hotel -
                                                    Villas, Apartments & more.</div>
                                                <div class="d-lg-flex">
                                                    <div class="d-flex  form-info">
                                                        <div class="form-item booking-dropdown dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label class="form-label fs-14 text-default mb-1">City,
                                                                    Property name or Location</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0 subname-result">USA</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-custom dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Search for City, Property name or Location">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">USA
                                                                            </div>
                                                                            <p class="dropdown-sub-name">2000 Properties</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Japan
                                                                            </div>
                                                                            <p class="dropdown-sub-name">3000 Properties</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Singapore</div>
                                                                            <p class="dropdown-sub-name">8000 Properties</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Russia</div>
                                                                            <p class="dropdown-sub-name">8000 Properties</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Germany</div>
                                                                            <p class="dropdown-sub-name">2000 Properties</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">Check
                                                                In</label>
                                                            <input type="text" class="form-control check-in"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">Check
                                                                Out</label>
                                                            <input type="text" class="form-control check-out"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <div class="form-item dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Guests</label>
                                                                <div class="home-eight-title text-dark member-count">4 <span
                                                                        class="fw-normal fs-14">Persons</span></div>
                                                                <p class="fs-12 mb-0"><span class="adult">4</span> Adult, <span class="room">2</span> Rooms</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end dropdown-xl">
                                                                <div class="mb-3 home-eight-title text-dark">Select
                                                                    Travelers & Class</div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Rooms</label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="room">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Adults</label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="adult">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Children
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        2-12 Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="children">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Infants
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        0-12 Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="infant">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="fs-16 fw-medium mb-2 text-dark">
                                                                        Travellers</div>
                                                                    <div class="d-flex align-items-center flex-wrap">
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="room" id="room01" checked>
                                                                            <label class="form-check-label"
                                                                                for="room01">
                                                                                Single
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="room" id="room02">
                                                                            <label class="form-check-label"
                                                                                for="room02">
                                                                                Double
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="room" id="room03">
                                                                            <label class="form-check-label"
                                                                                for="room03">
                                                                                Delux
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="room" id="room04">
                                                                            <label class="form-check-label"
                                                                                for="room04">
                                                                                Suite
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="fs-16 fw-medium mb-2 text-dark">Property
                                                                        Type</div>
                                                                    <div class="d-flex align-items-center flex-wrap">
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="property" id="property01" checked>
                                                                            <label class="form-check-label"
                                                                                for="property01">
                                                                                Villa
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="property" id="property02">
                                                                            <label class="form-check-label"
                                                                                for="property02">
                                                                                Condo
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="property" id="property03">
                                                                            <label class="form-check-label"
                                                                                for="property03">
                                                                                Cabin
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="property" id="property04">
                                                                            <label class="form-check-label"
                                                                                for="property04">
                                                                                Apartments
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <a href="#"
                                                                        class="btn btn-light btn-sm me-2">Cancel</a>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm apply-btn">Apply</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-item booking-dropdown dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label class="form-label fs-14 text-default mb-1">Price
                                                                    per Night</label>
                                                                <input type="text" class="form-control value-input"
                                                                    value="$1000 - $15000">
                                                                <p class="fs-12 mb-0">20 Offers Available</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">₹500 - ₹2000</div>
                                                                            <p>Upto 65% offers</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Upto 65% offers</div>
                                                                            <p>Upto 40% offers</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">₹5000 - ₹8000</div>
                                                                            <p>Upto 35% offers</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">₹9000 - ₹11000</div>
                                                                            <p>Upto 20% offers</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">₹11000 - ₹15000</div>
                                                                            <p>Upto 10% offers</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary search-btn rounded">Search</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="Cars">
                                            <form action="">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                                    <div class="d-flex align-items-center flex-wrap">
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="drop" id="same-drop" value="same-drop" checked>
                                                            <label class="form-check-label fs-14 ms-2" for="same-drop">
                                                                Same drop-off
                                                            </label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="drop" id="different-drop" value="different-drop">
                                                            <label class="form-check-label fs-14 ms-2"
                                                                for="different-drop">
                                                                Different Drop off
                                                            </label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="drop" id="airport" value="airport">
                                                            <label class="form-check-label fs-14 ms-2" for="airport">
                                                                Airport
                                                            </label>
                                                        </div>
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="drop" id="hourly-drop" value="hourly-drop">
                                                            <label class="form-check-label fs-14 ms-2"
                                                                for="hourly-drop">
                                                                Hourly Package
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="fw-medium fs-16 mb-2 text-dark">Book Car for rental
                                                    </div>
                                                </div>
                                                <div class="d-lg-flex">
                                                    <div class="d-flex  form-info">
                                                        <div class="form-item change-drop booking-dropdown dropdown from-location">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">From</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0">USA</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Search for Cars">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">USA
                                                                            </div>
                                                                            <p>2000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Japan
                                                                            </div>
                                                                            <p>3000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Singapore</div>
                                                                            <p>8000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Russia</div>
                                                                            <p>8000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Germany</div>
                                                                            <p>6000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item change-drop dropdown pickup-airport">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">From</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0">Ken International Airport</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control value-input"
                                                                            placeholder="Search for Airport">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark">Hartsfield-Jackson Atlanta International
                                                                            </div>
                                                                            <p>USA</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark">Dallas/Fort Worth International</div>
                                                                            <p>USA</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark">London Heathrow</div>
                                                                            <p>UK</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark">SeoulIncheon</div>
                                                                            <p>South Korea</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark">Singapore Changi International</div>
                                                                            <p>Singapore</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item change-drop booking-dropdown dropdown to-location ps-2 ps-sm-3">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">To</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0">USA</p>
                                                                <span
                                                                    class="way-icon badge badge-primary rounded-pill translate-middle">
                                                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                                                </span>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Search for Cars">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">USA</div>
                                                                            <p>2000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Japan</div>
                                                                            <p>3000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Singapore</div>
                                                                            <p>8000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Russia</div>
                                                                            <p>8000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Germany</div>
                                                                            <p>6000 Cars</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item">
                                                            <label
                                                                class="form-label fs-14 text-default mb-1">Departure</label>
                                                            <input type="text" class="form-control datetimepicker"
                                                                value="21-10-2024">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <div class="form-item return-drop">
                                                            <label
                                                                class="form-label fs-14 text-default mb-1">Return</label>
                                                            <input type="text" class="form-control datetimepicker"
                                                                value="23-10-2024">
                                                            <p class="fs-12 mb-0">Wednesday</p>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">Pickup
                                                                Time</label>
                                                            <input type="text" class="form-control timepicker"
                                                                value="11:45 PM">
                                                        </div>
                                                        <div class="form-item dropoff-time">
                                                            <label class="form-label fs-14 text-default mb-1">Dropoff
                                                                Time</label>
                                                            <input type="text" class="form-control timepicker"
                                                                value="11:45 PM">
                                                        </div>
                                                        <div class="form-item hourly-time">
                                                            <label
                                                                class="form-label fs-14 text-default mb-1">Hours</label>
                                                            <div class="home-eight-title text-dark">02 Hrs 10 Kms</div>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary search-btn rounded">Search</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="Cruise">
                                            <form action="">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                                    <div class="fw-medium fs-16 mb-2 text-dark">Search For Cruise</div>
                                                </div>
                                                <div class="d-lg-flex">
                                                    <div class="d-flex  form-info">
                                                        <div class="form-item booking-dropdown dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Destination</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0">USA</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Search Location">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                            <p>Ken International Airport</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                            <p>Boston Logan International Airport</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                            <p>Dulles International Airport</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                            <p>Los Angeles International Airport</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                            <p>Orlando International Airport</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">Start
                                                                Date</label>
                                                            <input type="text" class="form-control check-in"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">End
                                                                Date</label>
                                                            <input type="text" class="form-control check-out"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <!-- Add a hidden input or just use one of the inputs to trigger the daterangepicker -->
                                                        <input type="text" class="date-range d-none">
                                                        <div class="form-item dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Travellers
                                                                    & Cabin </label>
                                                                <div class="home-eight-title text-dark member-count">4 <span
                                                                        class="fw-normal fs-14">Persons</span></div>
                                                                <p class="fs-12 mb-0"><span class="adult">4</span> Adult</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end dropdown-xl">
                                                                <div class="mb-3 home-eight-title text-dark">Select
                                                                    Travelers & Class</div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="fs-16 fw-medium mb-2 text-dark">
                                                                        Travellers</div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Adults
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        12+ Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="adult">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Childrens
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        2-12 Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="children">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Infants
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        0-12 Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="infant">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="fs-16 fw-medium mb-2 text-dark">Select
                                                                        Cabin</div>
                                                                    <div class="d-flex align-items-center flex-wrap">
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="cabin" id="cabin1" checked>
                                                                            <label class="form-check-label"
                                                                                for="cabin1">
                                                                                Solo cabins
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="cabin" id="cabin2">
                                                                            <label class="form-check-label"
                                                                                for="cabin2">
                                                                                Balcony
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check me-3 mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                value="Business" name="cabin"
                                                                                id="cabin3">
                                                                            <label class="form-check-label"
                                                                                for="cabin3">
                                                                                Oceanview
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check mb-3">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="cabin" id="cabin4">
                                                                            <label class="form-check-label"
                                                                                for="cabin4">
                                                                                Balcony rooms
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <a href="#"
                                                                        class="btn btn-light btn-sm me-2">Cancel</a>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm apply-btn">Apply</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary search-btn rounded">Search</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="Tour">
                                            <form action="">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                                    <div class="fw-medium fs-16 mb-2 text-dark">Search holiday packages
                                                        & trips</div>
                                                </div>
                                                <div class="d-lg-flex">
                                                    <div class="d-flex  form-info">
                                                        <div class="form-item booking-dropdown dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label class="form-label fs-14 text-default mb-1">Where
                                                                    would like to go?</label>
                                                                <input type="text" class="form-control value-input" value="Newyork">
                                                                <p class="fs-12 mb-0">USA</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-md p-0">
                                                                <div class="input-search p-3 border-bottom">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="Search for City, Property name or Location">
                                                                        <span
                                                                            class="input-group-text px-2 border-start-0"><i
                                                                                class="isax isax-search-normal"></i></span>
                                                                    </div>
                                                                </div>
                                                                <ul>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">USA</div>
                                                                            <p>200 Places</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Japan</div>
                                                                            <p>300 Places</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Singapore</div>
                                                                            <p>80 Places</p>
                                                                        </a>
                                                                    </li>
                                                                    <li class="border-bottom">
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Russia</div>
                                                                            <p>500 Places</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="#">
                                                                            <div class="fs-16 fw-medium text-dark dropdown-name">Germany</div>
                                                                            <p>150 Places</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="form-item">
                                                            <label
                                                                class="form-label fs-14 text-default mb-1">Dates</label>
                                                            <input type="text" class="form-control check-in"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Monday</p>
                                                        </div>
                                                        <div class="form-item">
                                                            <label class="form-label fs-14 text-default mb-1">Check
                                                                Out</label>
                                                            <input type="text" class="form-control check-out"
                                                                value="21-10-2026">
                                                            <p class="fs-12 mb-0">Wednesday</p>
                                                        </div>
                                                        <div class="form-item dropdown">
                                                            <div data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="false" role="menu">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Travellers</label>
                                                                <div class="home-eight-title text-dark member-count">4 <span
                                                                        class="fw-normal fs-14">Persons</span></div>
                                                                <p class="fs-12 mb-0"><span class="adult">2</span> Adult</p>
                                                            </div>
                                                            <div class="dropdown-menu dropdown-menu-end dropdown-xl">
                                                                <div class="mb-3 home-eight-title text-dark">Select
                                                                    Travelers</div>
                                                                <div class="mb-3 border br-10 info-item pb-1">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Adult</label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="adult">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Childrens
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        12+ Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="children">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="mb-3 d-flex align-items-center justify-content-between">
                                                                                <label
                                                                                    class="form-label text-gray-9 mb-2">Infants
                                                                                    <span
                                                                                        class="text-default fw-normal">(
                                                                                        12+ Yrs )</span></label>
                                                                                <div class="custom-increment">
                                                                                    <div class="input-group">
                                                                                        <span
                                                                                            class="input-group-btn float-start">
                                                                                            <button type="button"
                                                                                                class="quantity-left-minus btn btn-light btn-number"
                                                                                                data-type="minus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-minus"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                        <input type="text"
                                                                                            name="quantity"
                                                                                            class=" input-number"
                                                                                            value="1" data-type="infant">
                                                                                        <span
                                                                                            class="input-group-btn float-end">
                                                                                            <button type="button"
                                                                                                class="quantity-right-plus btn btn-light btn-number"
                                                                                                data-type="plus"
                                                                                                data-field="">
                                                                                                <span><i
                                                                                                        class="isax isax-add"></i></span>
                                                                                            </button>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <a href="#"
                                                                        class="btn btn-light btn-sm me-2">Cancel</a>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm apply-btn">Apply</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary search-btn rounded">Search</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="Bus">
                                            <form action="#">
                                                <div
                                                    class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="tripType" id="oneways" value="oneway" checked>
                                                            <label class="form-check-label fs-14 ms-2"
                                                                for="oneways">Oneway</label>
                                                        </div>

                                                        <div class="form-check d-flex align-items-center me-3 mb-2">
                                                            <input class="form-check-input mt-0" type="radio"
                                                                name="tripType" id="roundedtrip" value="roundtrip">
                                                            <label class="form-check-label fs-14 ms-2"
                                                                for="roundedtrip">Round Trip</label>
                                                        </div>
                                                    </div>
                                                    <div class="fw-medium fs-16 mb-2 text-dark">Low cost Buses in One
                                                        simple search</div>
                                                </div>
                                                <div class="normal-trip">
                                                    <div class="d-lg-flex">
                                                        <div class="d-flex  form-info">
                                                            <div class="form-item change-drop booking-dropdown dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">From</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Newyork">
                                                                    <p class="fs-12 mb-0">USA</p>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Spain</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item change-drop booking-dropdown dropdown ps-2 ps-sm-3">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">To</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Las Vegas">
                                                                    <p class="fs-12 mb-0">USA</p>
                                                                    <span
                                                                        class="way-icon badge badge-primary rounded-pill translate-middle">
                                                                        <i
                                                                            class="fa-solid fa-arrow-right-arrow-left"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Spain</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>USA</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Departure</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="21-10-2024">
                                                                <p class="fs-12 mb-0">Monday</p>
                                                            </div>
                                                            <div class="form-item round-drip">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Return</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="23-10-2024">
                                                                <p class="fs-12 mb-0">Wednesday</p>
                                                            </div>
                                                            <div class="form-item dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">Travellers</label>
                                                                    <div class="home-eight-title text-dark member-count">4 <span
                                                                            class="fw-normal fs-14">Persons</span></div>
                                                                    <p class="fs-12 mb-0"><span class="adult">2</span> Adult, <span class="children">2</span> children</p>
                                                                </div>
                                                                <div
                                                                    class="dropdown-menu dropdown-menu-end dropdown-xl">
                                                                    <div class="mb-3 home-eight-title text-dark">Select
                                                                        Travelers & Class</div>
                                                                    <div class="mb-3 border br-10 info-item pb-1">
                                                                        <div class="fs-16 fw-medium mb-2 text-dark">
                                                                            Travellers</div>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Adults
                                                                                        <span
                                                                                            class="text-default fw-normal">(
                                                                                            12+ Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="adult">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Childrens
                                                                                        <span
                                                                                            class="text-default fw-normal">(
                                                                                            2-12 Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="children">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-3">
                                                                                    <label
                                                                                        class="form-label text-gray-9 mb-2">Infants<span
                                                                                            class="text-default fw-normal">(
                                                                                            0-12 Yrs )</span></label>
                                                                                    <div class="custom-increment">
                                                                                        <div class="input-group">
                                                                                            <span
                                                                                                class="input-group-btn float-start">
                                                                                                <button type="button"
                                                                                                    class="quantity-left-minus btn btn-light btn-number"
                                                                                                    data-type="minus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-minus"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                            <input type="text"
                                                                                                name="quantity"
                                                                                                class=" input-number"
                                                                                                value="1" data-type="infant">
                                                                                            <span
                                                                                                class="input-group-btn float-end">
                                                                                                <button type="button"
                                                                                                    class="quantity-right-plus btn btn-light btn-number"
                                                                                                    data-type="plus"
                                                                                                    data-field="">
                                                                                                    <span><i
                                                                                                            class="isax isax-add"></i></span>
                                                                                                </button>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3 border br-10 info-item pb-1">
                                                                        <div class="fs-16 fw-medium mb-2 text-dark">
                                                                            Travellers</div>
                                                                        <div
                                                                            class="d-flex align-items-center flex-wrap">
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Economy"
                                                                                    name="cabin-class" id="economys"
                                                                                    checked>
                                                                                <label class="form-check-label"
                                                                                    for="economys">
                                                                                    Seater
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Economy"
                                                                                    name="cabin-class"
                                                                                    id="premium-economys">
                                                                                <label class="form-check-label"
                                                                                    for="premium-economys">
                                                                                    Sleeper
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check me-3 mb-3">
                                                                                <input class="form-check-input"
                                                                                    type="radio" value="Business"
                                                                                    name="cabin-class" id="business">
                                                                                <label class="form-check-label"
                                                                                    for="business">
                                                                                    AC Sleeper
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end">
                                                                        <a href="#"
                                                                            class="btn btn-light btn-sm me-2">Cancel</a>
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-sm apply-btn">Apply</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary search-btn rounded">Search</button>
                                                    </div>
                                                </div>
                                                <div class="multi-trip">
                                                    <div class="d-lg-flex">
                                                        <div class="d-flex  form-info">
                                                            <div class="form-item change-drop booking-dropdown dropdown">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">From</label>
                                                                    <input type="text" class="form-control value-input"
                                                                        value="Newyork">
                                                                    <p class="fs-12 mb-0">Ken International Airport</p>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item change-drop booking-dropdown dropdown ps-2 ps-sm-3">
                                                                <div data-bs-toggle="dropdown"
                                                                    data-bs-auto-close="outside" aria-expanded="false"
                                                                    role="menu">
                                                                    <label
                                                                        class="form-label fs-14 text-default mb-1">To</label>
                                                                    <div class="home-eight-title text-dark value-input">Las Vegas
                                                                    </div>
                                                                    <p class="fs-12 mb-0">Martini International Airport
                                                                    </p>
                                                                    <span
                                                                        class="way-icon badge badge-primary rounded-pill translate-middle">
                                                                        <i
                                                                            class="fa-solid fa-arrow-right-arrow-left"></i>
                                                                    </span>
                                                                </div>
                                                                <div class="dropdown-menu dropdown-md p-0">
                                                                    <div class="input-search p-3 border-bottom">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Search Location">
                                                                            <span
                                                                                class="input-group-text px-2 border-start-0"><i
                                                                                    class="isax isax-search-normal"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <ul>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Newyork</div>
                                                                                <p>Ken International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Boston</div>
                                                                                <p>Boston Logan International Airport
                                                                                </p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Northern Virginia</div>
                                                                                <p>Dulles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li class="border-bottom">
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Los Angeles</div>
                                                                                <p>Los Angeles International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item" href="#">
                                                                                <div class="fs-16 fw-medium text-dark dropdown-name">Orlando</div>
                                                                                <p>Orlando International Airport</p>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="form-item">
                                                                <label
                                                                    class="form-label fs-14 text-default mb-1">Departure</label>
                                                                <input type="text" class="form-control datetimepicker"
                                                                    value="21-10-2024">
                                                                <p class="fs-12 mb-0">Monday</p>
                                                            </div>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary search-btn rounded">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


 
        
@foreach($get_custom_section as $key => $val)
    <section class="section destination-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-10 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="section-header text-center">
                        <h2 class="mb-2"><span class="text-primary text-decoration-underline">{{$val->section_name}}</span></h2>
                        <p class="sub-title">{{$val->section_title}}</p>
                    </div>
                </div>
            </div>
            <div class="owl-carousel cruise-slider nav-center wow fadeInUp">
                @php 
                // Convert comma-separated string to array
                $tourIds = !empty($val->tour_id) ? explode(',', $val->tour_id) : [];
                $tour = ToursModel::select('tour_name','slug','image')
                        ->whereIn('id', $tourIds)
                        ->where('status',1)
                        ->get();
                @endphp
                @foreach($tour as $key => $row)
                <div class="{{$val->section_style}}">
                    <img src="{{ asset($row->image ?? 'frontend/img/cruise/cruise-15.jpg') }}" alt="img" class="{{$val->section_style_image}}" style="height: 160px;">
                    <h6><a href="{{ route('tour', $row->slug) }}">{{$row->tour_name}}</a></h6>
                </div>
                @endforeach
            </div>
            
        </div>
    </section>
@endforeach
    <!-- /type Section -->


   

  <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="years-off  wow fadeInUp">
                                <h4 class="text-light"> 15 Years of Experience</h4>
                            </div>
                            <div class="discover-set-img-left  wow fadeInDown ">
                                <img src="{{asset('frontend/img/cruise/cruise-22.jpg')}}" alt="Img">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="discover-set-img-right wow zoomIn">
                                <img src="{{asset('frontend/img/cruise/cruise-21.jpg')}}" alt="Img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-header mb-4  wow fadeInUp">
                        <h2 class="mb-2">Discover <span class="text-primary  text-decoration-underline">Who We
                                Are</span></h2>
                        <p class="sub-title">Phoenix Tours and Travels (since 2016) is whole Proprietorship company, managed by Mr. Prasad M. Registered in Telangana , Miyapur, Hyderabad.We are a leading tour operator providing Flight Tickets, Domestic tour packages, and Hotel Reservations in all over India. </p>
                        <p class="sub-title">We are a leading tour operator providing Flight Tickets, Domestic tour packages, and Hotel Reservations in all over India. Our primary concern is to provide the quality service to the costumers</p>
                        <p class="sub-title">We have been organizing Inbound and Outbound Holiday packages & we specialize in Honeymoon Packages, Education Packages, Group tour packages, Wild Life Sanctuaries, Adventure Tours, Spiritual Tours, Historical Tours, and Customized Tours in all over India.</p>                     
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
        <!-- type Section -->
   
    <section class="section faq-secpath">
        <div class="bg-div">
            <img src="{{asset('frontend/img/bg/bg-03.png')}}" class="bg-3" alt="img">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faq-five  wow zoomIn">
                        <img src="{{asset('frontend/img/faq/travel-destination.png')}}" alt="img">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row justify-content-center">
                        <div class="col-xl-12  wow fadeInUp" data-wow-delay="0.2s">
                            <div class="section-header mb-4">
                                <p class="mb-1">Frequently Asked Questions</p>
                                <h2>Specializing in dream destinations.</h2>
                            </div>
                        </div>
                    </div>
                    <div class="accordion accordion-flush faq-accordion-five" id="accordionFaq">
                     @forelse($faq as $key => $val)
                        <div class="accordion-item mb-3 wow fadeInUp" data-wow-delay="{{ ($key + 1) * 0.2 }}s">
                            
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq-collapse{{ $key }}" 
                                    aria-expanded="{{ $key == 0 ? 'true' : 'false' }}"
                                    aria-controls="faq-collapse{{ $key }}">
                                    
                                    {{ $val->question ?? 'Default Question' }}
                                </button>
                            </h2>
                        
                            <div id="faq-collapse{{ $key }}" 
                                class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                                data-bs-parent="#accordionFaq">
                        
                                <div class="accordion-body">
                                    <p class="mb-0">{{ $val->answer ?? 'Default Answer' }}</p>
                                </div>
                            </div>
                        
                        </div>
                        @empty
                        <p>No FAQs available</p>
                        @endforelse
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /FAQ -->

    <!-- Testimonial Section -->
    <section class="section bgwhite">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-10 text-center">
                    <div class="section-header text-center  wow fadeInUp">
                        <h2 class="mb-2">What’s Our <span class="text-primary  text-decoration-underline">User</span>
                            Says</h2>
                        <p class="sub-title">Explore heartfelt stories from our satisfied travelers, showcasing
                            unforgettable journeys and remarkable memories</p>
                    </div>
                </div>
            </div>
            <div class="owl-carousel testimonial-slider">
                @foreach($testimonials as $key => $val)
                <div class="card border-white wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between text-center flex-column">
                            <div class="rating d-flex align-items-center me-2 mb-3">
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <span class="text-gray-9 fs-14 ms-1">5.0</span>
                            </div>
                            <h6 class="mb-2">Awesome service</h6>
                            <p class="mb-3">{{ $val->message }}</p>
                            <div class="d-flex align-items-center">
                                <div class="ms-2 d-flex align-items-center">
                                    <h6 class="fs-16 fw-medium"><a href="#">{{$val->name}}</a></h6>
                                    <span class="city-type">{{$val->role}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 @endforeach
               

            </div>
        </div>
    </section>
    <!-- /Testimonial Section -->

@endsection
@section('script')
 <script>
        $("#quick_contact").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('home') }}", new FormData($("#quick_contact")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Request Send Successfully', 'top right', 'success', true, "{{ route('home') }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0], 'botton left');
                    }
                }
            }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
 @stop