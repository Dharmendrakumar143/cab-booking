<footer>
    <!-- <div class="loader" id="loader" bis_skin_checked="1"></div> -->
    <div class="footer bg-black pt-5 ">
    <div class="container-fluid px-5">
        <div class="row align-items-center pb-3">
            <div class="col-md-4">
            <div class="footer-logo">
                <img src="{{asset('assets/images/footer-logo.png')}}">
            </div>
            </div>
            <div class="col-md-6">
            <div class="footer-text ">
                <p class="light-gray">Troy Rides is a service offered by Troy Harris Enterprises, a Subchapter S Corporation duly organized and existing under the laws of the State of California, with its principal place of business located in View Park, California, and established in the year 2024 in Santa Monica, California.</p>
                <div class="footer-link mt-2">
                <ul class="ps-0 d-flex gap-4 flex-direction-row">
                    <li class="nav-item">
                        <a class="nav-link text-white" aria-current="page" href="{{route('page-slug',['slug'=>'terms_and_conditions'])}}">Terms and Conditions </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('driver-register')}}" class="nav-link text-white">Become a Driver</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{route('page-slug',['slug'=>'privacy_policy'])}}">Privacy Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#about-us">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('page-slug',['slug'=>'faq'])}}" class="nav-link text-white">FAQ</a>
                    </li>
                    
                </ul>
                </div>
            </div>
            </div>
        </div>
        <div class="copy-right border-top py-4">
        <div class="row">
            <div class="col-md-6">
            <div class="copy">
                <p class="text-white">© 2025 . All rights reserved.</p>
            </div>
            </div>
            <div class="col-md-6">
            <div class="social-icons d-flex gap-3 justify-content-end">
                <!-- <div class="fb">
                <img src="{{asset('assets/images/Github.png')}}" alt="Github">
                </div> -->
                @if(!Auth::check())
                <div class="fb d-none" id="driver-link">
                    <a href="{{route('request-ride')}}" class="nav-link text-white">Become A Driver</a>
                </div>
                @endif
                <div class="fb">
                    <a href="https://www.facebook.com/people/Troy-Rides/61572336661816/?mibextid=wwXIfr" target="_blank"><img src="{{asset('assets/images/Facebook.png')}}" alt="Facebook"></a>
                </div>
                
                <div class="fb twiitee-icon">
                    <a href="https://x.com/troyridesapp" target="_blank"><img src="{{asset('assets/images/Twitter.png')}}" style="height: 28px; border-radius: 10px;" alt="Twitter"></a>
                </div>
              <!--   <div class="fb">
                <img src="{{asset('assets/images/Instagram.png')}}" alt="Instagram">
                </div> -->
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</footer>
