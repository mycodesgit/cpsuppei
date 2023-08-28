@extends('layouts.master')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-size: 17pt"></h5>
                    @include('partials.control_invSidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Property Type</th>
                                    <th>Property No.</th>
                                    <th>Office</th>
                                    <th>Item No.</th>
                                    <th>Item Desc.</th>
                                    <th>Serial No.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                <tr></tr>
                            </tbody> --}}
                            <h1 align="center" class="mt-3">
                                <i class="fas fa-code fa-xl"></i>
                            </h1>
                            <h1 align="center">
                                This part is ongoing <b id="typewriter-text"></b>
                            </h1>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

<script>
    AOS.init({
        easing: 'ease-in-out-sine',
        duration: 1000
    });
    const textElement = document.getElementById("typewriter-text");
    const textToType = "Development!";
    const typingSpeed = 150; // Adjust this value to change the typing speed (in milliseconds).
    const delayBetweenText = 2000; // Adjust this value to set the delay between text resets (in milliseconds).

    let charIndex = 0;
    let isTyping = true;

    function typeNextCharacter() {
        if (isTyping) {
            if (charIndex < textToType.length) {
                textElement.innerHTML += textToType.charAt(charIndex);
                charIndex++;
                setTimeout(typeNextCharacter, typingSpeed);
            } else {
                isTyping = false;
                setTimeout(resetTypewriter, delayBetweenText);
            }
        }
    }

    function resetTypewriter() {
        textElement.innerHTML = "";
        charIndex = 0;
        isTyping = true;
        typeNextCharacter();
    }

    typeNextCharacter();
</script>

@endsection