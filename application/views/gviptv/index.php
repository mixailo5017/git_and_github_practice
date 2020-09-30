<!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>



<style>
    body,
    html {
        height: 100%;
    }

    .hero-image {
        /* Use "linear-gradient" to add a darken background effect to the image (photographer.jpg). This will make the text easier to read */
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/onlineforum/assets/img/GVIPTV Banner 2.jpg");

        /* Set a specific height */
        height: 50%;

        /* Position and center the image to scale nicely on all screens */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }

    /* Place text in the middle of the image */
    .hero-text {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
    }

    .center {
        display: block;
        margin-top: auto;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
    }

    .sidebar-head {
        background-color: #2774A5;
        color: white;
    }

    .select-item:hover {
        transition: 300ms;
        background: #2774A5;
        color: white;
        cursor: pointer;
    }

    .active-item {
        background: #2774A5;
        color: white;
        cursor: initial;
    }

    .search-btn {


        font-size: 1.5em;
        border: 1px solid #2774A5 !important;
        border-radius: 4px;
        color: #2774A5 !important;
    }

    .search-btn:hover {
        background-color: white !important;
        cursor: pointer;
    }
</style>
<!-- Banner -->
<a href="#">
    <div class="hero-image">
        <div class="hero-text">
        </div>
    </div>
</a>
<!-- Banner end -->

<!-- Header -->
<div class="row" style="padding-top: 25px; padding-bottom: 25px">
    <h1 class="col-md-9 mx-auto" style="text-align: center">Video Gallery</h1>
</div>
<!-- Header end -->

<!-- Toggle Sidebar button -->
<div class="row" style="padding-top: 25px; padding-bottom: 25px;padding-left: 2%">
    <div class="col-md-2 col-12 mb-5 mr-md-auto">
        <button id="toggle-btn" type="button" class="btn w-100 search-btn ">Channels</button>
    </div>
</div>
<!-- Toggle Sidebar button end -->

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div style="min-height: 500px;" id="sidebar" class="col-lg-2 border ml-5">
            <div class="row sidebar-head">
                <h1 class="display-4 text-light mb-4">GVIPTV Channels:</h1>
            </div>
            <div class="row sidebar-head">
                <p class="h3 font-weight-light px-2"></p>
            </div>
            <div class="row sidebar-body my-5">
                <ul class="list-group w-100">
                    <li id="btn-all" class="list-group-item h3 my-2 font-weight-light select-item active-item">All Videos</li>
                    <li id='btn-recent' class="list-group-item h3 my-2 font-weight-light select-item">Recent Uploads</li>
                    <li id='btn-invest' class="list-group-item h3 my-2 font-weight-light select-item">Investment</li>
                    <li id='btn-leader' class="list-group-item h3 my-2 font-weight-light select-item">Leadership</li>
                    <li id='btn-projects' class="list-group-item h3 my-2 font-weight-light select-item">Projects</li>
                    <li id='btn-tech' class="list-group-item h3 my-2 font-weight-light select-item ">Technology</li>
                </ul>
            </div>
        
        </div>
        <!-- Sidebar end -->

        <!-- Main Section -->
        <div class="col-lg-9 mx-auto">
            <div id="main-content" style="padding-right: 2%; padding-left: 2%" class="row">
                <!-- Dynamicly Loaded  -->
            </div>
        </div>
        <!-- Main Section end -->
    </div>
</div>



