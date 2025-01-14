@php $hasHtml = $pageTitle !== strip_tags($pageTitle); @endphp

<header class="main-header  style-one">
    <div class="header-top">
        <div class="top-inner clearfix">
            <div class="top-left pull-left">
                <ul class="info clearfix">
                    <li>
                        <i class="flaticon-home"></i> Calgary, AB
                    </li>
                    <li>
                        <i class="flaticon-open-email-message"></i>
                        <a href="mailto:{{ EMAIL_CONTACT }}">{{ EMAIL_CONTACT }}</a></span>
                    </li>
                    <li>
                        <i class="flaticon-clock"></i><a href="" onclick="openToNewTab(event)">{{ ROUTE_CONTACT }}</a>
                    </li>
                </ul>
            </div>
            <div class="top-right pull-right">
                <ul class="social-links clearfix">
                    <!-- <li><a href="#"><i class="fab fa-twitter"></i></a></li> -->
                    <!-- <li><a href="#"><i class="fab fa-facebook-f"></i></a></li> -->
                    <!-- <li><a href="https://www.instagram.com/novelinkimmigration/"><i class="fab fa-instagram"></i></a> -->
                    </li>
                    <!-- <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li> -->
                    <!-- <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li> -->
                </ul>
            </div>
        </div>
    </div>
    <div class="header-upper heade-booking-btn-">
        <div class="outer-container">
            <div class="outer-box clearfix">
                <div class="upper-left pull-left" style="padding:17px 0 7px 0;">
                    <div class="logo-box pull-left">
                        <figure class="logo">
                            <a href="{{ url('/') }}" class="custom-logo-link" rel="home"
                                aria-current="page"><img width="180" height="54"
                                    src="{{ asset('uploads/2020/08/novelink.png') }}" class="custom-logo"
                                    alt="Novelink" /></a>
                        </figure>
                    </div>
                    <!-- <div class="btn-box">
                        <a href="contact.html" class="theme-btn-one">Appointment<i class="flaticon-send"></i></a>
                    </div> -->
                </div>
                <div class="menu-area pull-right">

                    <div class="mobile-nav-toggler">
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                    </div>
                    <nav class="main-menu navbar-expand-md navbar-light">
                        <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                            <ul id="menu-primary-menu" class="navigation clearfix">
                                <li id="menu-item-439"
                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-7 current_page_item current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-439">
                                    <a href="{{ url('/') }}" aria-current="page">Home</a>
                                    <!-- <ul class="sub-menu"> -->
                                    <!-- <li id="menu-item-337" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-7 current_page_item menu-item-337"><a href="{{ url('/') }}/" aria-current="page">PERMANENT RESIDENCE</a></li> -->
                                    <!-- <li id="menu-item-339" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-339"><a href="{{ url('/') }}/home-page-02/">Info</a></li> -->
                                    <!-- <li id="menu-item-984" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-7 current_page_item current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-984"><a href="{{ url('/') }}/" aria-current="page">Header Style</a>
                                        <ul class="sub-menu">
                                            <li id="menu-item-986" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-7 current_page_item menu-item-986"><a href="{{ url('/') }}/" aria-current="page">Header Style 01</a></li>
                                            <li id="menu-item-985" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-985"><a href="https://novelinkimmigration.ca/home-page-02/">Header Style 02</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- </ul> -->
                                </li>
                                <li id="menu-item-338"
                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-338">
                                    <a href="{{ route('about') }}">{{ ROUTE_ABOUT }}</a>
                                </li>
                                <li id="menu-item-911"
                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-911">
                                    <a href="{{ route('perm') }}">{{ TITLE_PERM_RESIDENCE }}</a>
                                    <ul class="sub-menu">
                                        <li id="menu-item-455"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-455">
                                            <a href="{{ route('perm') }}">{{ TITLE_PERM_RESIDENCE }}</a>
                                        </li>
                                        <li id="menu-item-455"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-455">
                                            <a
                                                href="{{ route('fed-skilled-worker') }}">{{ TITLE_FED_SKILLED_WORKER }}</a>
                                        </li>
                                        <li id="menu-item-340"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-340">
                                            <a href="{{ route('cad-experience') }}">{{ TITLE_CAD_EXPERIENCE }}</a>
                                        </li>
                                        <li id="menu-item-453"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-453">
                                            <a
                                                href="{{ route('fed-skilled-trades') }}">{{ TITLE_FED_SKILLED_TRADES }}</a>
                                        </li>
                                        <li id="menu-item-912"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-912">
                                            <a href="{{ route('prov-nom') }}">{{ TITLE_PROV_NOM }}</a>
                                        </li>
                                        <li id="menu-item-913"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-913">
                                            <a href="{{ route('sponsor') }}">{{ TITLE_SPONSORSHIP }}</a>
                                        </li>
                                        <li id="menu-item-913"
                                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-913">
                                            <a href="{{ route('caregivers') }}">{{ TITLE_CAREGIVERS }}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li id="menu-item-452"
                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-452">
                                    <a href="{{ route('temp-res') }}">{{ TITLE_TEMP_RESIDENCE }}</a>
                                    <ul class="sub-menu">
                                        <li id="menu-item-457"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-457">
                                            <a href="{{ route('temp-res') }}">{{ TITLE_TEMP_RESIDENCE }}</a>
                                        </li>
                                        <li id="menu-item-457"
                                            class="menu-item menu-item-type-post_type menu-item-object-page menu-item-457">
                                            <a href="{{ route('study-permit') }}">{{ TITLE_STUDY_PERMIT }}</a>
                                        </li>
                                        <li id="menu-item-459"
                                            class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-459">
                                            <a href="{{ route('visitor-visa') }}">{{ TITLE_VISITOR_VISA }}</a>
                                        </li>
                                        <li id="menu-item-460"
                                            class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-460">
                                            <a href="{{ route('super-visa') }}">{{ TITLE_SUPER_VISA }}</a>
                                        </li>
                                        <li id="menu-item-461"
                                            class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-461">
                                            <a href="{{ route('work-permit') }}">{{ TITLE_WORK_PERMIT }}</a>
                                        </li>
                                        <!-- <li id="menu-item-462" class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-462"><a href="https://novelinkimmigration.ca/coaching/toefl/">TOEFL</a></li> -->
                                        <!-- <li id="menu-item-463" class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-463"><a href="https://novelinkimmigration.ca/coaching/sat-coaching/">SAT Coaching</a></li> -->
                                        <!-- <li id="menu-item-464" class="menu-item menu-item-type-post_type menu-item-object-coachings menu-item-464"><a href="https://novelinkimmigration.ca/coaching/skills-exam/">Skills Exam</a></li> -->
                                    </ul>
                                </li>
                                <li id="menu-item-910"
                                    class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-910">
                                    <a href="{{ route('citizenship') }}">{{ TITLE_CITIZENSHIP }}</a>
                                    <!-- <ul class="sub-menu">
                                        <li id="menu-item-451" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-451"><a href="https://novelinkimmigration.ca/service-page-01/">Service Page 01</a></li>
                                        <li id="menu-item-450" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-450"><a href="https://novelinkimmigration.ca/service-page-02/">Service Page 02</a></li>
                                        <li id="menu-item-465" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-465"><a href="https://novelinkimmigration.ca/service/business-visa/">Business Visa</a></li>
                                        <li id="menu-item-466" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-466"><a href="https://novelinkimmigration.ca/service/students-visa/">Students Visa</a></li>
                                        <li id="menu-item-467" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-467"><a href="https://novelinkimmigration.ca/service/immigration-visa/">Immigration Visa</a></li>
                                        <li id="menu-item-468" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-468"><a href="https://novelinkimmigration.ca/service/transit-visa/">Transit Visa</a></li>
                                        <li id="menu-item-469" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-469"><a href="https://novelinkimmigration.ca/service/tourists-visa/">Tourists Visa</a></li>
                                        <li id="menu-item-470" class="menu-item menu-item-type-post_type menu-item-object-service menu-item-470"><a href="https://novelinkimmigration.ca/service/diplomatic-visa/">Diplomatic Visa</a></li>
                                    </ul> -->
                                </li>
                                <!-- <li id="menu-item-776" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-776"><a href="https://novelinkimmigration.ca/blog/">Blog</a>
                                    <ul class="sub-menu">
                                        <li id="menu-item-777" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-777"><a href="https://novelinkimmigration.ca/?blog_style=2">Blog Grid</a></li>
                                        <li id="menu-item-778" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-778"><a href="https://novelinkimmigration.ca/blog/">Blog List</a></li>
                                        <li id="menu-item-779" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-779"><a href="https://novelinkimmigration.ca/country-to-offer-point-based-immigrations-copy/">Blog Details</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                        </div>
                    </nav>
                    <div class="menu-right-content clearfix pull-left">

                        <div class="support-box" style="padding: 0 55px 10px 105px">
                            <i class="fas fa-phone-volume" style="top: 6px; left: 25px"></i>
                            <p>Any Questions? Call us</p>
                            <h3><a href="tel:+1-587-(707)-(4206)">+1 587 707 4206</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sticky-header sty-btn-">
        <div class="auto-container">
            <div class="outer-box clearfix">
                <div class="logo-box pull-left">
                    <figure class="logo" style="padding: 19.5px 0px 5px 0px !important;">
                        <a href="{{ url('/') }}"><img width="160"
                                src="{{ asset('uploads/2020/08/novelink.png') }}" alt="Logo"></a>
                    </figure>
                </div>
                <div class="menu-area pull-right">
                    <nav class="main-menu clearfix">

                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <div class="close-btn"><i class="fas fa-times"></i></div>
    <nav class="menu-box">
        <div class="nav-logo">
            <a href="{{ url('/') }}"><img width="105" height="109"
                    src="{{ asset('uploads/2020/08/novelink.png') }}" alt="Logo"></a>
        </div>
        <div class="menu-outer"></div>
        <div class="contact-info">
            <ul class="info clearfix">
                <li><i class="flaticon-home"></i>Calgary, AB</li>
                <li><i class="flaticon-open-email-message"></i>{{ EMAIL_CONTACT }}</span></li>
                <li><i class="flaticon-clock"></i><a href="" onclick="openToNewTab(event)">{{ ROUTE_CONTACT }}</a></li>
            </ul>
        </div>
        <div class="social-links">
            <ul class="clearfix">
                <!-- <li><a href="#"><i class="fab fa-twitter"></i></a></li> -->
                <!-- <li><a href="#"><i class="fab fa-facebook-f"></i></a></li> -->
                <!-- <li><a href="https://www.instagram.com/novelinkimmigration/"><i class="fab fa-instagram"></i></a></li> -->
                <!-- <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li> -->
                <!-- <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li> -->
            </ul>
        </div>
    </nav>
