<div class="vernav2 iconmenu">
    <?php
    $fst_segment = $this->uri->segment(1);
    $snd_segment = $this->uri->segment(2);
    ?>
	<ul>
		<li <?php if ($fst_segment == "members" || $fst_segment == "myaccount" ) { echo "class='current'"; } ?>><a href="#membersub" class="editor">Members</a>
			<span class="arrow"></span>
			<ul id="membersub">
				<li <?php if ($snd_segment == "view_all_members" || $fst_segment == "myaccount") { echo "class='current'"; } ?>><a href="/admin.php/members">View All</a></li>
				<li <?php if ($snd_segment == "manage_group") { echo "class='current'"; } ?>><a href="/admin.php/members/manage_group">Member Groups</a></li>
				<li <?php if ($snd_segment == "new_member") { echo "class='current'"; } ?>><a href="/admin.php/members/new_member">Add New Member</a></li>
				<li <?php if ($snd_segment == "new_expert_advert") { echo "class='current'"; } ?>><a href="/admin.php/members/new_expert_advert">Add New Expert Advert</a></li>
                <li <?php if ($snd_segment == "export" && $fst_segment == "members") { echo "class='current'"; } ?>><a href="/admin.php/members/export">Export Experts</a></li>
			</ul>
		</li>
		<li <?php if ($fst_segment == "projects" ) { echo "class='current'"; } ?>><a href="#projectsub" class="gallery">Projects</a>
			<span class="arrow"></span>
			<ul id="projectsub">
				<li <?php if ($snd_segment == "view_all_projects") { echo "class='current'"; } ?>><a href="/admin.php/projects/view_all_projects">View All</a></li>
				<li <?php if ($snd_segment == "create") { echo "class='current'"; } ?>><a href="/admin.php/projects/create">Add New Project</a></li>
			</ul>
		</li>

        <li <?php if ($fst_segment == "forums") { echo "class='current'"; } ?>><a href="#forumsub" class="gallery">Forums</a>
            <span class="arrow"></span>
            <ul id="forumsub">
                <li <?php if ($fst_segment == "forums" && ($snd_segment == "index" || $snd_segment == "")) { echo "class='current'"; } ?>><a href="/admin.php/forums">View All</a></li>
                <li <?php if ($fst_segment == "forums" && $snd_segment == "create") { echo "class='current'"; } ?>><a href="/admin.php/forums/create">Add New Forum</a></li>
            </ul>
        </li>

        <li <?php if($fst_segment == "store") { echo "class='current'"; } ?>><a href="#storesub" class="gallery">Store</a>
            <span class="arrow"></span>
            <ul id="storesub">
                <li <?php if ($fst_segment == "store" && ($snd_segment == "index" || $snd_segment == "")) { echo "class='current'"; } ?>><a href="/admin.php/store">View All</a></li>
                <li <?php if ($fst_segment == "store" && ($snd_segment == "create")) { echo "class='current'"; } ?>><a href="/admin.php/store/create">Add New Item</a></li>
            </ul>
        </li>
		<li <?php if ($fst_segment == "concierge" ) { echo "class='current'"; } ?>><a href="#concierge" class="help">Concierge</a>
			<span class="arrow"></span>
			<ul id="concierge">
				<li <?php if ($snd_segment == "") { echo "class='current'"; } ?>><a href="/admin.php/concierge">Manage Questions</a></li>
			</ul>
		</li>
        <li <?php if ($fst_segment == 'updates' ) { echo 'class="current"'; } ?>><a href="#updatessub" class="elements">Comments</a>
            <span class="arrow"></span>
            <ul id="updatessub">
                <li <?php if ($snd_segment == '' || $snd_segment == 'index') { echo 'class="current"'; } ?>><a href="/admin.php/updates">Manage Feed</a></li>
            </ul>
        </li>
        <li <?php if ($fst_segment == 'discussions' ) { echo 'class="current"'; } ?>><a href="#discussionssub" class="elements">Discussions</a>
            <span class="arrow"></span>
            <ul id="discussionssub">
                <li <?php if ($snd_segment == '' || $snd_segment == 'index') { echo 'class="current"'; } ?>><a href="/admin.php/discussions">View All</a></li>
                <li <?php if ($fst_segment == "discussions" && ($snd_segment == "create")) { echo "class='current'"; } ?>><a href="/admin.php/discussions/create">Add New Discussion</a></li>
            </ul>
        </li>
        <li <?php if ($fst_segment == "security" ) { echo "class='current'"; } ?>><a href="#securitysub" class="support">Security</a>
            <span class="arrow"></span>
            <ul id="securitysub">
                <li <?php if ($snd_segment == "banning") { echo "class='current'"; } ?>><a href="/admin.php/security/banning">User Banning</a></li>
                <li <?php if ($snd_segment == "throttling") { echo "class='current'"; } ?>><a href="/admin.php/security/throttling">Throttling Configuration</a></li>
            </ul>
        </li>
        <li <?php if ($fst_segment == "googleapi" ) { echo "class='current'"; } ?>><a href="#reportssub" class="addons">Reports</a>
            <span class="arrow"></span>
            <ul id="reportssub">
                <li <?php if ($snd_segment == "reports") { echo "class='current'"; } ?>><a href="/admin.php/googleapi/reports">Google Analytics</a></li>
                <li <?php if ($snd_segment == "setting") { echo "class='current'"; } ?>><a href="/admin.php/googleapi/setting">GA Account Setting</a></li>
            </ul>
        </li>
	</ul>
	<a class="togglemenu"></a>
	<br><br>
</div><!--leftmenu-->