<script>
    //Should be dynamicly loaded. Still waiting on John with that
    const hardcodedData = [{
            head: "Rail Baltica - Europe's Greatest Connectivity Project",
            body: "Mark Loader, Chief Project Officer, RB Rail AS, and Norman Anderson, President and CEO of CG/LA Infrastructure, discuss Rail Baltica. ",
            type: "projects",
            videoUrl: "https://www.youtube.com/embed/7buloKcgpUE",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Rail_Baltica.jpg"
        }, {
            head: "Project of the Year Awards",
            body: "Announced at the 13th Global Infrastructure Leadership Virtual Forum, sponsored by Oracle Construction and Engineering.",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/H-zWCqVWtGQ",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/POY.jpg "
        }, {
            head: "The View from Space - Business Opportunities that will Change Life on Earth",
            body: "Erlend Olson, Co-Founder and COO, Theia Group <br/> Guest interviewer: Dr. Joseph N. Pelton, Executive Board, International Association for the Advancement of Space Safety (IAASS) ",
            type: "projects",
            videoUrl: "https://www.youtube.com/embed/EHkhlNcPxkU",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Space.jpg"
        },
        {
            head: "13th Global Strategic Infrastructure Leadership Forum : Special Strategic Keynote",
            body: "David H. Petraeus, Chairman, KKR Global Institute and Former Director, Central Intelligence Agency",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/e7rWFTxGz08",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Petraeus.jpg"
        },
        {
            head: "Andrés Gluski, CEO, AES Corporation:<br />'Innovation & Infrastructure - The Role of the Global CEO'",
            body: "Andrés Gluski has served as President and CEO since 2011, after serving as COO for five years. He has led AES through a dramatic transformation by focusing on simplification, risk improvement and innovation - including a $5 billion divestiture program, dramatically increased credit rating and the initiation of a quarterly dividend, which has grown at an 8% annual rate. Under his leadership, AES has become a world leader in implementing new clean technologies, including energy storage, wind, solar and LNG.",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/KmkCHU_Sy_8",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Gluski.jpg"
        },
        {
            head: "A Catalyst for Necessary Investment: the California I-Bank",
            body: "Scott Wu, Executive Director, California Infrastructure and Economic Development Bank",
            type: "investment",
            videoUrl: "https://www.youtube.com/embed/RojqQ-lMsAU",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Wu.jpg"
        },
        {
            head: "David Zimmer",
            body: "David Zimmer, NJIB Executive Director, and CG/LA CEO Norman Anderson explore the strength of state-level I-Banks and how to leverage their full potential.",
            type: "investment",
            videoUrl: "https://www.youtube.com/embed/vT7gmmCbenk",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/David_Zimmer.png"
        },
        {
            head: "Dry Powder & the Build America Bureau",
            body: "Roger Bohnert, Director of Business Outreach, Build America Bureau, U.S. DOT",
            type: "investment",
            videoUrl: "https://www.youtube.com/embed/OGbw2-aUhZU",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/5+Dry+Power.png"
        },
        {
            head: "David Penna",
            body: "Senior Vice President, Office of Strategic Initiatives, U.S. International Development Finance Corporation (DFC)<br/>in conversation with Norman Anderson, President, and CEO, CG-LA Infrastructure<br/>The U.S. Development Corporation (DFC)<br/>Funding for Reshoring Critical Capabilities",
            type: "investment",
            videoUrl: "https://www.youtube.com/embed/DwWyTQHcSSw",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/1+Penna.png"
        },
        {
            head: "General David H. Petraeus",
            body: "General David H. Petraeus AO, MSC,<br/>Former Director of the Central Intelligence Agency<br/>in conversation with Norman Anderson, President, and CEO, CG-LA Infrastructure<br/>Filmed live at the North American Infrastructure Leadership Forum, Washington DC, 2019",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/O49MWYEgeYw",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/6.+Petreus.png"
        },
        {
            head: "Looking to Asia: Recovery, Growth, and What Lies Ahead",
            body: "Dr. Parag Khanna, Managing Partner, FutureMaps, and Norman Anderson, President and CEO, CG/LA Infrastructure",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/Wnd4WZpuWYc",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/prang.jpg"
        },
        {
            head: "French Hill",
            body: "U.S. House of Representatives from Arkansas's 2nd district in conversation with Norman Anderson, President, and CEO, CG-LA Infrastructure<br/>View from the White House on Infrastructure and Stimulus",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/VqVZKK6jUc4",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/2.+French+Hill.png"
        },
        {
            head: "Leadership Panel: What the Infrastructure Community Needs from Washington",
            body: "Mike Johnson, Sr. Vice President - Infrastructure Market and Strategy, Kiewit<br/>Pierce Homer, Director, Moffat & Nichol (former Sec. of Transportation, Virginia)<br/>K. N. Gunalan, Senior Vice President, Alternative Delivery, Americas, AECOM (current ASCE 2020 President)<br/>Norman Anderson, President, and CEO, CG-LA Infrastructure",
            type: "leadership",
            videoUrl: "https://www.youtube.com/embed/F38myQfNIMA",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/3.+Leadership+Panel.png"
        },
        {
            head: "Army Corps",
            body: "U.S. Army Corps of Engineers - Meeting Covid Challenges with Priority Projects<br/>Peter Dodgion, Asset Management Program Manager, U.S. Army Corps of Engineers",
            type: "projects",
            videoUrl: "https://www.youtube.com/embed/0Klp_VSCp0w",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/4+Army+Corps.png"
        },
        {
            head: "NioCorp",
            body: "NioCorp’s Elk Creek Project - 'Bringing Neobium to America'<br/>Mark A. Smith, P.E., Esq",
            type: "projects",
            videoUrl: "https://www.youtube.com/embed/ITZChQP_qfc",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/NioCorp.png"
        },
        {
            head: "The CLARA Plan: Maximizing the Value of the Sydney-Melbourne Corridor with HSR and Smart Cities",
            body: "Nick Cleary, Chairman and CEO, Consolidated Land and Rail Australia (CLARA), and Norman Anderson, President, and CEO, CG/LA Infrastructure: In conversation",
            type: "projects",
            videoUrl: "https://www.youtube.com/embed/GHZ4J3OxBTU",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Clara.jpg"
        },
        {
            head: "Big Sun Holdings: Printing Buildings and Securing Your IoT Future",
            body: "Michael Woods, CEO, Black Buffalo & Flash Labs and Norman Anderson, CEO, CG/LA Infrastructure in conversation.",
            type: "tech",
            videoUrl: "https://www.youtube.com/embed/BA9ZqOkkPpw",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/black_buffalo_test.jpg"
        },
        {
            head: "Jennifer Schmitz, CEO, Founder at Lattice Industries, Inc.",
            body: "Lattice Industries: Using the Data Marketplace to Fund the Future of Infrastructure.",
            type: "tech",
            videoUrl: "https://www.youtube.com/embed/O1Uwk8vyvNk",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/Jenifer.jpg"
        }
    ];
    const mainContent = document.querySelector('#main-content');
    let currentlySeleceted = document.querySelector('#btn-all');

    // Replacing the attributes on the active channel sidebar
    function replaceSelected(id) {
        currentlySeleceted.classList.remove('active-item');
        currentlySeleceted = document.getElementById(id);
        currentlySeleceted.classList.add('active-item')
    }

    //populating the container with the data
    function populate(content, status) {
        mainContent.innerHTML = '';
        if (status !== 'all' && status !== 'recent')
            content = content.filter(el => el.type === status)
        if (status === 'recent')
            content = content.slice(0, 3)

        content.forEach((el, i) => {
            mainContent.innerHTML += `
                <div class="col-lg-4 col-md-12 mb-4">
                    <!--Modal: Name-->
                    <div class="modal fade" id="modal${i}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <!--Content-->
                            <div style="top: 10em" class="modal-content">
                                <!--Body-->
                                <div class="modal-body mb-0 p-0">

                                    <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                                        <iframe class="embed-responsive-item" src="${el.videoUrl}" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <!--/.Content-->
                        </div>
                    </div>
                    <!--Modal: Name-->

                    <a><img class="img-fluid w-100 z-depth-1" src="${el.imageUrl}" alt="video" data-toggle="modal" data-target="#modal${i}"></a>
                    <h3>${el.head}</h3>
                    <p>${el.body}</p>
                </div>
                `;
        })

    }
    
    //  Toggle sidebar on and off
    $('#toggle-btn').on('click', function(e) {
        $('#sidebar').toggle()
    })
    // OnLoad Populate
    $(document).ready(function() {
        populate(hardcodedData, 'all')
    })

    //Button Event listeners
    $('#btn-all').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'all');
    })
    $('#btn-recent').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'recent');
    })
    $('#btn-invest').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'investment');
    })
    $('#btn-leader').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'leadership');
    })
    $('#btn-projects').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'projects');
    })
    $('#btn-tech').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'tech');
    })
</script>