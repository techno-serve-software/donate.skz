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
    <link rel="icon" href="{{ URL::asset('assets/images/SKZLogo.png') }}" sizes="32x32" />
    <link rel="apple-touch-icon" href="{{ URL::asset('assets/images/SKZLogo.png') }}" />

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
                        <a href=" //{{ config('icharms.website_url') }} "><img height="57px"
                                src="{{ URL::asset('assets/images/SKZLogo.png') }}" style="margin-top:0px"
                                alt="SKZ Foundation"></a>
                    </div>
                    <nav class="main-nav">
                        <ul>

                            <li class="main-nav-item">
                                <a class="main-nav-link" href="{{ config('icharms.website_url') }}">Home</a>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://skzfoundation.uk/about-founder/">
                                    About Founder</a>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://skzfoundation.uk/our-projects/">Projects</a>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link">
                                    Ramadan Appeal
                                </a>
                                <ul role="menu" class=" dropdown-menu" style="background-color: white;">
                                    <li id="menu-item-12977"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12977">
                                        <a title="Ramadan 2024" href="https://skzfoundation.uk/ramadan/">Ramadan
                                            2024</a></li>
                                    <li id="menu-item-12976"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12976">
                                        <a title="Iftar Drives" href="https://skzfoundation.uk/iftar-drives/">Iftar
                                            Drives</a></li>
                                    <li id="menu-item-12978"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12978">
                                        <a title="Ration Packs Distribution"
                                            href="https://skzfoundation.uk/ration-packs-distribution/">Ration Packs
                                            Distribution</a></li>
                                    <li id="menu-item-12980"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12980">
                                        <a title="Fitrana and Eid Gifts"
                                            href="https://skzfoundation.uk/fitrana-and-eid-gifts/">Fitrana and Eid
                                            Gifts</a></li>
                                    <li id="menu-item-12981"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-12981">
                                        <a title="Zakat" href="https://skzfoundation.uk/zakat/">Zakat</a></li>
                                    <li id="menu-item-13203"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-13203">
                                        <a title="Zakat Calculator"
                                            href="https://skzfoundation.uk/zakat-calculator/">Zakat Calculator</a></li>
                                </ul>
                            </li>
                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://skzfoundation.uk/blogs/">
                                    Blog</a>
                            </li>

                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://skzfoundation.uk/events/">Event</a>
                            </li>

                            <li class="main-nav-item">
                                <a class="main-nav-link" href="https://skzfoundation.uk/contact-us/">Contact Us</a>
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
                    <img alt="logo" src="{{ URL::asset('assets/images/SKZLogo.png') }}" height="144px;"
                        class="home-image" />
                    <div>

                    </div>
                    <p class="font_paragraph ft" id="ft1"
                        style="margin-top: 39px;text-align: justify;width: 300px;">We believe that only education and
                        skill development can empower youth, shaping their futures and substantiating the deprived
                        community. </p>
                    <br>
                    <a class="btn btn-danger" href="https://skzfoundation.uk/about-founder/" role="button"
                        style="background-color: red;">Read More</a>

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
                            <span class="home-text01 ft">USEFUL LINKS</span>
                            <a href="https://skzfoundation.uk/faqs/" class="home-link06 ft">
                                FAQ&#8217;s
                            </a>
                            <a href="https://skzfoundation.uk/gift-aid-policy/" class="home-link07 ft">
                                Gift Aid
                            </a>
                            <a href="https://skzfoundation.uk/our-team/" class="home-link08 ft">
                                Our Team
                            </a>
                            <a href="https://skzfoundation.uk/our-policy/" class="home-link09 ft">
                                Our Policy
                            </a>
                            <a href="https://skzfoundation.uk/shaping-minds-with-technology" class="home-link10 ft">
                                Digital Future
                            </a>
                            <a href="https://skzfoundation.uk/newsletter/" class="home-link11 ft">
                                Newsletter
                            </a>
                            <a href="https://skzfoundation.uk/wp-content/uploads/2019/05/Compressed-SKZ-Annual-Achievement-Report-2022.pdf"
                                class="home-link11 ft">
                                Reports
                            </a>
                            <a href="https://skzfoundation.uk/privacy-policy/" class="home-link11 ft">
                                Privacy Policy
                            </a>

                            <a href="https://skzfoundation.uk/terms-and-conditions/" class="home-link11 ft">
                                Terms and Conditions
                            </a>
                        </div>
                    </div>

                    <div class="home-container4">
                        <div class="home-product-container2">
                            <span class="home-text02">

                                <span>BANK DETAILS</span>
                                <br />
                            </span>
                            <a href="https://skzfoundation.uk/accept-payments" class="home-link12 ft">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                Credit Card
                            </a>
                            <a href="#" class="home-link13 ft">
                                <span class="detail_name">Bank Name:</span> Bank Islami
                            </a>

                            <a href="#" class="home-link14 ft">
                                <span class="detail_name"> Account Title:</span> Saya E Khuda E Zuljalal
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name">Account Number:</span> 2053-5712157-0001
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name">IBAN NUMBER:</span> <br> PK60BKIP0205357121570001
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name"> Bank Name:</span> Cashplus Bank
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name"> Account Title:</span> Saya E Khuda E Zuljlal
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name"> Account Number:</span> 07845728
                            </a>
                            <a href="javascript:void(0)" class="home-link15 ft">
                                <span class="detail_name">Sort Code:</span> 08-71-99
                            </a>
                        </div>
                    </div>
                    <div class="home-container5">
                        <div style="height: 100%; position: relative;">
                            <span class="home-text03">

                                <span class="newsletter" style="width: 179%;">NEWSLETTER</span>
                                <br />
                            </span>

                            <p class="home-link15 newtext ft">
                                Subscribe to our newsletter to get the latest news.
                            </p>
                            {{-- <input type="email" name="form_fields[email]" id="form-field-email"
                                placeholder="Email" required="required" aria-required="true"
                                data-gtm-form-interact-field-id="0"><button type="button"
                                class="btn btn-danger">send</button> --}}

                            <div
                                style="margin-top: 10px; justify-content: flex-start; align-items: flex-start; gap: 33px; display: inline-flex">
                                <a href="https://www.youtube.com/@skz.foundation" target="_blank">
                                    <div class="elementor-grid-item"
                                        style="width: 40px; height: 40px; padding: 12px; background: #cd201f; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                                        <div
                                            style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                                            <span>

                                                <i class="fa fa-youtube-play icons-footer" aria-hidden="true"></i>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                                <a class="elementor-icon elementor-social-icon elementor-social-icon-youtube elementor-animation-grow elementor-repeater-item-8612d61"
                                    href="https://www.facebook.com/SKZngo/" target="_blank">
                                    <div
                                        style="width: 40px; height: 40px; padding: 12px; background: #3b5998; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                                        <div
                                            style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">

                                            <i class="fa fa-facebook icons-footer" style="padding-left: 2px;"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </a>
                                <a class="elementor-icon elementor-social-icon elementor-social-icon-linkedin elementor-animation-grow elementor-repeater-item-c3ee66e"
                                    href="https://www.linkedin.com/groups/9521530/" target="_blank">
                                    <div
                                        style="width: 40px; height: 40px; padding: 12px; background: #0077b5; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                                        <div
                                            style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">

                                            <i class="fa fa-linkedin  icons-footer" style="padding-left: 2px;"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </a>
                                <a class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-animation-grow elementor-repeater-item-cb1cae3"
                                    href="https://www.instagram.com/skzfoundation?igsh=MWpmOWwzMDIycG90cQ=="
                                    target="_blank">
                                    <div
                                        style="width: 40px; height: 40px; padding: 12px; background: #F7125F; border-radius: 9999px; justify-content: center; align-items: center; display: flex">
                                        <div
                                            style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">

                                            <i class="fa fa-instagram icons-footer" style="padding-left: 2px;"
                                                aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="home-contact">
                        </div>
                        <div class="home-socials"></div>
                    </div>
                </div>
            </div>
            <div class="home-separator"></div>
            <span class="home-text14" style="color: white">
                © Copyright 2023. All Rights Reserved by SKZ Foundation.
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
