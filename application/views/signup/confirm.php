<section class="main-content container">
    <h1 class="h1-xl">Confirmation</h1>
    <p>Please confirm your profile information so we can save it to your GViP profile and get you started.</p>

    <?php $this->load->view('signup/_progress', array('step' => 'confirm')) ?>

    <div class="form-cta">
        <div class="interior">
<!--            <h2 class="h3-std">Confirm Info</h2>-->
<!--            <div class="down-arrow"></div>-->
            
            <form action="#" method="post" accept-charset="utf-8" id="member_form" name="join_network" class="form">
            <p>You can update your profile information at any time from your Account Settings.</p>
            <div class="confirm-info">
                    <div class="cropped-photo">
                        <?php $src = safe_image(SIGNUP_IMAGE_PATH, $signup['userphoto'], USER_NO_IMAGE_PATH . USER_IMAGE_PLACEHOLDER, array('max' => 198)) ?>
                        <img src="<?php echo $src ?>" alt="Expert's photo">
                    </div>
                    <div class="review">
                        <table>
                            <?php if (! empty($signup['is_developer'])) { ?>
                                <th>Project Developer:</th>
                                <td>Yes, I manage one or more projects.</td>
                            <?php } ?>
                            <tr>
                                <th>First Name:</th>
                                <td><?php echo $signup['firstname'] ?></td>
                            </tr>
                            <tr>
                                <th>Last Name:</th>
                                <td><?php echo $signup['lastname'] ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $signup['email'] ?></td>
                            </tr>
                            <tr>
                                <th>Discipline:</th>
                                <td><?php echo $signup['discipline'] ?></td>
                            </tr>
                            <tr>
                                <th>Sector(s):</th>
                                <td><?php 
                                    $select2 = array();
                                    $i = 0;
                                    $len = count($signup['sub-sector']);
                                    foreach ($signup['sub-sector'] as $key => $value) {
                                        $select2[$key] = explode(':', $signup['sub-sector'][$key]);
                                        if ($i == $len - 1) {echo implode(': ', $select2[$key]);}
                                        else {echo implode(': ', $select2[$key]).'<br><br>';}
                                        $i++;
                                    }
                                ?></td>
                            </tr>
                            <tr>
                                <th>Title:</th>
                                <td><?php echo $signup['title'] ?></td>
                            </tr>
                            <tr>
                                <th>Organization:</th>
                                <td><?php echo $signup['organization'] ?></td>
                            </tr>
                            <tr>
                                <th>Org Structure:</th>
                                <td><?php echo $signup['public_status'] ?></td>
                            </tr>
                            <tr>
                                <th>City:</th>
                                <td><?php echo $signup['city'] ?></td>
                            </tr>
                            <tr>
                                <th>Country:</th>
                                <td><?php echo $signup['country'] ?></td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="form-buttons centered">
                    <a href="/signup/pickphoto" class="btn std clear">Back</a>
                    <input type="submit" name="submit" class="btn std dk-green" value="Create Account" />
                    <div class="terms">By Clicking <strong>Create Account</strong> I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a></div>
                </div>
            </form>
        </div>
    </div>
</section>