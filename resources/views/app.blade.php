<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    {{--
    <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}"> --}}
    <title>{{ config('app.name', 'App') }} - Donation</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Plugins css -->
    <link rel="stylesheet" href=" {{ URL::asset('assets/styles/plugins.css') }} ">

    <!-- Main css -->
    <link rel="stylesheet" href=" {{ URL::asset('assets/styles/main.css') }} ">
    <link rel="stylesheet" href=" {{ URL::asset('assets/styles/footer.css') }} ">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

    @if (config('app.env') == 'production')
        {{-- {!! $google_script_head !!} --}}
    @endif


    <!-- $value = session('key'); -->

    {{-- Favicon --}}
    <link rel="icon" href="{{ URL::asset('assets/images/HAFlogo.png') }}" sizes="32x32" />
    {{-- <link rel="icon" href="{{ URL::asset('assets/images/sadqaonlinelogo.png') }}" sizes="192x192" /> --}}
    <link rel="apple-touch-icon" href="{{ URL::asset('assets/images/HAFlogo.png') }}" />

    @include('_jsVariables')
    @yield('header_scripts')
</head>

<body>

    @if (config('app.env') == 'production')
        {{-- {!! $google_script_body !!} --}}
    @endif

    <div id="wrapper" class="wrapper">

        <!-- BEGIN HEADER -->
        <header class="site-header">
            <div class="site-header-bar hidden">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col col-lg-6">
                            <div class="contacts-info">
                                <p> {{-- <i class="fa fa-info-circle"></i> Serving Helpless People Since More Than 3
                                    Decades --}}
                                </p>
                            </div>
                        </div>
                        <div class="col col-lg-6">
                            {{-- <div class="social">
                                <h3>Follow us:</h3>
                                <ul class="social-nav">
                                    <li><a href=""><i class="fa
                                                    fa-facebook"></i></a></li>
                                    <li><a href=""><i class="fa fa-youtube"></i></a></li>
                                </ul>
                            </div> --}}
                            <div class="contacts-info">
                                <p><i class="fa fa-phone"></i> 0208 903 8944</p>
                                <p><i class="fa fa-envelope-open-o"></i>
                                    info@sadaqaonline.org
                                </p>
                                {{-- <p> <a href="">Ways to Donate</a></p> --}}
                                <p> <a href="//{{ config('icharms.website_url') }}/contact/">Contact Us</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-header-inner">
                <div class="container-fluid">
                    <div class="logo">
                        <a href=" //{{ config('icharms.website_url') }} "><img height="120px" 
                                src="{{ URL::asset('assets/images/HAFlogo2.png') }}"
                                style="margin-top:-20px" alt="Sadaqa Online"></a>
                    </div>
                    <nav class="main-nav">
                        <ul>

                            <li class="main-nav-item">
                                <a class="main-nav-link" href="//{{ config('icharms.website_url') }}">Home</a>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://sadaqa-online.vercel.app/about">
                                    About</a>
                            </li>
                            <li class="main-nav-item">
                              <a class="main-nav-link"
                                  href="https://sadaqa-online.vercel.app/services">Services</a>
                          </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link"
                                    href="https://sadaqa-online.vercel.app/">Appeal</a>
                            </li>

                            <li class="main-nav-item">
                                <a class="main-nav-link"
                                    href="https://sadaqa-online.vercel.app/event">Event</a>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://sadaqa-online.vercel.app/blog">
                                    Blog</a>
                            </li>

                            <li class="main-nav-item">
                                <a class="main-nav-link"
                                    href="https://sadaqa-online.vercel.app/contact">Contact</a>
                            </li>
                        </ul>
                        <button type="button" class="main-nav-toggle-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </nav>
                    <ul class="user-nav">
                        <li class="user-nav-item">
                            <a class="user-nav-link" href="{{ route('home') }}/#cart-section" data-toggle="tooltip"
                                data-placement="top" title="Cart">
                                <i class="fa fa-shopping-basket"></i>
                                <sup class="cart-count"></sup>
                            </a>
                        </li>
                        @if (\Session::has('user'))
                            <li class="user-nav-item">
                                <a class="user-nav-link" href="{{ action('AccountController@profile') }}"
                                    data-toggle="tooltip" data-placement="top" title="Profile">
                                    <i class="fa fa-user-o"></i>
                                </a>
                            </li>
                        @else
                            <li class="user-nav-item">
                                <a class="user-nav-link btn-popup" href="#login-form" data-toggle="tooltip"
                                    data-placement="top" title="Login">
                                    <i class="fa fa-sign-in"></i>
                                </a>
                            </li>
                            <li class="user-nav-item">
                                <a class="user-nav-link btn-popup" href="#signup-form" data-toggle="tooltip"
                                    data-placement="top" title="Register">
                                    <i class="fa fa-user-plus"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <button type="button" class="user-nav-toggle-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </header>
        <!-- END HEADER -->

        @if (Request::is('/', '/home'))
            <!-- BEGIN FAST DONATE -->
            <div class="section-dark fast-donate">
                <div class="container no-top-padding no-bottom-padding">
                    <div class="row">
                        <div class="col col-lg-5 col-md-5 col-sm-5">
                            <header class="heading">
                                <p>Hope starts here</p>
                                <h2><i>Fast</i> donate</h2>
                            </header>
                        </div>
                        <div
                            class="col col-lg-2 col-lg-offset-2 col-md-2
                            col-md-offset-2 col-sm-2 col-sm-offset-2">
                            <a href="#donate-form"
                                class="btn btn-lg
                                btn-primary donate_form">Donate</a>
                        </div>
                        @if (config('icharms.qurbani_button'))
                            <div class="col col-lg-2 col-md-2 col-sm-2 ">
                                <a href="{{ url('qurbani') }}"
                                    class="btn btn-lg
                    btn-default">Give Qurbani</a>
                        @endif
                    </div>
                </div>
            </div>
    </div>
    <!-- END FAST DONATE -->
    @endif

    <!-- BEGIN MAIN CONTENT -->
    <main class="site-content">
        <div class="container no-top-padding">
            @yield('content')
        </div>


        @yield('modal')
    </main>
    <!-- END MAIN CONTENT -->

    <!-- BEGIN FOOTER -->

    <div class="home-container">
        <footer class="home-footer">
          <div class="home-container1">
            <div class="home-logo">
              <img alt="logo" src="{{ URL::asset('assets/images/HAFlogo2.png') }}" height="120px" class="home-image" />
              <div>
                
              </div>
              <p class="font_paragraph">Our mission is to harness the power <br>of AI
                to solve complex business challenges <br>&
                decision-makers with data-driven insights,<br>
                and enhance user experiences across
                digital platforms.</p>
                <br>
                <p>Website: <a href="www.sadaqaonline.org">www.sadaqaonline.org</a></p>
            </div>
            <div class="home-links-container">
              <div class="home-container2">
                <div class="home-product-container hidden">
                  <span class="home-text">APPEALS</span>
                  <a href="https://sadaqaonline.org/all-appeals/water/" class="home-link">
                    Water for Life
                  </a>
                  <a href="https://sadaqaonline.org/all-appeals/cooked-food/" class="home-link01">
                    Feed the Hungry
                  </a>
                  <a href="https://sadaqaonline.org/all-appeals/masjid/" class="home-link02">
                    Build a Masjid
                  </a>
                  <a href="https://sadaqaonline.org/all-appeals/rozgar-project/" class="home-link03">
                    Provide Livelihood
                  </a>
                  <a href="https://sadaqaonline.org/all-appeals/eye-surgeries/" class="home-link04">
                    Eye Care Project
                  </a>
                  <a href="https://sadaqaonline.org/all-appeals/maktab/" class="home-link05">
                    Support a Maktab
                  </a>
                </div>
              </div>
              <div class="home-container3">
                <div class="home-product-container1">
                  <span class="home-text01" style="color: black">Primary Pages</span>
                  <a href="https://sadaqa-online.vercel.app/" class="home-link06">
                    Home
                  </a>
                  <a href="https://sadaqa-online.vercel.app/about" class="home-link07">
                    About Us
                  </a>
                  <a href="https://sadaqa-online.vercel.app/services" class="home-link08">
                    Services
                  </a>
                  <a href="https://sadaqa-online.vercel.app/privacy-policy" class="home-link09">
                    Privacy Policy
                  </a>
                  <a href="https://sadaqa-online.vercel.app/terms-conditions" class="home-link10">
                    Terms & Conditions
                  </a>
                  <a href="https://sadaqa-online.vercel.app/contact" class="home-link11">
                    Contact
                  </a>
                </div>
              </div>

              <div class="home-container4" style="color: black">
                <div class="home-product-container2" style="color: black">
                  <span class="home-text02" style="color: black">

                    <span>Utility Pages</span>
                    <br />
                  </span>
                  <a href="https://sadaqa-online.vercel.app/signup" class="home-link12">
                    Signup
                  </a>
                  <a href="https://sadaqa-online.vercel.app/login" class="home-link13">
                    Login
                  </a>

                  <a href="https://sadaqa-online.vercel.app" class="home-link14">
                    404 Not Found
                  </a>
                  <a href="https://sadaqaonline-donate.tscube.co.in/" class="home-link15">

                    Password Reset
                  </a>
                </div>
              </div>
              <div class="home-container5">
                <div style="width: 100%; height: 100%; position: relative; margin-left: 80px">
                  <div style="height: 27.93px; padding-right: 76.97px; left: 0px; top: 0px; position: absolute; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                      <div style="color: black; font-size: 21px; font-family: DM Sans; font-weight: 700; text-transform: capitalize; line-height: 27.93px; word-wrap: break-word">Subscribe to our newsletter</div>
                  </div>
                  <div style="height: 50px; padding-right: 67.97px; left: 0px; top: 51.93px; position: absolute; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                      <div style="align-self: stretch; height: 50px; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                          <div style="align-self: stretch; padding-top: 13.50px; padding-bottom: 14.50px; padding-left: 25px; padding-right: 70px; background: white; border-radius: 50px; border: 1px black solid; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                              <div style="padding-right: 56px; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                                  <div style="color: #9CA3AF; font-size: 18px; font-family: Inter; font-weight: 400; word-wrap: break-word">Enter your email</div>
                              </div>
                          </div>
                          {{-- <div style="width: 72px; padding-left: 24px; padding-right: 24px; padding-top: 7.50px; padding-bottom: 7.50px; background: black; border-radius: 50px; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                              <div style="align-self: stretch; height: 25px; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                                  <div style="width: 24px; height: 25px; flex-direction: column; justify-content: center; align-items: center; display: flex">
                                      <div style="width: 24px; height: 25px; position: relative">
                                          <div style="width: 18px; height: 14px; left: 3px; top: 6px; position: absolute; border: 2px white solid"></div>
                                      </div>
                                  </div>
                              </div>
                          </div> --}}
                      </div>
                  </div>
                  <div style="left: 10px; top: 126px; position: absolute; justify-content: flex-start; align-items: flex-start; gap: 22px; display: inline-flex">
                      <div style="width: 40px; height: 40px; padding: 12px; background: #0f4f7b; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                          <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <i class="fa fa-instagram icons-footer" aria-hidden="true"></i>
                          </div>
                      </div>
                      <div style="width: 40px; height: 40px; padding: 12px; background: #0f4f7b; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                          <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <i class="fa fa-pinterest icons-footer" aria-hidden="true"></i>
                          </div>
                      </div>
                      <div style="width: 40px; height: 40px; padding: 12px; background: #0f4f7b; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                          <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <i class="fa fa-twitter icons-footer" aria-hidden="true"></i>
                          </div>
                      </div>
                      <div style="width: 40px; height: 40px; padding: 12px; background: #0f4f7b; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                          <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <i class="fa fa-facebook icons-footer" aria-hidden="true"></i>
                          </div>
                      </div>
                      <div style="width: 40px; height: 40px; padding: 12px; background: #0f4f7b; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                          <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <i class="fa fa-google icons-footer" aria-hidden="true"></i>
                          </div>
                      </div>
                  </div>
              </div>

                <div class="home-contact">
                </div>
                <div class="home-socials"></div>
              </div>
            </div>
          </div>
          <div class="home-separator"></div>
          <span class="home-text14"  style="color: black">
            © Copyright 2023. All Rights Reserved by Sadaqa Online.
          </span>
        </footer>
      </div>
    <!-- END FOOTER -->

    </div>

    <!-- Plugins js -->
    <script src=" {{ URL::asset('assets/scripts/plugins.js?v=' . time()) }} "></script>

    <!-- Jquery Mask js -->
    <script src=" {{ URL::asset('assets/scripts/jquery.mask.min.js') }} "></script>
    <!-- Jquery blockUI js -->
    <script src=" {{ URL::asset('assets/scripts/jquery.blockUI.js') }} "></script>

    <!-- Main js -->
    <script src=" {{ URL::asset('assets/scripts/main.js?v=' . time()) }} "></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!--Footer scripts-->
    @yield('footer_scripts')



</body>

</html>
