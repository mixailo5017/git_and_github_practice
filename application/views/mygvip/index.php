<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script><!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>
    AOS.init();
</script>

<style>
    body,
    html {
        height: 100%;
    }
    .hero-image,
    .tv-image {
        height: 55%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }
    .hero-image {
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/Nalf12 Banner 1.jpg");
    }
    .tv-image {
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/GVIPTV Banner 2.jpg");
    }
    .image,
    .image-3,
    .image-2 {
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        position: relative;
    }
    .image {
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/Experts-min.gif");
    }
    .image-2 {
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/Handshake.jpeg");
    }
    .image-3 {
        background-image: url("https://d2huw5an5od7zn.cloudfront.net/cropped-gif");
    }
    .center {
        display: block;
        margin-top: auto;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
    }
    .row {
        background: white;
        margin: 2em 0;
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-evenly;
        min-height: 50vh;
        border: 0.1px solid rgba(28, 28, 28, 0.1) !important;
        border-radius: 10px;
        box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12),
            0 2px 4px -1px rgba(0, 0, 0, 0.3);
    }
    .row .column {
        padding: 0.5em 0;
        flex-basis: 45%;
        font-size: 1.5rem;
    }
    .gvip-text {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-around;
        padding: 0.5em 0.5em;
    }
    .gvip-text p {
        font-size: 2.9rem;
    }
    .embed {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .embed-responsive-item {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    @media screen and (max-width:850px) {
        .hero-image,
        .tv-image {
            height: 25%;
            background-position: top;
            background-repeat: no-repeat;
            background-size: contain;
            position: relative;
        }
        .hero-image {
            background-position: center !important;
            background-image: url("https://d2huw5an5od7zn.cloudfront.net/Nalf12 Banner 1.jpg");
        }
        .tv-image {
            background-image: url("https://d2huw5an5od7zn.cloudfront.net/GVIPTV Banner 2.jpg");
        }
        .embed,
        .image,
        .image-2,
        .image-3 {
            height: 50vh !important;
        }
        .row {
            background: white;
            margin: 2em 0;
            display: flex !important;
            flex-direction: column !important;
            min-height: 50vh;
            border: 0.1px solid rgba(28, 28, 28, 0.1) !important;
            border-radius: 10px;
            box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12),
                0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }
        .row .column {
            padding: 0.5em 0;
            flex-basis: 90%;
            font-size: 1.5rem;
        }
        .gvip-text {
            padding: 1em;
            margin: 0 0.5em;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-around;
        }
        .gvip-text p {
            font-size: 1.5rem;
        }
        .reverese {
            flex-direction: column-reverse !important;
        }
    }
</style>
<!-- Head Banner -->
<a href=" https://www.cg-la.com/store/nalf-registration">
    <div class="tv-image">
    </div>
</a>

<!-- Main content section -->
<section id="main-content">
    <div class="container-fluid">
        <!--1. Row -->
        <div class="row">
            <div class="column">
                <div class="embed-responsive embed">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/UGNw0KWsYOg"></iframe>
                </div>
            </div>
            <div class="column gvip-text" data-aos='fade-in'>
                <p>
                    GVIP, your source for expertise, intelligence, and connection. In the multisector Infrastructure Landscape.
                </p>
                <p>
                    GVIP, Let’s Make Projects Happen.
                </p>
            </div>
        </div>
        <!-- Row end -->
        <!-- 2. Row -->
        <div class="row reverese">
            <div class="column gvip-text" data-aos='fade-in-up'>
                <p>
                    Interactive Data, Maps and Visualizations, Access to Global Multisector Experts, Project pages updated by Owner Operators in real-time, Access to financiers and industry leaders, exclusives interviews and roundtable discussions.
                </p>
                <p>
                    Only on GVIP
                </p>
            </div>
            <div class="column">
                <div class="image"></div>
            </div>
        </div>
        <!-- Row end -->
        <!-- 3. Row -->
        <div class="row">
            <div class="column">
                <div class="image-2"></div>
            </div>
            <div class="column">
                <div class="column gvip-text" data-aos='zoom-in'> <br> <br>
                    <p>
                        2020 has made GVIPs mission more vital than ever. Infrastructure is the key to saving the world from global recession, creating jobs, value and opportunities for communities around the world.
                    </p>
                    <br>
                </div>
            </div>
        </div>
        <!-- Row end -->
        <!-- 4. Row -->
        <div class="row reverese">
            <div class="column gvip-text" data-aos="zoom-in-up">
                <br>
                <p>
                    How do you make your project visible to the global multisector infrastructure Industry? GVIP.
                </p>
                <p>
                    How do you connect with project owners and build your business? GVIP.
                </p>
                <p>
                    How do you get just in time intelligence, stay ahead of trends, and arm yourself with intelligence and information you can’t find anywhere else? GVIP.
                </p>
            </div>
            <div class="column">
                <div class="image-3 "></div>
            </div>
        </div>
        <!-- Row end -->
</section>
<!-- Footer Banner -->
<a href="https://www.gvip.io/gviptv">
    <div class="tv-image">
    </div>
</a>
