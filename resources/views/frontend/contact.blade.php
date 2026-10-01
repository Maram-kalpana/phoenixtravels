@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels')
@section('content')
<div class="breadcrumb-bar text-center"
     style="background: url( '{{asset('frontend/img/bg/banner-6.png')}}') no-repeat center center; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Contact Us</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="isax isax-home5"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Contact Us
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

    <div class="content">
        <div class="container">
            <div class="row align-items-center row-gap-4">
                <div class="col-xl-7 col-lg-7">
                    <div class="mb-4 mb-lg-0">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="mb-3">Reach Out to Our Dedicated Support Team</h2>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6 class="mb-2">Our team is ready to help. Your satisfaction is our priority</h6>
                            <p>Got a question, need advice, or looking for help? Our knowledgeable team is here to
                                assist you every step of the way. We’re just a message or call away, ready to provide
                                the guidance you need.</p>
                        </div>
                        <div class="border-bottom mb-4">
                            <div class="d-flex align-items-center mb-4">
                                <span class="avatar avatar-lg rounded-circle bg-light  text-gray-6 me-2"><i
                                        class="isax isax-sms5 fs-24"></i></span>
                                <div>
                                    <p class="fs-14 mb-0">Email Address</p>
                                    <h6 class="text-gray-6"><a href="mailto:support@phoenixtoursandtravels.in">support@phoenixtoursandtravels.in</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom mb-4">
                            <div class="d-flex align-items-center mb-4">
                                <span class="avatar avatar-lg rounded-circle bg-light  text-gray-6 me-2"><i
                                        class="isax isax-call-calling5 fs-24"></i></span>
                                <div>
                                    <p class="fs-14 mb-0">Phone Number</p>
                                    <h6 class="text-gray-6">+91 8985094597</h6>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-lg rounded-circle bg-light  text-gray-6 me-2"><i
                                        class="isax isax-map-15 fs-24"></i></span>
                                <div>
                                    <p class="fs-14 mb-0">Our Location</p>
                                    <h6 class="text-gray-6">18 & 19 1, Gullapallis Movva Nest Complex, 1st Floor, Plot. No. 17, Nizampet Rd, opp. Vijetha Super Market, Hyder Nagar, Kukatpally, Hyderabad, Telangana 500085</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <div class="card bg-light-200 shadow-none mb-0">
                        <div class="card-body">
                            <div class="mb-3">
                                <h2 class="mb-1">Get in Touch</h2>
                                <p class="text-gray-6 mb-1">How we can help you? Please write down your query</p>
                            </div>
                            <form action="" method="POST" id="contact_form_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" id="first_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name" id="last_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="3"  name="message" id="message"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <button type="submit"  id="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="map-grid">
                <iframe class="w-100" height="300"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3805.0265353250875!2d78.38647809999999!3d17.506246699999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9268e369f219%3A0x2846a6011b5ab839!2sPhoenix%20Tours%20%26%20Travels!5e0!3m2!1sen!2sin!4v1774789661909!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $("#contact_form_content").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('contact') }}", new FormData($("#contact_form_content")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Your Request Sent Successfully', 'top right', 'success', true,
                    "{{ route('contact') }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],
                            'botton left');
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

