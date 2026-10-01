@extends('layouts.web')
@section('title', 'OTP Verification | Phoenix Tours & Travels')
@section('css')
<style>
    .otp-inputs{
display:flex;
justify-content:center;
gap:10px;
}

.otp-input{
width:45px;
height:50px;
text-align:center;
font-size:20px;
border:1px solid #ddd;
border-radius:8px;
}

.otp-input:focus{
border-color:#0d6efd;
box-shadow:0 0 5px rgba(13,110,253,0.5);
outline:none;
}
</style>
@endsection
@section('content')
<section id="common_banner" class="py-5 text-center bg-light">
    <div class="container">
        <h2 class="fw-bold" style="color: #fff;">OTP Verification</h2>
        <p class="text-muted" style="color: #fff !important;">Enter the OTP sent to your mobile number</p>
    </div>
</section>
<section class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-4 col-md-6">
<div class="card shadow-lg border-0 rounded-4">
<div class="card-body p-5 text-center">
<h4 class="fw-bold mb-2">Verify OTP</h4>
<p class="text-muted small mb-4">
Enter the 6 digit code sent to your phone
</p>
<form id="login_submit">
@csrf

<div class="otp-inputs mb-4">

<input type="text" maxlength="1" class="otp-input">
<input type="text" maxlength="1" class="otp-input">
<input type="text" maxlength="1" class="otp-input">
<input type="text" maxlength="1" class="otp-input">
<input type="text" maxlength="1" class="otp-input">
<input type="text" maxlength="1" class="otp-input">

</div>

<input type="hidden" name="otp" id="otp">

<div class="d-grid">
<button type="submit" id="submit" class="btn btn-primary btn-lg">
Verify OTP
</button>
</div>

</form>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@section('script')
<script>
const inputs = document.querySelectorAll(".otp-input");
inputs.forEach((input,index)=>{
input.addEventListener("keyup",(e)=>{
if(input.value.length === 1 && index < inputs.length -1){
inputs[index+1].focus();
}
if(e.key === "Backspace" && index > 0 && input.value === ""){
inputs[index-1].focus();
}
});
});
</script>
<script>

$("#login_submit").submit(function(event){

event.preventDefault();

/* combine otp inputs */

let otp = "";

$(".otp-input").each(function(){
otp += $(this).val();
});

$("#otp").val(otp);

$("#submit").prop('disabled', true);
$("#submit").html('<span class="spinner-border spinner-border-sm"></span> Verifying...');

axios.post("{{ route('user.otp') }}", new FormData(this))

.then(response => {

var data = response.data;

if(data.success){

notify(null,'Otp Successfully Verified','top right', 'success',true,"{{ route('user.dashboard') }}", 1000);


}else{

$("#submit").prop('disabled', false);
$("#submit").html('Verify OTP');

if(data.error_new){
notify(null,data.error_new[0],'top right','error');
}

}

})

.catch(error => {

$("#submit").prop('disabled', false);
$("#submit").html('Verify OTP');

notify(null,'Something went wrong','top right');

});

});

</script>
@stop