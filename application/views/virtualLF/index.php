<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>TheEvent Bootstrap Template - Index</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/venobox/venobox.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/css/style_updated_v4.css" rel="stylesheet">


    <!-- =======================================================
  * Template Name: TheEvent - v2.2.0
  * Template URL: https://bootstrapmade.com/theevent-conference-event-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <style>
        /* .nav-link {
            color: #fff !important;
        }


        #intro::before {
            background: rgba(100, 100, 100, 0.4) !important;
        }

        #intro-p {
            color: #366 !important;
        }

        #intro-h1 {
            color: #366 !important;
        }

        #intro-h2 {
            color: #366 !important;
        }

        .intro-btn {
            color: #366 !important;
        }

        .intro-btn:hover {
            color: white !important;
        }

        #about::before {
            background: rgba(150, 150, 150, 0.4) !important;
        }

        #about-text * {
            color: #366 !important;
        }

        #subscribe-h2 {
            color: #366 !important;
        } */
    </style>
    <!-- ======= Header ======= -->
    <header id="header">
        <nav>
            <ul class="nav-menu">
                <li class="menu-active"><a class="nav-link" href="virtualLF">Home</a></li>
                <li><a class="nav-link" href="#about">About</a></li>
                <li><a class="nav-link" href="#schedule">Agenda</a></li>
                <li><a class="nav-link" href="#speakers">Speakers</a></li>
                <li><a class="nav-link" href="#sponsors">Sponsors</a></li>
                <li><a class="nav-link" href="#contact">Contact</a></li>
                <li><a class="nav-link" href="https://www.cg-la.com/privatemeetings">Private Meetings</a></li>
                <li><a class="nav-link" href="https://www.cg-la.com/store?category=GViP">Custom Services</a></li>
                <li class="buy-tickets"><a href="https://www.cg-la.com/store/global-registration-all-access">Register Now</a></li>


            </ul>
        </nav><!-- #nav-menu-container -->
        </div>
    </header><!-- End Header -->

    <!-- ======= Intro Section ======= -->
    <section id="intro">
        <div class="intro-container" data-aos="zoom-in" data-aos-delay="100">

            <h1 id="intro-h1" class="mb-4 pb-0"> <?php echo $details['title']; ?> </h1>
            <h2 id='intro-h2' style="font-size:2.5rem"> Real Opportunities, Bold Recovery </h2>
            <p id="intro-p" class="mb-4 pb-0">September 17, 2020</p>
            <!-- <a href="https://www.youtube.com/watch?v=fVkrz-W1rY4" class="venobox play-btn mb-4" data-vbtype="video" data-autoplay="true"></a>-->
            <div>
                <a href="#schedule" style="font-size:2rem" class="about-btn scrollto intro-btn">Agenda </a>
                <a href="https://www.cg-la.com/store/global-registration-all-access" style="font-size:2rem" class="about-btn intro-btn scrollto">Register Now</a>
            </div>
        </div>
    </section><!-- End Intro Section -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div id="about-text" class="col-lg-9">
                        <h2 id="about-h2">About The Event</h2>
                        <p id="about-p"><?php echo $details['content']; ?></p>
                    </div>
                    <div class="container">
                        <div class="row">
                            <a href="https://www.cg-la.com/privatemeetings" class="col-lg-6 thumbnail">
                                <div class="overlay">
                                    <h1 class="overlay-head">Private Meetings:</h1>
                                    <p class="overlay-text">
                                        Schedule one-on-one meetings with project owners and industry experts
                                        across the globe with our private meeting system. See who is available
                                        to build your business now!
                                    </p>
                                </div>
                                <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/about/Private_Meeting.jpg" class="img-fluid w-100" alt="" />
                            </a>
                            <a href="https://www.cg-la.com/virtualtradeshow" class="col-lg-6 thumbnail">
                                <div class="overlay">
                                    <h1 class="overlay-head">Virtual Trade Show:</h1>
                                    <p class="overlay-text">
                                        Our virtual trade show booth experience is aninteractive experience!
                                        Check out our exhibition booths, chat with sponsors live, enter
                                        raffles to win!
                                    </p>
                                </div>
                                <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/about/Virtual_Private_Tradeship.jpg" class="img-fluid w-100" alt="" />
                            </a>
                            <a href="https://www.cg-la.com/store" class="col-lg-6 thumbnail">
                                <div class="overlay">
                                    <h1 class="overlay-head">GVIP Member Services</h1>
                                    <p class="overlay-text">
                                        Harness the power of GVIP On time solutions, Expert Insight,
                                        Unprecedented connectivity.Make projects happen.
                                    </p>
                                </div>
                                <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/about/GVIPmember1.jpg" class="img-fluid w-100" alt="" />
                            </a>
                            <a href="https://www.gvip.io/gviptv" class="col-lg-6 thumbnail">
                                <div class="overlay">
                                    <h1 class="overlay-head">GVIPTV</h1>
                                    <p class="overlay-text">
                                        Next Generation TV. Vital discussions with infra insider - exclusive
                                        interviews with industry experts.
                                    </p>
                                </div>
                                <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/about/GVIPTV.jpg" class="img-fluid w-100" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End About Section -->




        <!-- ======= Schedule Section ======= -->
        <section id="schedule" class="section-with-bg">
            <div class="container-fluid" data-aos="fade-up">
                <div class="section-header">
                    <h2>Agenda Preview</h2>
                </div>

                <ul class="nav nav-tabs" role="tablist" data-aos="fade-up" data-aos-delay="100">
                    <li class="nav-item">
                        <a class="nav-link active" href="#day-1" role="tab" data-toggle="tab">Track A</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#day-2" role="tab" data-toggle="tab">Track B</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#day-3" role="tab" data-toggle="tab">Strategic Workshops</a>
                    </li>
                </ul>

                <h3 class="sub-heading">At a time of enormous change worldwide, we bring together top leadership to discuss global economic recovery and develop 50+ real project opportunities in the Global Marketplace (a $2.5 trillion annual market), across all infrastructure sectors – transportation, energy, water/wastewater and digitization. “Vision and Leadership; Creating Tomorrow’s World” brings the major projects together with key funders, critical technology disruptors and great designers and builders.</h3>

                <div class="tab-content row justify-content-center" data-aos="fade-up" data-aos-delay="200">

                    <!-- Schdule Day 1 -->
                    <div role="tabpanel" class="col-lg-9 tab-pane fade show active" id="day-1">
                        <h2>Track A: Plenary</h2>
                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>7:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10 ">
                                <h4>Top 5 Asian Projects Live (Breakfast in Washington, Lunch in Europe & Dinner in Asia)!</h4>
                                <br>
                                <p>Special Keynote:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/4360" class="schedule-link">Parag Khanna, Founder & Managing Partner, FutureMap</a></strong></p>
                                <br>
                                <p>Parag Khanna is a leading global strategy advisor, world traveler, and best-selling author. He is Founder & Managing Partner of FutureMap, a data and scenario based strategic advisory firm. Parag’s newest book, The Future is Asian: Commerce, Conflict & Culture in the 21st Century(2019), presents this irrepressible global Asianization through detailed analysis, data and maps of Asia’s major markets and their combined impact on global economy, society and governance.</p>
                                <br />
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/ali-jinnah-supper-specialty-hospital" class="schedule-link">Ali Jinnah 250 Bed Hospital, Afghanistan</strong>(Afghanistan)</p>
                                <p><strong><a href="https://www.gvip.io/projects/the-clara-plan" class="schedule-link">The Clara Plan</a></strong>(Australia)</p>
                                <p><strong>Digital Silk Way</strong>(Georgia, Azerbaijan, Kazakhstan)</p>
                                <p><strong><a href="https://www.gvip.io/projects/sinma-or-nga-yok-bay-area-development-deep-sea-port-project-ayeyarwady-region-myanmar" class="schedule-link">Sinma Deepwater Port</a></strong>(Myanmar)</p>
                                <p><strong><a href="https://www.gvip.io/projects/aqaba-amman-water-desalination-conveyance-project-aawdc" class="schedule-link">Aqaba – Amman Water Desalination & Conveyance Project</a></strong>(AAWDC) (Jordan)</p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>8:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Bom Dia Brasil - Top 5 Brazilian Projects Live!</h4>
                                <br>
                                <p>Keynote:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Roberto Escoto, Manager, Investment Department, APEX</a></strong></p>
                                <br />
                                <p>Key Brazilian Federal and State Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/pampulha-airport-concession" class="schedule-link">Pampulha Airport Concession</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/alcantara-port-terminal-tpa" class="schedule-link">Alcantara Port Terminal</a></strong>(TPA) (Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/metropolitan-integrated-transit-system" class="schedule-link">Metropolitan Integrated Transit System, Florianopolis</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/metropolitan-train-lines-8-diamond-and-9-emerald" class="schedule-link">Metropolitan Train Lines 8 and 9, São Paulo</a></strong>(Brazil)</p>
                            </div>

                            <div class="col-md-2">
                            </div>

                            <div class="col-md-10">

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>9:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Top North American Strategic Projects </h4>
                                <p>Projects that are critical to country growth</p>
                                <br>
                                <p>Special Keynote - Key Actions to Drive Global Growth:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Andrew Robb, Former Minister of Trade and Investment, Government of Australia </a> </strong></p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/great-lakes-basin-railroad" class="schedule-link">Great Lakes Transportation Basin</a></strong>(United States)</p>
                                <p><strong><a href="https://www.gvip.io/projects/calcasieu-river-bridge-i-10-ppg-drive-us-90e" class="schedule-link">Calcasieu River Bridge</a></strong> (United States)</p>
                                <p><strong><a href="https://www.gvip.io/projects/the-mayan-train" class="schedule-link">Mayan Train</a></strong>(Mexico)</p>
                                <p><strong><a href="https://www.gvip.io/projects/jfk-airport-modernization" class="schedule-link">JFK Terminal One Redevelopment</a></strong>(United States)</p>

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>10:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>"Innovation & Infrastructure - The Role of the Global CEO"</h4>
                                <br>
                                <p>Keynote:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Andrés Gluski, CEO, AES Corporation</a></strong></p>
                                <br />
                                <p>Andrés Gluski has served as President and CEO since 2011, after serving as COO for five years. He has led AES through a dramatic transformation by focusing on simplification, risk improvement and innovation - including a $5 billion divestiture program, dramatically increased credit rating and the initiation of a quarterly dividend, which has grown at an 8% annual rate. Under his leadership, AES has become a world leader in implementing new clean technologies, including energy storage, wind, solar and LNG.</p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>10:30 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Owning & Operating Major Infrastructure Assets</h4>
                                <br>
                                <p>Panelist:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Javier Perez Fortea, CEO, Globalvia</a></strong></p>
                                <br />
                                <p>Mr. Perez Fortea joined GLOBALVIA in 2011 and has been serving as Chief Executive Officer since 2013. Mr. Perez Fortea has been responsible for the financing, construction and operation of infrastructure projects in 14 countries in 3 different continents. He worked on a US $ 2.5 billion railroad PPP project in Manila (Philippines), in Indonesia he built a US $500 Million New Town in the rainforest of Irian Jaya with 3,500 local workers in only 18 months.</p>
                            </div>
                        </div>


                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>11:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Insight Discussion - The Global Economy, 2020 - 2023</h4>
                                <p>Given the 'fog of war' nature of the current global economy, this panel - made up of investors, operators and policymakers, critical thinkers all, will discuss the most likely scenarios for the next three years - and how they think about that 36 month period.</p>
                                <br>
                                <p>Panelists:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/897" class="schedule-link">Santiago Castagnino, Managing Director and Partner, BCG</a> </strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Joaquim Levy, Chief Economist, Safra bank and former Minister of Finance, Brazil</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Jim Pass, Senior Managing Director, Guggenheim Partners</a></strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>12:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>The Path Forward - U.S. Leadership and the Global Recovery</h4>
                                <br>
                                <p>Special Policy Keynote:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Joseph Semsar, Deputy Under Secretary for International Trade, US Department of Commerce</a></strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>12:50 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker" id="oracle-specific">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/oracle.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Oracle Project of the Year Awards:</h4>
                                <p>Each year working with Oracle we identify and select the best global projects from an initial list of as many as 250 projects across the globe. The next step is to have people worldwide vote on a long list, reducing the candidates from 5-3 projects - and finally our sponsors and Forum participants, the creme de la creme of infrastructure - choose the winners. This year the awards will be broadcasted, on GViP TV! </p>
                                <br>
                                <p>Special Strategic Keynote:</p>
                                <p><strong><a href="#speakers" class="schedule-link">David H. Petraeus, Chairman, KKR Global Institute and Former Director, Central Intelligence Agency</a></strong></p>
                                <br>
                                <p>Categories include:</p>
                                <p><strong>- Strategic Project of the Year</strong></p>
                                <p><strong>- Engineering Project of the Year</strong></p>
                                <p><strong>- Finance Project of the Year</strong></p>
                                <p><strong>- Sustainability/Green Project of the Year</strong></p>
                                <p><strong>- Job Creation Project of the Year</strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>1:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>New Technologies Driving Real Opportunity for Improved Project Performance & Economic Growth:</h4>
                                <p>We bring people together to highlight new and visionary technology applications that will change our world - and that offer immediate opportunities to project developers, business executives and national policymakers.</p>
                                <br>
                                <p>Panelists:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/4103" class="schedule-link">Jennifer Schmitz, Founder and CEO, Lattice Industries, Inc.</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Lisa Stine, Technical Solutions Executive, Construction, Autodesk</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/3772" class="schedule-link">Tim Beck, Principal, Strategic Transportation Solutions, Oracle</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/4048" class="schedule-link">Dev Amratia, Co-Founder & CEO, nPlan</a></strong></p>

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>2:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Revitalizing Emerging Market Economies :</h4>
                                <br>
                                <p>Thought Leaders:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Nicolas Firzli, Director General, World Pensions Council</a> </strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Fernando Marcatto, Infrastructure Secretary, State of Minas Gerais, Brazil</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Andrew Charlesworth, CEO, CAMG Infrastructure</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/4269" class="schedule-link">Mark Davis, Chief Infrastructure Officer, Alaska Industrial Development and Export Authority</a></strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>3:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Bringing Supply Chains Home - The Case of Rare Earths and Speciality Metals</h4>
                                <p>Covid has highlighted the fact that many countries around the world have dangerously exported their ability to produce and/or make necessities strategic to their economic health. This includes PPE as well as medicines, and also includes data collection and analytics and - most critically - rare earth and specialty metals. This panel brings together critical rare earths and specialty metals actors focused on Niobium in the Americas.</p>
                                <br>
                                <p>Special Case Study:</p>
                                <p><strong>The Niobium Elk Creek Project, Nebraska</strong></p>
                                <br>
                                <p>Panelists:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Sarah Ryker, Associate Director, Energy and Minerals, USGS</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Mark Smith, NioCorp Developments Ltd.</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Eric Jones, Managing Director, U.S. International Development Finance Corporation
                                        </a></strong></p>

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>4:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>National Infrastructure Banks - Driving Economic Recovery:</h4>
                                <p>Infrastructure banks can focus and drive investment during a period of economic crisis, and so this panel looks at the priorities of such institutions, and how their capacity can be enhanced. Critical issues include funding for feasibility studies, crowding out private investment, and politicization. </p>
                                <br>
                                <p>Panelists:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/4154" class="schedule-link">William T. Nolan, President, Infra-Bk, LLC</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">John Casola, Chief Investment Officer, Canada Infrastructure Bank</a></strong></p>
                                <p><strong><a href="#spakers" class="schedule-link">Francisco Gomez, Vice President of Strategy & Development, FDN</a></strong>(Colombia)</p>
                                <p><strong><a href="https://www.gvip.io/expertise/2072" class="schedule-link">Brian Ross, CEO, InfraShares </a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/2759" class="schedule-link">Pablo Perreira dos Santos, Manager, Infrastructure - Inter-American Development Bank</a></strong></p>
                            </div>
                        </div>
                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>5:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>The View from Space - Business Opportunities that will change Life on Earth</h4>
                                <p>The ability to see, measure and process data from space about the earth - minerals, public safety, agriculture - is going to transform how we live and work</p>
                                <br>
                                <p>Moderator:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/28" class="schedule-link">Norman Anderson, CEO, CG/LA Infrastructure</a></strong></p>
                                <br>
                                <p>Thought Leader Discussion:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Erlend Olsen, Theia Satellite Project</a></strong>(United States)</p>
                                <p><strong><a href="#speakers" class="schedule-link">Dr. Joseph N. Pelton, Executive Board, International Association for the Advancement of Space Safety (IAASS)</a></strong></p>
                            </div>
                        </div>


                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>6:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>First Annual Global Benefits Prize:</h4>
                                <p>The world needs a better way to think about infrastructure, and how to prioritize projects. Technology allows us to increasingly focus on users - and on the results of projects from the user perspective. This includes health, mobility, job creation, business creation and overall opportunity creation. Forty-two projects, from all sectors, have entered this competition - after a discussion the winners will be announced! </p>
                                <br>
                                <p>Discussion Leaders:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/3976" class="schedule-link">Mark Freedman, Managing Director, Dalberg</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Prof. Dr.-Ing. Michael Bühler, HTCW - Constance, Construction Economics</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/2759" class="schedule-link">Pablo Perreira dos Santos, Manager, Infrastructure Manager, InterAmerican Development Bank</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Stephen Townsend, Network Engagement </a></strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>7:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Real Opportunities, Bold Recovery - Action Items From Around the World:</h4>
                                <p>(Breakfast in Tokyo, Dinner in the U.S., and late Dinner in Madrid!)</p>
                                <br>
                                <p>The wrap-up will focus on action items compiled from discussions throughout the day. The overall aim of this 13th Global Strategic Infrastructure Leadership Forum is swift and robust economic recovery. We believe that infrastructure is key, and so the recommendations will focus on that topic.</p>
                            </div>
                        </div>

                    </div>
                    <!-- End Schdule Day 1 -->

                    <!-- Start Track B -->
                    <div role="tabpanel" class="col-lg-9  tab-pane fade" id="day-2">
                        <h2>Track B: Projects</h2>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>9:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Top European Strategic Projects</h4>
                                <p>Projects that are critical to rejuvinating the region. </p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/new-central-polish-airport-cpk" class="schedule-link">Solidarity Transport Hub</a></strong>(Poland)</p>
                                <p><strong><a href="https://www.gvip.io/projects/fehmarn-belt-fixed-link" class="schedule-link">Fehmarn Belt Fixed Link</a></strong>(Denmark-Germany)</p>
                                <p><strong><a href="https://www.gvip.io/projects/west-africa-rail-network" class="schedule-link">Rail Baltica</a></strong> (Estonia-Latvia-Lithuania)</p>
                                <p><strong>Ankara-Niğde Highway</strong>(Turkey)</p>

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>10:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Water/Wastewater:</h4>
                                <p>The water business is the great forgotten infrastructure, and one that is critical for realizing the benefits of infrastructure investment for the hardest hit members of the global community.</p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong>Hydropower Power Plants Revitalization Project & PRASA Metering Project</strong>(Puerto Rico)</p>
                                <p><strong>Vita Ambient Water Performance Contracts</strong>(Brazil)</p>
                                <p><strong>Vita Ambient Water Performance Contracts</strong>(Brazil)</p>
                                <p><strong>Headworks Water and Sanitation Project</strong>(Peru)</p>
                                <p><strong><a href="https://www.gvip.io/projects/cadiz-valley-water" class="schedule-link">Cadiz Water Conveyance</a></strong>(United States)</p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>11:00 AM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Transit Networks:</h4>
                                <p>Mobility is one of the key values for infrastructure investment, and we present some of the best global opportunities for infrastructure project development.</p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/concession-of-belo-horizontes-bus-terminal-tergip" class="schedule-link">Concession of Belo Horizonte's Bus Terminal</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/lote-litoral-paulista-coastline-road-concession" class="schedule-link">Road Concession - State Coastline Lot A, Sao Paulo </a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/ontario-line-subway" class="schedule-link">Ontario Line Subway</a></strong>(Canada)</p>
                                <p><strong><a href="https://www.gvip.io/projects/ferropista-en-la-linea-colombia" class="schedule-link">Ferropista Railway</a></strong>(Colombia)</p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>1:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Highways, Bridges & Tunnels:</h4>
                                <p>The largest single category of infrastructure investment, these projects are transforming the productive capacity of countries worldwide, and have enormous potential to drive economic recovery.</p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/rodoanel-concession-program" class="schedule-link">Road Concessions Program, Rodoanel Concession Program, Minas Gerais</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/gordie-howe-international-bridge-detroit-mi-windsor-on" class="schedule-link">Gordie Howe International Bridge</a></strong>(Canada)</p>
                                <p><strong><a href="https://www.gvip.io/projects/peripheral-ring-road" class="schedule-link">Peripheral Ring Road</a></strong>(Peru)</p>
                                <p><strong>Aydin- Denizli and Nakkas-Basaksehir Highway Projects </strong>(Turkey)</p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>2:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Smart & Sustainable Infrastructure:</h4>
                                <p>In fact, all infrastructure going forward will be ‘smart,’ and the opportunity now is to create a strategy for commercializing data to both securitize investments, and pay for new investments.</p>
                                <br>
                                <p>Thought Leaders::</p>
                                <p><strong><a href="#speakers" class="schedule-link">Jeffrey DeCoux, Chairman, Autonomy Institute</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/4062" class="schedule-link">Michael J. Woods, CEO and COO, Big Sun Holdings Group Corporation</a></strong></p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/contra-costa-countywide-digital-infrastructure-project" class="schedule-link">ContraCosta Project</a> - <a href="https://www.gvip.io/expertise/4103" class="schedule-link">Jennifer Schmitz, Founder and CEO, Lattice Industries, Inc.</a></strong>(United States)</p>
                                <p><strong><a href="https://www.gvip.io/projects/ibirapuera-park-concession" class="schedule-link">Ibirapuera Park Concession & Zoo and Botanical Garden Project</a> - <a href="#speakers" class="schedule-link">Gabriela Engler, Executive Secretary for Partnerships, Government of the State of São Paulo </a></strong>(Brazil)</p>
                                <p><strong>Clara Project - <a href="https://www.gvip.io/expertise/3816" class="schedule-link"> Mike Day, Director, Clara Smart Cities</a></strong></p>
                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>3:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Ports:</h4>
                                <p>Leading Ports projects offer tremendous opportunities globally and are drivers of economic recovery and job creation.</p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong>Port of Tangier Med </strong>(Morocco)</p>
                                <p><strong>Port Privatization Program </strong>(Guinea - Bissau)</p>
                                <p><strong><a href="https://www.gvip.io/projects/hydroport-wales" class="schedule-link">HydroPort Wales</a></strong>(United Kingdom)</p>

                            </div>
                        </div>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>4:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Energy:</h4>
                                <p>The pace of transformative energy projects is tremendous, from renewable energy, to storage, to new ‘behind the meter’ technologies.</p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong>Azito plant</strong>
                                    <p>(Côte d’Ivoire)</p><strong>& Malindi PV Projects </strong>(Kenya)
                                </p>
                                <p><strong>Tire Recycling Waste-to-Energy Plant</strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/mauritania-hybrid-photovoltaic-power-plants" class="schedule-link">8 Hybrid Photovoltaic (PV) Power Plants</a></strong>(Mauritania)</p>
                                <p><strong><a href="https://www.gvip.io/projects/puerto-rico-electric-power-authority-utility-scale-energy-storage-system" class="schedule-link">Battery Energy Storage System & Flexible Distributed Energy Units</a></strong>(Puerto Rico)</p>

                            </div>
                        </div>


                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>5:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Water and Wastewater - Reviving Investment in the Forgotten Infrastructure:</h4>
                                <p>The focus is on identifying investment targets and strategies for this critical sector, often overlooked - but always considered the #1 priority by voters around the world. We combine key practitioners with policymakers and developers.</p>
                                <br />
                                <p>Panelists: </p>
                                <p><strong><a href="#speakers" class="schedule-link">Mohsen Mortada, President and CEO at Cole Engineering Group</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Nilton Seuaciuc, President, Vita Ambiental, Brazil</a> </strong>(Performance Contracts)</p>
                                <p><strong><a href="https://www.gvip.io/expertise/3493" class="schedule-link">Antonino Ferreira, International CEO, ACEA </a></strong>(Rome)</p>
                                <p><strong><a href="#speakers" class="schedule-link">Albert Cho, Vice President & General Manager, Advanced Infrastructure Analytics, Xylem</a></strong></p>
                            </div>
                        </div>


                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>6:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Rail:</h4>
                                <p>Some of the most exciting projects in the world are rail projects, driving critical connections between cities like São Paulo and Campinas, Dallas and Houston, and Tokyo and Kyoto. </p>
                                <br>
                                <p>Project Presentations:</p>
                                <p><strong><a href="https://www.gvip.io/projects/intercity-train-tic" class="schedule-link">São Paulo Intercity Trains project</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/fiol-i-west-east-integration-railway-part-one" class="schedule-link">FIOL & FICO Railways</a></strong>(Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/serra-gacha-regional-train" class="schedule-link">Serra Gaúcha Regional Commuter Train</a></strong> (Brazil)</p>
                                <p><strong><a href="https://www.gvip.io/projects/northeast-maglev-project" class="schedule-link">The Northeast Maglev</a></strong> (United States)</p>
                            </div>
                        </div>

                    </div>
                    <!-- End Schdule Track B-->

                    <!-- Start Track C -->
                    <div role="tabpanel" class="col-lg-9  tab-pane fade" id="day-3">
                        <h2>Track C: Strategic Workshops</h2>

                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>5:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4> US Deal Team Discussion:</h4>
                                <br>
                                <p> Thought Leaders:</p>
                                <p><strong><a href="https://www.gvip.io/expertise/1774" class="schedule-link">Craig O'Connor, Director, Office of Renewable Energy, Export-Import Bank of the United States</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Pat Kirwan, Director, TPCC, United States Department of Commerce </a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Fernando Marcatto, Infrastructure Secretary, State of Minas Gerais, Brazil</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Paul Alvaro Marin, Director, Public Affairs (Acting), U.S. Trade and Development Agency</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">Roland de Marcellus, Deputy Assistant Secretary, US Department of State</a></strong></p>
                                <p><strong><a href="#speakers" class="schedule-link">U.S. International Development Finance Corporation</a></strong></p>

                            </div>
                        </div>
                        <div class="row schedule-item">
                            <div class="col-md-2">
                                <time>6:00 PM EST</time>
                                <form method="POST" action="#">
                                    <div class="form-row">
                                        <div class="col-auto">
                                            <button type="submit">Join Session</button>
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div class="speaker">
                                    <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/Agenda/CGLA_newlogo.jpg" alt="Norman Anderson">
                                </div>
                            </div>
                            <div class="col-md-10">
                                <h4>Benefits Index Creation:</h4>
                                <br>
                                <p>Thought Leaders:</p>
                                <p><strong><a href="#speakers" class="schedule-link">Stephen Townsend, Network Engagement Facilitator, Project Management Institute</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/3976" class="schedule-link">Mark Freedman, Managing Director, Dalberg</a></strong></p>
                                <p><strong><a href="https://www.gvip.io/expertise/28" class="schedule-link">Norman Anderson, CEO, CG/LA Infrastructure</a></strong></p>

                            </div>

                        </div>

                        <!-- End of Track C-->

                    </div>



                </div>

            </div>

        </section><!-- End Schedule Section -->


        <section id="speakers">
            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Event Speakers</h2>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Khanna_Parag_FutureMap.jpg" alt="Speaker 1" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Parag Khanna</a></h3>
                                <p>Founder & Managing Partner, FutureMap</p>
                                <div class="social">
                                    <a href="https://www.gvip.io/expertise/4360"><strong style="font-size: larger;">GVIP</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Petraeus_David_KKR.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">David H. Petraeus</a></h3>
                                <p>Chairman, KKR Global Institute and Former Director, Central Intelligence</p>
                                <div class="social">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Levy_Joaquim_Safra-Bank.jpg" alt="Speaker 3" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Joaquim Levy</a></h3>
                                <p>Chief Economist, Safra bank and former Minister of Finance, Brazil</p>
                                <div class="social">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Fortea_Javier_Globalvia.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Javier Perez Fortea</a></h3>
                                <p>CEO, Globalvia</p>
                                <div class="social">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/norm.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Norman F. Anderson</a></h3>
                                <p>CG/LA Infrastructure</p>
                                <div class="social">
                                    <a href="https://www.gvip.io/expertise/28"><strong style="font-size: larger;">GVIP</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Escoto_Roberto_Apex.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Roberto Escoto</a></h3>
                                <p>Manager, Investment Department, APEX</p>
                                <div class="social">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Gluski_Andres_AES.png" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Andrés Gluski</a></h3>
                                <p> CEO, AES Corporation</p>
                                <div class="social">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Robb_Andrew_Australia.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Andrew Robb</a></h3>
                                <p>Minister of Trade and Investment, Government of Australia</p>
                                <div class="social">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Semsar_Joe_US-Department.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Joseph Semsar</a></h3>
                                <p>Acting Under Secretary for International Trade, US Department of Commerce Agency</p>
                                <div class="social">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Pass_James_Guggenheim.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Jim Pass</a></h3>
                                <p>Senior Managing Director, Guggenheim Partners</p>
                                <div class="social">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="speaker" data-aos="fade-up" data-aos-delay="200">
                            <img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/speakers/Jones_Eric_US-International-Finance.jpg" alt="Speaker 2" class="img-fluid w-100">
                            <div class="details">
                                <h3><a href="#schedule">Eric Jones</a></h3>
                                <p>Managing Director, U.S. International Development Finance Corporation</p>
                                <div class="social">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Speakers Section -->

        <!-- ======= Subscribe Section ======= -->
        <section id="subscribe">
            <div class="container" data-aos="zoom-in">
                <div class="section-header">
                    <h2 id="subscribe-h2">Register Now</h2>
                </div>

                <form action="https://www.cg-la.com/store/global-registration-all-access">
                    <div class="form-row justify-content-center">
                        <div class="col-auto">
                            <button type="submit">Register All Access</button>
                        </div>
                    </div>
                </form>
                <br>
                <form action="https://www.cg-la.com/store/r2xmfwrzr5gjrbw4luvs2s5racfx1t">
                    <div class="form-row justify-content-center">
                        <div class="col-auto">
                            <button type="submit">Register Basic</button>
                        </div>
                    </div>
                </form>

            </div>
        </section>
        <!-- End Subscribe Section -->

        <!-- ======= Gallery Section ======= -->
        <section id="gallery">

            <div class="container" data-aos="fade-up">
                <div class="section-header">
                    <h2>Gallery</h2>
                    <p>Check our gallery from the recent events</p>
                </div>
            </div>

            <div class="owl-carousel gallery-carousel" data-aos="fade-up" data-aos-delay="100">
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/1.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/1.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/2.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/2.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/3.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/3.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/4.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/4.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/5.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/5.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/6.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/6.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/7.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/7.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/8.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/8.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/9.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/9.jpg" alt=""></a>
                <a href="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/10.jpg" class="venobox" data-gall="gallery-carousel"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/gallery/10.jpg" alt=""></a>

            </div>

        </section><!-- End Gallery Section -->


        <!-- Sponsors Section -->
        <section id="sponsors">
            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>Event Sponsors</h2>
                </div>
                <div class="row">
                    <h1 class="col-12" style="border-bottom: 2px solid #060c22;">Strategic Sponsors</h1>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/Apcoll.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/Hexagon.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/oracle.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/starr.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Strategic/Trimble.jpg" alt="" class="img-fluid w-100"></div>
                </div>
                <div class="row">
                    <h1 class="col-12" style="border-bottom: 2px solid #060c22;">Platinum Sponsors</h1>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/acea.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/apexbrasil copy.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/CamGLLp.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/crowell.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/Northeastmaglev.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Platinum/theia.jpg" alt="" class="img-fluid w-100"></div>
                </div>
                <div class="row">
                    <h1 class="col-12" style="border-bottom: 2px solid #060c22;">Gold Sponsors</h1>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/Arup.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/blackbuffalo.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/BNA.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/CCR.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/COLE.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/DP.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/Global VIA.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/Lattice.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/Simco.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Gold/Structural.jpg" alt="" class="img-fluid w-100"></div>
                </div>

                <div class="row">
                    <h1 class="col-12" style="border-bottom: 2px solid #060c22;">Silver Sponsors</h1>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Silver/Autodesk.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Silver/Cadiz.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Silver/GreatLakes.jpg" alt="" class="img-fluid w-100"></div>
                    <div class="col-lg-3 col-md-4 col-sm-6"><img src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/sponsors/Silver/Port of Montreal.jpg" alt="" class="img-fluid w-100"></div>

                </div>


            </div>
        </section>
        <!-- End  Sponsors Section -->

        <!-- =======  F.A.Q Section ======= -->
        <section id="faq">

            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>F.A.Q </h2>
                </div>

                <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-9">
                        <ul id="faq-list">

                            <li>
                                <a data-toggle="collapse" class="collapsed" href="#faq1">How do I access the webinar sessions? <i class="fa fa-minus-circle"></i></a>
                                <div id="faq1" class="collapse" data-parent="#faq-list">
                                    <p>
                                        Scroll up to the agenda and click the "Join Session" button on the left side of the session you would like to join.
                                    </p>
                                </div>
                            </li>

                            <li>
                                <a data-toggle="collapse" href="#faq2" class="collapsed">What is GViP? <i class="fa fa-minus-circle"></i></a>
                                <div id="faq2" class="collapse" data-parent="#faq-list">
                                    <p>
                                        GViP stands for "Global Visualization of Infrastructure Projects", and is a web application designed to be the LinkedIn of the Infrastructure community.
                                    </p>
                                </div>
                            </li>

                            <li>
                                <a data-toggle="collapse" href="#faq3" class="collapsed">What is the Stimulus Map? <i class="fa fa-minus-circle"></i></a>
                                <div id="faq3" class="collapse" data-parent="#faq-list">
                                    <p>
                                        The Stimulus Map is an interactive digital platform that identifies more than 550 infrastructure projects. These projects have the potential to revitalize the U.S. economy through strategic investments. They cover all infrastructure sectors, have an overall investment value of approximately $1 trillion, and could create approximately 2.4 million direct and indirect jobs.
                                    </p>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

            </div>

        </section><!-- End  F.A.Q Section -->


        <!-- ======= Contact Section ======= -->
        <section id="contact" class="section-bg">

            <div class="container" data-aos="fade-up">

                <div class="section-header">
                    <h2>Contact Us</h2>
                    <p>CG/LA Infrastructure</p>
                </div>

                <div class="row contact-info">

                    <div class="col-md-4">
                        <div class="contact-address">
                            <i class="ion-ios-location-outline"></i>
                            <h3>Address</h3>
                            <address>729 15th St. NW
                                Suite 600
                                Washington, D.C. 20005
                            </address>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-phone">
                            <i class="ion-ios-telephone-outline"></i>
                            <h3>Phone Number</h3>
                            <p><a href="tel:+12027760990">+1 (202) 776-0990</a></p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="contact-email">
                            <i class="ion-ios-email-outline"></i>
                            <h3>Email</h3>
                            <p><a href="mailto:info@cg-la.com">info@cg-la.com</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong>TheEvent</strong>. All Rights Reserved
            </div>
            <div class="credits">
                <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=TheEvent
      -->
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End  Footer -->

    <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- Vendor JS Files -->
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/jquery/jquery.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/php-email-form/validate.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/venobox/venobox.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/superfish/superfish.min.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/hoverIntent/hoverIntent.js"></script>
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/vendor/aos/aos.js"></script>

    <!-- Template Main JS File -->
    <script src="https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/js/main.js"></script>

</body>

</html>