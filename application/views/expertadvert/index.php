<style>
body, html {
  height: 100%;
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}
.center {
      display: block;
      margin-top: auto;
      margin-left: auto;
      margin-right: auto;
        }
.hero-image {
  background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url("images/new/businessai.png");
  height: 40%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}

* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column1 {
  float: left;
  width: 60%;
}
.column3 {
  float: left;
  width: 40%;
}
.column2 {
  float: left;
  width: 50%;
}
.column4 {
  float: left;
  width: 80%;
}
.column5 {
  float: left;
  width: 20%;
}


table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 80%;
  margin-top: auto;
  margin-left: auto;
  margin-right: auto;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>

<div class="hero-image">
</div>

<h1 class="center" style="font-size:50px; padding-top:50px">Welcome to Business AI+ with GViP<br>A new way to <strong>Connect</strong> with the Infrastructure community</h1>

<div style="padding-top:50px; padding-right:30px" class="row">
  <div class="column1">
    <img src="/images/new/_108243428_gettyimages-871148930.jpg" height="500" width="800"/>
  </div>
  <div class="column3">
    <h1 class="center"> How it Works</h2>
    <p style="font-size:20px">GViP Business AI+ will implement Google's TensorFlow in order to optimize experts, companies, and projects users connect with. <br><br>
    TensorFlow takes in user specific data based on previous interactions and profile details. <br><br>
    Using machine learning algorithms, the model ranks other companies, experts, and projects based on the user data.<br><br>
    The top ranked data is then presented to the user, completely optimizing who you interact with in the infrastructure community.<br><br></p>
    <h3><a href="https://www.tensorflow.org/"> Learn more about Tensor Flow</a></h3>
  </div>
</div>
<hr>
<hr>


<html>
<head>
  <meta charset='utf-8' />
  <title>Points on a map</title>
  <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.4.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.4.0/mapbox-gl.css' rel='stylesheet' />
  <style>
    body {
      margin: 0;
      padding: 0;
    }
  </style>
</head>

<h1 class="center" style="padding-bottom: 100px;font-size:50px; padding-top:50px">Example Project Page</h1>
<hr>


<div class="clearfix" id="content">
  <img class="center" src="/images/new/northeastmag.png" height="75" width="965" class="center"/>



  <div class="clearfix" id="content" style="padding-top:50px">
    <div class="center white_box">
      <p>
        <strong>Description: </strong>
        <br>
        The Northeast Maglev (TNEM) is a U.S.-owned company based in Washington, DC. We are committed to bringing the revolutionary Superconducting Maglev (SCMAGLEV) to the Northeast Corridor, the most congested transportation region in the country.

    TNEM is working closely with the Central Japan Railway Company (JR Central), which has led development of the SCMAGLEV system since its formation in 1987. JR Central also operates the world’s premier high-speed rail line between Tokyo and Osaka, serving more than 140 million passengers a year.

    A world of new possibilities will open to people living in the Northeast Corridor when an SCMAGLEV train can …take them from Washington to New York in less than an hour. Passengers can catch a Broadway show and be back in D.C. later that evening. Businesspeople can attend meetings in different cities without staying overnight. Air travelers can choose from numerous airports, as opposed to the one that is closest.

    SCMAGLEV will revolutionize travel and convenience for commuters moving between Washington, D.C. and New York City, through Baltimore, Wilmington and Philadelphia, as well as the Baltimore-Washington, Philadelphia and Newark International Airports.

    What would you do with an extra 100 minutes? That’s how much time you’d save each way if you took the SCMAGLEV instead of the Acela from D.C. to New York City.

      </p>
    </div>
  </div>



  <div id="col9" class="center_col white_box new_map show_loading show_projects">
    <div id='map' style='height: 650px'></div>
    <script>
    mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'; // replace this with your access token
    var map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/johnbrisbane/ck1td1wem6cfm1dpesilgf09w', // replace this with your style URL
      center: [-72.661557, 39.893748],
      zoom: 5.7
    });
// code from the next step will go here
    </script>
  </div>
</div>

<div class="clearfix" id="content" style="padding-top: 20px; padding-bottom: 50px;">

<table style="width:80%">
  <tr>
    <th>Stage:</th>
    <td>Conceptual</td>
    <th>Location:</th>
    <td>Washington D.C.</td>
  </tr>
  <tr>
    <th>Sector:</th>
    <td>Transport</td>
    <th>Subsector:</th>
    <td>Transit</td>
  </tr>
  <tr>
    <th>Financial Structure:</th>
    <td>Private</td>
    <th>Budget</th>
    <td>10.0B</td>
  </tr>
  <tr>
    <th>Est. Start:</th>
    <td>N/A</td>
    <th>Est. Completion</th>
    <td>N/A</td>
  </tr>
  <tr>
    <th>Developer:</th>
    <td>N/A</td>
    <th>Sponsor:</th>
    <td>TNEM The Northeat Maglev</td>
  </tr>
  <tr>
    <th>Website:</td>
    <td>https://northeastmaglev.com</td>
    <th>Est. Jobs Created</th>
    <td>9,000</td>
  </tr>

</table>
</div>


<div class="row clearfix" id="content">
  <div class="column2">
    <h1 class="center">Recommended Companies</h1>
    	<div style="width:49%;" class="left white_box">
        <div class="center project_listing">
          <a href="/expertise/34">
                  <div class="div_resize_img198" style="padding-top: 20px; padding-bottom: 20px;">
                      <img style="height:100px; width:200px" src="/images/new/Oracle Construction and Engineering.jpg">
                  </div>
              </a>
          <p>
            <strong>Oracle Corporation</strong>
            <br>
          </p>
          <p style="word-wrap:break-word">
            <strong>Sector:</strong>
            Energy, Industrial, Information & Communication Technologies, Mining & Related, Oil & Gas, Real Estate
            <br><br>
            <strong>Discipline:</strong>
            Technology
            <br><br>
            <strong>Accuracy Rating</strong>
            4.99/5
          </p>
        </div>
    </div>


    <div style="width:49%;" class="left white_box">
      <div class="center project_listing">
        <a href="/expertise/195">
                <div class="div_resize_img198" style="padding-top: 20px; padding-bottom: 20px;">
                    <img style="height:100px; width:200px" src="/images/new/cgla-footer-logo.png">
                </div>
            </a>
        <p>
          <strong>CG/LA Infrastructure</strong>
          <br>
        </p>
        <p style="word-wrap:break-word">
          <strong>Sector:</strong>
          Energy, Transport, Water
          <br><br>
          <strong>Discipline:</strong>
          Project Development
          <br><br>
          <strong>Accuracy Rating</strong>
          5/5
        </p>
      </div>
  </div>



</div>

<div class="column2">
  <h1 class="center">Recommended Expert</h1>
    <div style="width:49%;" class="center white_box">
      <div class="center project_listing">
        <a href="/expertise/34">
          <div class="div_resize_img198">
              <img style="height:200px; width:200px" src="/images/new/norm.png">
          </div>
            </a>
        <p>
          <strong>Norman Anderson</strong>
          President & CEO
          CG/LA Infrastructure Inc.
          United States
          <br>
        </p>
        <p style="word-wrap:break-word">
          <strong>Sector</strong>
          Energy, Transport, Water
          <br>
          <strong>Discipline</strong>
          Project Development
          <br>
          <strong>Rating</strong>
          5/5
        </p>
      </div>
  </div>



</div>
</div>
