<section class="hero-image">
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
                <p>&ldquo;GViP is becoming the LinkedIn of the infrastructure world.  It's a community where we all trust each other and are looking for ways to improve our projects and business relationships.  There is no other place on the web where this quality and quantity of decision makers for the major infrastructure projects globally are available to discuss partnering arrangements.&rdquo;</p>
                <p class="author"><span>Chris Hussey</span>Partner Executive, e-Builder</p>
            </li>
        </ul>
    </div>
</section>

<section class="global-lt" style="background: url(../images/new/world-grid-spotlight.png)">
    <div class="container">
        <h2 class="h1-xl">#CreateGreat, Globally</h2>
        <p style="padding-bottom:50px;" >You need to make good decisions, quickly.  You need to build your business, with certainty.  You need to find the right data that defines your market, and act.  GViP is the tool that enables you to make decisions, build your business, and powerfully understand the market.  Cut your project's development time by 50%, and reach the finish line on time, and on budget.  The GViP platform helps you find expertise, identify the right tools, choose the right partners, and build a tremendous career.  #CreateGreat
        </p>
        <h2> Strategic Project Connections Globally (Powered by GViP AI+) </h2>
        <div style="padding-left:170px">
          <script src="http://d3js.org/d3.v3.min.js"></script>
          <script src="http://d3js.org/topojson.v1.min.js"></script>
          <!-- I recommend you host this file on your own, since this will change without warning -->
          <script src="http://datamaps.github.io/scripts/datamaps.world.min.js?v=1"></script>
          <div id="container1" style="position: relative; width: 80%; max-height: 450px;"></div>


             <script>
               //basic map config with custom fills, mercator projection
              var map = new Datamap({
                scope: 'world',
                element: document.getElementById('container1'),
                projection: 'mercator',
                height: 500,
                fills: {
                  defaultFill: '#2274A5',
                  lt50: 'rgba(0,244,244,0.9)',
                  gt50: 'red'
                },

                data: {
                }
              })


              //sample of the arc plugin
              map.arc([
               {
                origin: {
                    latitude: 40.639722,
                    longitude: 73.778889
                },
                destination: {
                    latitude: 37.618889,
                    longitude: -122.375
                }
              },
              {
                  origin: {
                      latitude: -14.00457,
                      longitude: 21.67237
                  },
                  destination: {
                      latitude: 7.37241,
                      longitude: -83.80716
                  }
              },
              {
                  origin: {
                      latitude: 15.58157,
                      longitude: -1.88358
                  },
                  destination: {
                      latitude: 38.23790,
                      longitude: 70.91722
                  }
              },
              {
                  origin: {
                      latitude: -18.01022,
                      longitude: 139.24755
                  },
                  destination: {
                      latitude: 28.05075,
                      longitude: 44.42961
                  }
              },
              {
                  origin: {
                      latitude: 20.63842,
                      longitude: 104.52987
                  },
                  destination: {
                      latitude: -24.25800,
                      longitude: 19.06474
                  }
              },
              {
                  origin: {
                      latitude: 44.68675,
                      longitude: 23.22046
                  },
                  destination: {
                      latitude: 17.70696,
                      longitude: 101.23668
                  }
              },
              {
                  origin: {
                      latitude: 41.28717,
                      longitude: -87.26811
                  },
                  destination: {
                      latitude: 62.24216,
                      longitude: 39.47313
                  }
              },
              {
                  origin: {
                      latitude: 47.84060,
                      longitude: 18.41143
                  },
                  destination: {
                      latitude: 39.25087,
                      longitude: -12.39903
                  }
              },
              {
                  origin: {
                      latitude: 25.46978,
                      longitude: -109.51350
                  },
                  destination: {
                      latitude: -1.22078,
                      longitude: -48.32917
                  }
              },
              {
                  origin: {
                      latitude: 33.16763,
                      longitude: -97.60777
                  },
                  destination: {
                      latitude: 33.27800,
                      longitude: 10.76563
                  }
              },
              {
                  origin: {
                      latitude: 53.54529,
                      longitude: 30.39564
                  },
                  destination: {
                      latitude: -7.36276,
                      longitude: -40.24986
                  }
              },
              {
                  origin: {
                      latitude: 22.84079,
                      longitude: 40.07192
                  },
                  destination: {
                      latitude: 26.17474,
                      longitude: 30.13632
                  }
              },


              {
                  origin: {
                      latitude: 63.61429,
                      longitude: 20.06469
                  },
                  destination: {
                      latitude: 23.00524,
                      longitude: 75.34928
                  }
              },
              {
                  origin: {
                      latitude: -14.72688,
                      longitude: -67.57199
                  },
                  destination: {
                      latitude: 30.88521,
                      longitude: -100.93921
                  }
              },
              {
                  origin: {
                      latitude: -48.42260,
                      longitude: -70.63041
                  },
                  destination: {
                      latitude: 47.06564,
                      longitude: 68.91427
                  }
              },
              {
                  origin: {
                      latitude: 25.69084,
                      longitude: -104.30151
                  },
                  destination: {
                      latitude: -27.82786,
                      longitude: 115.13256
                  }
              },
              {
                  origin: {
                      latitude: 45.54674,
                      longitude: 110.07192
                  },
                  destination: {
                      latitude: 24.33724,
                      longitude: 47.14929
                  }
              },
              {
                  origin: {
                      latitude: 32.03369,
                      longitude: 75.02046
                  },
                  destination: {
                      latitude: 49.22086,
                      longitude: -97.10265
                  }
              },
              {
                  origin: {
                      latitude: 31.93671,
                      longitude: -9.70368
                  },
                  destination: {
                      latitude: 46.50735,
                      longitude: 59.41860
                  }
              },
              {
                  origin: {
                      latitude: 6.18996,
                      longitude: -51.25002
                  },
                  destination: {
                      latitude: 37.27680,
                      longitude: 140.23566
                  }
              },
              {
                  origin: {
                      latitude: 34.38430,
                      longitude: -86.65807
                  },
                  destination: {
                      latitude: 25.39532,
                      longitude: -109.72826
                  }
              }
              ], {strokeWidth: 2});


               //bubbles, custom popup on hover template
             map.bubbles([
               //{name: 'Hot', latitude: 21.32, longitude: 5.32, radius: 10, fillKey: 'gt50'},
               //{name: 'Chilly', latitude: -25.32, longitude: 120.32, radius: 18, fillKey: 'lt50'},
               //{name: 'Hot again', latitude: 21.32, longitude: -84.32, radius: 8, fillKey: 'gt50'},

             ], {
               popupTemplate: function(geo, data) {
                 return "<div class='hoverinfo'>It is " + data.name + "</div>";
               }
             });
             </script>
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
