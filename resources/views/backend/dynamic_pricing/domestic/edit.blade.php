@extends('layouts.app')
@section('title', 'Package Price Edit')
@section('css')
<style>
    .price-table-container {
        overflow-x: auto;
        margin-top: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }
    .price-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        min-width: 800px;
    }
    .price-table th,
    .price-table td {
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        text-align: center;
        vertical-align: middle;
    }
    .price-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #1e293b;
    }
    .table-input {
        width: 100%;
        min-width: 80px;
        padding: 0.25rem 0.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        text-align: center;
    }
    .table-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .star-3 {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 10px;
    }
    .star-4 {
        background-color: #e0f2fe;
        border-left: 4px solid #3b82f6;
        padding: 10px;
    }
    .star-5 {
        background-color: #f3e8ff;
        border-left: 4px solid #a855f7;
        padding: 10px;
    }
    .package-basic-info {
        background-color: #f0fdf4;
        border-left: 4px solid #22c55e;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Package Price</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Package Price</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">Edit Package Price</h6>
                        <hr>

                        <form method="post" id="package_price_form" action="" enctype="multipart/form-data">
                            @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">State Name</label>
                                        <select name="state_id" id="state_id" class="form-control">
                                            <option value="">Select State Name</option>
                                            @foreach($state as $key => $val)
                                                <option value="{{ $val->id }}" 
                                                    {{ (old('state_id', $dynamic_price->state_id ?? '') == $val->id) ? 'selected' : '' }}>
                                                    {{ $val->tour_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <!-- 3 Star Category Table -->
                            <div class="section-title star-{{ $dynamic_price->star_category }}">
                                <span>⭐ {{ $dynamic_price->star_category }} Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_{{ $dynamic_price->star_category }}">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                            <tr>
                                                <td><input type="text" name="hotel_name" value="{{ $dynamic_price->hotel_name }}" class="table-input"></td>
                                                <td><input type="text" name="rooms_cost" value="{{ $dynamic_price->rooms_cost }}" class="table-input" placeholder="Room cost"></td>
                                                <td><input type="number" name="extra_bed_cost" value="{{ $dynamic_price->extra_bed_cost }}" class="table-input" placeholder="Extra bed cost"></td>
                                                <td><input type="number" name="attract_tickets" value="{{ $dynamic_price->attract_tickets }}" class="table-input" placeholder="Ticket cost"></td>
                                                <td><input type="number" name="honeymoon_cost" value="{{ $dynamic_price->honeymoon_cost }}" class="table-input" placeholder="Ticket cost"></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>

                            
                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <button type="submit" id="submit" class="btn btn-primary">Update Package Price</button>
                                    <a href="{{ route('list.dynamic.price', [request()->segment(3)]) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Form submission
    $("#package_price_form").submit(function(event) {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');

        const formData = new FormData(this);
        formData.append('_method', 'POST');

        axios.post("{{ route('edit.dynamic.price', [request()->segment(3)]) }}", formData)
            .then(response => {
                var data = response.data;
                if (data.success) {
                    if (typeof notify === 'function') {
                        notify(null, 'Package price updated successfully', 'top right', 'success', true, "{{ route('list.dynamic.price',[request()->segment(3)]) }}", 1000);
                    } else {
                        window.location.href = "{{ route('list.dynamic.price',[request()->segment(3)]) }}";
                    }
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Update Package Price');

                    if (data.error) {
                        let errorMessage = '';
                        if (typeof data.error === 'object') {
                            for (let key in data.error) {
                                if (data.error[key] && Array.isArray(data.error[key])) {
                                    errorMessage += data.error[key][0] + '\n';
                                }
                            }
                        }
                        if (typeof notify === 'function') {
                            notify(null, errorMessage || 'Validation error occurred', 'bottom left');
                        } else {
                            alert(errorMessage || 'Validation error occurred');
                        }
                    }
                }
            })
            .catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Update Package Price');
                console.error('Error:', error);

                let errorMsg = 'Something went wrong';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMsg = error.response.data.message;
                }

                if (typeof notify === 'function') {
                    notify(null, errorMsg, 'top right', 'error');
                } else {
                    alert(errorMsg);
                }
            });
    });
</script>
@stop