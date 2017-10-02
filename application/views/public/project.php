<div class="clearfix m-public-facing" id="content">
    <section class="m-project-details bordered">
        <div class="clearfix m-meta-container">
            <div class="m-thumb">
                <img src="<?php echo $project['photo_src'] ?>" alt='<?php echo $project['projectname'] ?>'>
            </div>
            <div class="m-meta">
                <h1><?php echo $project['projectname'] ?></h1>
                <h2><?php echo $project['country'] ?></h2>

                <div class="btn-container">
                    <a href="#join" class="bttn follow">Follow Project</a>
                </div>

            </div>
        </div>
        <p><?php echo nl2br($project['description']) ?></p>
    </section>
    <section class="m-project-lead bordered">
        <h2>Project Executive</h2>
        <p>Registered users have full access to project executives' projects and credentials, and can contact them directly. Our powerful algorithms make it easier than ever before for you connect with peers across the infrastructure industry. The world's leading infrastructure project executives are waiting for you to reach out to them and establish effective partnerships. Get started with GViP today!</p>
        <div class="clearfix  m-meta-container">
            <div class="m-thumb">
                <img src="<?php echo $project_executive['photo_src'] ?>" alt='Project Executive of <?php echo $project['projectname'] ?>'>
            </div>
            <div class="m-meta">

                <div class="btn-container">
                    <a href="#join" class="bttn disabled">Follow</a>
                    <a href="#join" class="bttn disabled">Send Message</a>
                </div>

            </div>
        </div>
    </section>
    <section class="m-project-overview">
        <h2>Project Overview</h2>
        <p>Sign up for a GViP account today and get access to a full list of current, detailed information on every project in our database. With easy access to both top-level and granular information, project overviews provide you with all the information you need to take your next step. From budget and location to stage and sector, we've got everything you need.</p>
    </section>

    <section class="m-join-cta">
        <h2><a name="join"></a>Join GViP and View the Full Project Profile for <br><?php echo $project['projectname'] ?>.</h2>
        <p>As a GViP member, you'll gain access to over 2000 project profiles worldwide, and become part of a worldwide community of project developers and infrastructure professionals focused on delivering projects.</p>
        <div class="btn-container">
            <a href="/login" class="bttn btn-outlined">Sign In</a>
            <a href="/signup" class="bttn btn-filled">Create Account</a>
        </div>
    </section>
</div>