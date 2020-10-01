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

    .header {
        background: #2774A5;

    }

    .header h1 {
        padding: 0.25em 0;
        color: white;
    }

    #sidebar {
        overflow-y: scroll;
    }

    .share-btn {
        color: white !important;
        width: 100% !important;
        font-size: 1.5em;
        padding-left: 1em !important;
        padding-right: 1em !important;
        background: #2774A5 !important;

    }


    @media screen and (min-width:992px) {


        #sidebar {
            height: 90vh !important;
        }
    }
</style>
<div style="height: 90vh; min-height:600px;" class="container-fluid">
    <div class="row my-3">
        <!-- Main Content -->
        <div class="col-lg-9 border mx-auto">
            <!-- Video -->
            <div class="row">
                <div class="embed-responsive embed-responsive-21by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/RojqQ-lMsAU" allowfullscreen></iframe>
                </div>
            </div>
            <!-- Description,Header and Share -->
            <div class="row mt-3">
                <div class="col-md-9 col-12">
                    <h1 class="display-4 border-bottom mb-1">A Catalyst for Necessary Investment: the California I-Bank</h1>
                </div>
                <div class="col-md-2 col-sm-4 col-5  my-md-auto mr-auto my-2 ml-md-auto"><button type="button" class="btn btn-block share-btn btn-primary">Share</button></div>
                <h2 class="font-weight-light ml-4">Scott Wu, Executive Director, California Infrastructure and Economic Development Bank</h2>


            </div>
        </div>
        <!-- Main Content end -->


        <!-- Sidebar  -->
        <div id="sidebar" class="col-lg-2 mt-md-0 border mx-auto">
            <!-- Sidebar Header -->
            <div class="row header mx-md-0 mx-1">
                <h1 class="text-center mx-auto mb-4 font-weight-light">Investment Channel</h1>
            </div>

            <!-- Sidebar Content -->
            <div id="sidebar-content" class="row">
                <!-- Dynaicly loaded content -->
            </div>

        </div>
        <!-- Sidebar end -->
    </div>




</div>
<script>
    const sidebarContent = document.querySelector('#sidebar-content');
    // Hardcoded test data  
    const hardcodedData = [{
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
            type: "i    nvestment",
            videoUrl: "https://www.youtube.com/embed/DwWyTQHcSSw",
            imageUrl: "https://d2huw5an5od7zn.cloudfront.net/gviptv/images/1+Penna.png"
        },

    ];
    // Loads the rest of the videos from the same channel
    $(document).ready(() => {
        hardcodedData.forEach(el => {
            sidebarContent.innerHTML += ` <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12 my-4">
                    <a>
                        <img class="img-fluid w-100 z-depth-1" src="${el.imageUrl}" alt="video">
                    </a>
                    <h3 class="mt-1">${el.head}</h3>
                </div>`;
        });
    })
</script>