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
    <?php
    echo 'const hardcodedData = [';

    foreach($rows as $videos){
        echo "{head: \"".$videos['title']."\",";
        echo "body: \"".$videos['description']."\",";
        echo "type: \"".$videos['category']."\",";
        echo "videoUrl: \"".$videos['link']."\",";
        echo "imageUrl: \"".$videos['thumbnail']."\"},";
    }

    echo ']';

    ?>


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
        populate(hardcodedData, 'Investment');
    })
    $('#btn-leader').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'Leadership');
    })
    $('#btn-projects').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'Projects');
    })
    $('#btn-tech').on('click', function(e) {
        replaceSelected(e.target.id)
        populate(hardcodedData, 'Technology');
    })
</script>