</div>
<div data-elementor-type="wp-page" data-elementor-id="7" class="elementor elementor-7">
    <div class="elementor-inner">
        <div class="elementor-section-wrap">
            {{-- TODO:reduce the padding of the .page-title class below, try "padding: 100px 0px 100px 0px;"  --}}
            <section class="page-title page-breadcrumb">
                <div class="auto-container">
                    <div class="content-box">
                        <div class="title-box">
                            <h1>
                                @if ($hasHtml)
                                    {!! $pageTitle !!}
                                @else
                                    {{ $pageTitle }}
                                @endif
                            </h1>
                            <div class="dotted-box">
                                <span class="dotted"></span>
                                <span class="dotted"></span>
                                <span class="dotted"></span>
                            </div>
                        </div>
                        <ul class="bread-crumb">
                            @if (@isset($currentRouteName) and $currentRouteName != 'home')
                                <li>
                                    <i class="flaticon-home-1"></i>
                                    <span property="itemListElement" typeof="ListItem">
                                        <a property="item" typeof="WebPage" title="Go to Visarzo." href="/"
                                            class="home">
                                            <span property="name">Home</span>
                                        </a>
                                        <meta property="position" content="1">
                                    </span>
                                </li>
                                <li>
                                    @if ($hasHtml)
                                        {!! $pageTitle !!}
                                    @else
                                        {{ $pageTitle }}
                                    @endif
                                </li>
                            @endif
                            @if (@isset($currentRouteName) and $currentRouteName == 'home')
                                <div class="btn-box">
                                    <a href="" onclick="openToNewTab(event)" class="theme-btn-one"><i
                                            class="flaticon-send" style="margin-right: 10px;"></i>Book
                                        a Consultation</a>
                                </div>
                            @endif
                        </ul>
                    </div>
                </div>
            </section>
