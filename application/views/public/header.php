<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-43491822-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-43491822-3');
</script>
</head>
<header class="search">
    <div class="container">
        <nav class="m-nav logo">
            <ul>
                <?php if (Auth::check() && (! App::is_down_for_maintenence() || App::is_ip_allowed_when_down())) { ?>
                    <li>
                        <span class="iicon-menu m-menu-btn"></span>
                    </li>
                <?php } ?>
                <li>
                    <a href="/"><span><img src="/images/new/GViP_Logos_white.png" width="64" height="40"/></span></a>
                </li>
                <li><a href="/login"><span>Sign In</span></a></li>
                <li class="join"><a href="/signup"><span>Join for Free</span></a></li>
            </ul>
        </nav>
    </div>
</header>
