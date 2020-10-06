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

    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 140px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -75px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }
</style>
<div style="min-height:600px;" class="container-fluid">
    <div class="row my-3">
        <!-- Main Content -->
        <div class="col-lg-9 border mx-auto">
            <!-- Video -->
            <div class="row">
                <div class="embed-responsive embed-responsive-21by9">
                    <iframe class="embed-responsive-item" src="<?php echo $details['link']; ?>" allowfullscreen></iframe>
                </div>
            </div>
            <!-- Description,Header and Share -->
            <div class="row mt-3">
                <div class="col-md-9 col-12">
                    <h1 class="display-4 border-bottom mb-1"><?php echo $details['title']; ?></h1>
                </div>
                <div class="col-md-2 col-sm-4 col-5  my-md-auto mr-auto my-2 ml-md-auto">
                    <button onClick="copyFunction()" onmouseout="outFunc()" id="copy-text" type="button" class="btn btn-block share-btn btn-primary">Share Link</button>
                    <br>
                    <input readonly="readonly" style="width: 100%" type="text" value="https://gvip.io/gviptv/view/<?php echo $details['id']; ?>" id="myInput">
                </div>
                <h2 class="font-weight-light ml-4"><?php echo $details['description']; ?></h2>
            </div>
        </div>
        <!-- Main Content end -->
        <!-- Sidebar  -->
        <div id="sidebar" class="col-lg-2 mt-md-0 border mx-auto">
            <!-- Sidebar Header -->
            <div class="row header mx-md-0 mx-1">
                <h1 class="text-center mx-auto mb-4 font-weight-light"><?php echo $details['category']; ?> Channel</h1>
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
    <?php
    echo 'const hardcodedData = [';

    foreach($rows as $videos){
        if ($details['category'] == $videos['category'] && $details['id'] != $videos['id']){

            echo "{head: \"".$videos['title']."\",";
            echo "body: `".$videos['description']."`,";
            echo "type: \"".$videos['category']."\",";
            echo "videoUrl: \"".$videos['link']."\",";
            echo "id: \"".$videos['id']."\",";
            echo "imageUrl: \"".$videos['thumbnail']."\"},";

        }
    }

    echo ']';

    ?>
    // Loads the rest of the videos from the same channel
    $(document).ready(() => {
        hardcodedData.forEach(el => {
            sidebarContent.innerHTML += ` <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12 my-4">
                    <a href="/gviptv/view/${el.id}">
                        <img class="img-fluid w-100 z-depth-1" src="${el.imageUrl}" alt="video">
                    </a>
                    <h3 class="mt-1">${el.head}</h3>
                </div>`;
        });
    })

    function copyFunction() {
        /* Get the text field */
        var copyText = document.getElementById("myInput");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

        var tooltip = document.getElementById("copy-text");
        tooltip.innerHTML = "Copied";
    }

    function outFunc() {
        var tooltip = document.getElementById("copy-text");
        tooltip.innerHTML = "Share Link";
    }
</script>
