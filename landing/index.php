<?php

    require_once '/var/www/nestogy.io/includes/landing/landing_header.php';

?>


    <!-- Sections:Start -->

    <div data-bs-spy="scroll" class="scrollspy-example">
        <!-- Hero: Start -->
        <section id="hero-animation">
            <div id="landingHero" class="section-py landing-hero position-relative">
                <img src="/includes/assets/img/front-pages/backgrounds/hero-bg.png" alt="hero background" class="position-absolute top-0 start-50 translate-middle-x object-fit-contain w-100 h-100" data-speed="1" />
                <div class="container">
                    <div class="hero-text-box text-center">
                        <h1 class="text-primary hero-title display-4 fw-bold">One dashboard to manage your whole MSP</h1>
                        <h2 class="hero-sub-title h6 mb-4 pb-1">
                            A <b>Modern</b> Enterprise Resource Planer<br class="d-none d-lg-block" />
                            Built for MSPs, by MSPs. Free for small teams.
                        </h2>
                        <div class="landing-hero-btn d-inline-block position-relative">
                            <span class="hero-btn-item position-absolute d-none d-md-flex text-heading">See the difference
                                <img src="/includes/assets/img/front-pages/icons/Join-community-arrow.png" alt="Join community arrow" class="scaleX-n1-rtl" /></span>
                            <a href="#landingPricing" class="btn btn-primary">Start for Free</a>
                        </div>
                    </div>
                    <div id="heroDashboardAnimation" class="hero-animation-img">
                        <a href="" target="_blank">
                            <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
                                <img src="/includes/assets/img/front-pages/landing-page/hero-dashboard-light.png" alt="hero dashboard" class="animation-img" data-app-light-img="front-pages/landing-page/hero-dashboard-light.png" data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" />
                                <img src="/includes/assets/img/front-pages/landing-page/hero-elements-light.png" alt="hero elements" class="position-absolute hero-elements-img animation-img top-0 start-0" data-app-light-img="front-pages/landing-page/hero-elements-light.png" data-app-dark-img="front-pages/landing-page/hero-elements-dark.png" />
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="landing-hero-blank"></div>
        </section>
        <!-- Hero: End -->

        <!-- Useful features: Start -->
        <section id="landingFeatures" class="section-py landing-features">
            <div class="container">
                <div class="text-center mb-3 pb-1">
                    <span class="badge bg-label-primary">Useful Features</span>
                </div>
                <h3 class="text-center mb-1">Everything you need to manage your business</h3>
                <p class="text-center mb-3 mb-md-5 pb-3">
                    Not just a set of tools, but a complete solution. Manage your business with ease and efficiency.
                </p>
                <div class="features-icon-wrapper row gx-0 gy-4 g-sm-5">
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/laptop.png" alt="laptop charging" />
                        </div>
                        <h5 class="mb-3">Robust Ticketing and Project Management</h5>
                        <hr>
                        <ul class="features-icon-description text-start">
                            <li> Manage all your tickets and projects in one place </li>
                            <li> Assign tickets to your team members </li>
                            <li> Track time spent on each ticket </li>
                            <li> Easily bill clients for time spent </li>
                            <li> Track asset ticket history </li>

                        </ul>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/rocket.png" alt="transition up" />
                        </div>
                        <h5 class="mb-3"> Documentation and Knowledge Base</h5>
                        <p class="features-icon-description">
                            <ul class="features-icon-description text-start">
                                <li> Create and manage documentation for your team and clients </li>
                                <li> Asset lifecycle management and tracking </li>
                                <li> License and Warranty tracking </li>
                                <li> Encrypted password management </li>
                                <li> Securely share documentation and files with clients </li>
                                <li> Document and track client network configurations </li>
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/paper.png" alt="edit" />
                        </div>
                        <h5 class="mb-3"> Invoicing and Accounting</h5>
                        <p class="features-icon-description">
                            <ul class="features-icon-description text-start">
                                <li> Create and send invoices to clients </li>
                                <li> Track payments and outstanding invoices </li>
                                <li> Manage expenses and track profit and loss </li>
                                <li> Create and manage quotes and proposals </li>
                                <li> Track time spent on projects and tickets </li>
                                <li> Manage and track client contracts </li>
                                <li> Track and manage taxes owed </li>
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/check.png" alt="3d select solid" />
                        </div>
                        <h5 class="mb-3"> Client Portal</h5>
                        <p class="features-icon-description">
                            <ul class="features-icon-description text-start">
                                <li> Give clients access to their tickets and documentation </li>
                                <li> Allow clients to create and manage tickets </li>
                                <li> Share documentation and files with clients </li>
                                <li> Allow clients to view and pay invoices </li>
                                <li> Create and manage client accounts </li>
                                <li> Track client assets and configurations </li>
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/user.png" alt="lifebelt" />
                        </div>
                        <h5 class="mb-3"> Marketing Automation</h5>
                        <p class="features-icon-description">
                            <ul class="features-icon-description text-start">
                                <li> Create and manage marketing campaigns </li>
                                <li> Track and manage leads and prospects </li>
                                <li> Create and manage newsletters and distribution lists </li>
                                <li> Track and manage social media campaigns </li>
                                <li> Track and manage email campaigns </li>
                                <li> Track and manage website traffic </li>
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-4 col-sm-6 text-center features-icon-box">
                        <div class="text-center mb-3">
                            <img src="/includes/assets/img/front-pages/icons/keyboard.png" alt="google docs" />
                        </div>
                        <h5 class="mb-3"> Collections and Termination Management</h5>
                        <p class="features-icon-description">
                            <ul class="features-icon-description text-start">
                                <li> Track and manage overdue invoices </li>
                                <li> Manage and track client terminations </li>
                                <li> Automatically suspend client accounts </li>
                                <li> Send scheduled reminders to clients </li>
                                <li> Track and manage collections </li>
                                <li> Manage and track client contracts </li>
                            </ul>
                        </p>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <a href="#landingPricing" class="btn btn-primary">Get Started</a>
                </div>

            </div>
        </section>
        <!-- Useful features: End -->

        <!-- Pricing plans: Start -->
        <section id="landingPricing" class="section-py bg-body landing-pricing">
            <div class="container">
                <div class="text-center mb-3 pb-1">
                    <span class="badge bg-label-primary">Pricing Plans</span>
                </div>
                <h3 class="text-center mb-1">Tailored pricing plans designed for MSPs</h3>
                <p class="text-center mb-4 pb-3">
                    Choose the best plan to fit your needs.
                </p>
                <div class="row gy-4 pt-lg-3">
                    <!-- Starter Plan: Start -->
                    <div class="col-xl-4 col-lg-6 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-center">
                                    <img src="/includes/assets/img/front-pages/icons/paper-airplane.png" alt="paper airplane icon" class="mb-4 pb-2 scaleX-n1-rtl" />
                                    <h4 class="mb-1">Starter</h4>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="">
                                            <span class="price-monthly h1 text-primary fw-bold mb-0">$0</span>
                                        </div>
                                        <div class="h6 text-muted mb-0 ms-1">You read that right</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Client Documentation & Tickets
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Newsletters and Distribution Lists
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Invoicing and Accounting
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Password Management
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Client Portal
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Email and Forum Support
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Up to <b>3</b> Seats or <b>5</b> Clients
                                        </h5>
                                    </li>
                                </ul>
                                <div class="d-grid mt-4 pt-3">
                                    <a href="register/?starter" class="btn btn-label-primary">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Plan: End -->

                    <!-- Favourite Plan: Start -->
                    <div class="col-xl-4 col-lg-6 mx-auto">
                        <div class="card border border-primary shadow-lg">
                            <div class="card-header">
                                <div class="text-center">
                                    <img src="/includes/assets/img/front-pages/icons/plane.png" alt="plane icon" class="mb-4 pb-2 scaleX-n1-rtl" />
                                    <h4 class="mb-1">Professional</h4>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="price-monthly h1 text-primary fw-bold mb-0">$19</span>
                                        <sub class="h6 text-muted mb-0 ms-1">Per User</sub>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Everything in <b>Starter</b>, plus:
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            GPT-4 Integration
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Advanced API Calls
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Marketing automation
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Support Flows (Checklists)
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Rush Support (< 6 Hours)
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Up to <b>5</b> Seats or <b>25</b> Clients
                                        </h5>
                                    </li>
                                </ul>
                                <div class="d-grid mt-4 pt-3">
                                    <a href="register/?pro" class="btn btn-primary">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Favourite Plan: End -->

                    <!-- Standard Plan: Start -->
                    <div class="col-xl-4 col-lg-12 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-center">
                                    <img src="/includes/assets/img/front-pages/icons/shuttle-rocket.png" alt="shuttle rocket icon" class="mb-4 pb-2 scaleX-n1-rtl" />
                                    <h4 class="mb-1">Enterprise</h4>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="price-monthly h1 text-primary fw-bold mb-0">$26*</span>
                                        <sub class="h6 text-muted mb-0 ms-1">Per User</sub>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Everything in <b>Professional</b>, plus:
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Detailed Audit Log
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Custom Dashboards
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Quickbooks® Integration
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Custom Permissions
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            Priority Support (< 2 Hours)
                                        </h5>
                                    </li>
                                    <li>
                                        <h5>
                                            <span class="badge badge-center rounded-pill bg-label-primary p-0 me-2"><i class="bx bx-check bx-xs"></i></span>
                                            <b>Unlimited</b> Seats
                                        </h5>
                                    </li>
                                </ul>
                                <div class="d-grid mt-4 pt-3">
                                    <a href="register/?enterprise" class="btn btn-label-primary">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Standard Plan: End -->
                </div>
                <!-- asterisk description -->
                <div class="text-center mt-3">
                    <p class="text-muted
                    ">* Asterisk denotes price is negotiable on larger subscriptions. 
                    <a href="https://twe.tech" target="_blank">Contact us</a>
                    if you have more than 10 users.</p>
                </div>
            </div>
        </section>
        <!-- Pricing plans: End -->

        <!-- Fun facts: Start 
        <section id="landingFunFacts" class="section-py landing-fun-facts">
            <div class="container">
                <div class="row gy-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-label-primary shadow-none">
                            <div class="card-body text-center">
                                <img src="/includes/assets/img/front-pages/icons/laptop.png" alt="laptop" class="mb-2" />
                                <h5 class="h2 mb-1">7.1k+</h5>
                                <p class="fw-medium mb-0">
                                    Support Tickets<br />
                                    Resolved
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-label-success shadow-none">
                            <div class="card-body text-center">
                                <img src="/includes/assets/img/front-pages/icons/user-success.png" alt="laptop" class="mb-2" />
                                <h5 class="h2 mb-1">50k+</h5>
                                <p class="fw-medium mb-0">
                                    Join creatives<br />
                                    community
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-label-info shadow-none">
                            <div class="card-body text-center">
                                <img src="/includes/assets/img/front-pages/icons/diamond-info.png" alt="laptop" class="mb-2" />
                                <h5 class="h2 mb-1">4.8/5</h5>
                                <p class="fw-medium mb-0">
                                    Highly Rated<br />
                                    Products
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card border border-label-warning shadow-none">
                            <div class="card-body text-center">
                                <img src="/includes/assets/img/front-pages/icons/check-warning.png" alt="laptop" class="mb-2" />
                                <h5 class="h2 mb-1">100%</h5>
                                <p class="fw-medium mb-0">
                                    Money Back<br />
                                    Guarantee
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        Fun facts: End -->

        <!-- FAQ: Start -->
        <section id="landingFAQ" class="section-py bg-body landing-faq">
            <div class="container">
                <div class="text-center mb-3 pb-1">
                    <span class="badge bg-label-primary">FAQ</span>
                </div>
                <h3 class="text-center mb-1">Frequently asked questions</h3>
                <p class="text-center mb-5 pb-3">Browse through these FAQs to find answers to commonly asked questions.</p>
                <div class="row gy-5">
                    <div class="col-lg-5">
                        <div class="text-center">
                            <img src="/includes/assets/img/front-pages/landing-page/faq-boy-with-logos.png" alt="faq boy with logos" class="faq-image" />
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="accordion" id="accordionExample">
                            <div class="card accordion-item active">
                                <h2 class="accordion-header" id="headingOne">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                        Do you offer a free trial?
                                    </button>
                                </h2>

                                <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        We offer an unlimited free trial to all of our users. You can try out the platform and all its features for as long as you want, no credit card required. The Starter tier is free, and limited to 15 Clients and Leads.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                                        Can I upgrade once we find we love this tool?
                                    </button>
                                </h2>
                                <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Upgrades and Downgrades are easy, manage your account via one click. You can upgrade or downgrade your plan at any time. If you upgrade, you will be prorated for the time you have left on your current plan. Downgrades will credit your account the remaining balance.
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                        What if I need help?
                                    </button>
                                </h2>
                                <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        Unlimited support is included with the Enterprise Plan. For Starter and Proffesional, free email support is provided; Phone and Remote support available via <a href="https://twe.tech" target="_blank">TWE Technologies</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                                        What makes Nestogy Different?
                                    </button>
                                </h2>
                                <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        We are built by MSPs, for MSPs. We understand the needs of the industry and have built a platform that is easy to use, and has all the features you need to manage your business. 
                                    </div>
                                </div>
                            </div>
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFive" aria-expanded="false" aria-controls="accordionFive">
                                        What RMM do you suggest using?
                                    </button>
                                </h2>
                                <div id="accordionFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        NinjaOne and TacticalRMM are the only supported RMMs for integration at the moment. While we want to expand this list, it is not currently on our roadmap.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FAQ: End -->
    </div>

    <!-- / Sections:End -->
<?php require_once '/var/www/nestogy.io/includes/landing/landing_footer.php'; ?>