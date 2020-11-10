<style>
  .hero-image{
      position: relative;
      height: 600px;
  }  
  video {
        object-fit: cover;
        position: absolute;
        width: 100vw;
        height: 100%;
        z-index: 1 !important;
        top: 0;
        left: 0;
    }
    .wrap{
        position: relative;
        width: 100%;
        height: 100%;
        z-index: 2;
        background: rgba(0,0,0,0.4);
    } 
</style> 



<section class="hero-image">
    <video playsinline autoplay muted loop poster="polina.jpg" id="bgvid">
        <source src="https://d2huw5an5od7zn.cloudfront.net/Meet GVIP.mp4" type="video/webm">
    </video>
    <div class="wrap">
        <div class="container">
            <div class="headline">
                <h1 class="h1-xl">Build your Membership in the Global Infrastructure Community</h1>
            </div>
            <div class="head-cta">
                <div>
                    <a class="btn std lt-blue" href="/signup">Join for Free</a>
                    <a class="btn std clear play" href="https://youtube.com/watch?v=U_xIrk7P_KY"><span>Watch It in Action</span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Plug in default values for counters in case not provided to the view
$counters['experts'] = empty($counters['experts']) ? 2287 : $counters['experts'];
$counters['projects'] = empty($counters['projects']) ? 1718 : $counters['projects'];
$counters['countries'] = empty($counters['countries']) ? 155 : $counters['countries'];
$counters['totalvalue'] = empty($counters['totalvalue']) ? 2.8 : $counters['totalvalue'];
$counters['jobs'] = empty($counters['jobs']) ? 84 : $counters['jobs'];
?>
<section class="testimonials">
    <div class="container">
        <h2 class="h2-std" id="testimonials-statistics">To date, GViP has connected <?php echo $counters['experts'] ?> experts with <?php echo $counters['projects'] ?> projects across <?php echo $counters['countries'] ?> countries! That's a total project value of $<?php echo $counters['totalvalue'] ?> trillion, creating an estimated <?php echo $counters['jobs'] ?> million jobs.</h2>
        <ul>
            <li class="photo">
                <img src="/images/new/testimonial3.png" width="100" height="100" />
            </li>
            <li class="quote">
                <p>&ldquo;Finally our industry has its own social business platform, this is really great!&rdquo;</p>
                <p class="author"><span>Walter Kemmsies</span>Chief Strategist, U.S. Ports, Airports and Global Infrastructure Group, JLL</p>
            </li>
        </ul>
        <ul>
            <li class="photo">
                <img src="/images/new/testimonial4.jpg" width="100" height="100" />
            </li>
            <li class="quote">
                <p>&ldquo;GViP is becoming the LinkedIn of the infrastructure world. It's a community where we all trust each other and are looking for ways to improve our projects and business relationships. There is no other place on the web where this quality and quantity of decision makers for the major infrastructure projects globally are available to discuss partnering arrangements.&rdquo;</p>
                <p class="author"><span>Chris Hussey</span>Partner Executive, e-Builder</p>
            </li>
        </ul>
    </div>
</section>

<section class="global-lt" style="background: url(../images/new/world-grid-spotlight.png)">
    <div class="container">
        <h2 class="h1-xl">#CreateGreat, Globally</h2>
        <p style="padding-bottom:50px;">You need to make good decisions, quickly. You need to build your business, with certainty. You need to find the right data that defines your market, and act. GViP is the tool that enables you to make decisions, build your business, and powerfully understand the market. Cut your project's development time by 50%, and reach the finish line on time, and on budget. The GViP platform helps you find expertise, identify the right tools, choose the right partners, and build a tremendous career. #CreateGreat
        </p>
        <h2> Strategic Project Connections Globally (Powered by GViP AI+) </h2>
        <div>
            <img src="/images/new/connections.png" alt="Connetions">
        </div>
    </div>
</section>

<section class="spotlights">
    <div class="container">
        <div class="spotlight-wrapper">
            <article class="active card card-1">
                <div class="card-interior">
                    <h4 class="h3-std">Street Lighting PPP</h4>
                    <div class="img">
                        <span class="badge power"><span></span></span>
                        <img src="/images/new/lights.jpg" />
                    </div>
                    <ul class="stats">
                        <li class="figure">440</li>
                        <li class="labels">
                            <p>mm</p>
                            <p class="currency">USD</p>
                        </li>
                    </ul>
                    <dl>
                        <dt>Stage:</dt>
                        <dd>Planning</dd>
                        <dt>Location:</dt>
                        <dd>Belo Horizonte, Brazil</dd>
                        <dt>Sector:</dt>
                        <dd>Energy</dd>
                    </dl>
                </div>
            </article>
            <article class="card card-2">
                <div class="card-interior">
                    <h4 class="h3-std">New Airport in Mexico City</h4>
                    <div class="img">
                        <span class="badge logistics"><span></span></span>
                        <img src="/images/new/airfield.jpg" width="250" height="175" />
                    </div>
                    <ul class="stats">
                        <li class="figure">4000</li>
                        <li class="labels">
                            <p>mm</p>
                            <p class="currency">USD</p>
                        </li>
                    </ul>
                    <dl>
                        <dt>Stage:</dt>
                        <dd>Planning</dd>
                        <dt>Location:</dt>
                        <dd>Mexico City, Mexico</dd>
                        <dt>Sector:</dt>
                        <dd>Airports and Logistics</dd>
                    </dl>
                </div>
            </article>

            <article class="card card-3">
                <div class="card-interior">
                    <h4 class="h3-std">Oakajee Port</h4>
                    <div class="img">
                        <span class="badge logistics"><span></span></span>
                        <img src="/images/new/ship.jpg" width="250" height="175" />
                    </div>
                    <ul class="stats">
                        <li class="figure">3400</li>
                        <li class="labels">
                            <p>mm</p>
                            <p class="currency">USD</p>
                        </li>
                    </ul>
                    <dl>
                        <dt>Stage:</dt>
                        <dd>Planning</dd>
                        <dt>Location:</dt>
                        <dd>Western Australia</dd>
                        <dt>Sector:</dt>
                        <dd>Ports and Logistics</dd>
                    </dl>
                </div>
            </article>

        </div>
        <div class="green-alt cta-box pull-right">
            <h3 class="h3-std">Visualize People, Projects &amp; Places</h3>
            <p>GViP's tools empower you to work visually, by seeing everything on a map, globally: from projects, to people, to best-in-class teaming partners—including SMEs—wherever they are in the world. Gain early warning into the challenges you face—and your peers’ solutions—by building your own customized dashboard.</p>
        </div>
    </div>
</section>

<section class="personalize">
    <div class="container">
        <div class="blue cta-box">
            <h3 class="h3-std">Create Your Long-Term Potential</h3>
            <p>GViP is your tool to build your strategy, and execute on that strategy—use our platform to achieve greatness, for your project, your company and yourself! Be perfect!!</p>
        </div>
    </div>
</section>

<section class="front-page-cta">
    <div class="container">
        <h2 class="h2-std">Start connecting with infrastructure experts today!</h2>
        <p>Get in on the ground floor and create a free account.</p>
        <a class="btn std lt-blue" href="/signup">Join for Free</a>
    </div>
</section>

<script src="https://www.youtube.com/iframe_api" async="async"></script>