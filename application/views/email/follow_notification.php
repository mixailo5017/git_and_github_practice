<div class="message" style="border: 1px solid #D9D9D9;background: #FFF;">
    <table width="100%" class="dark" style="border-collapse: collapse;font-family: 'Open Sans', Helvetica, Arial, sans-serif;font-weight: 400;">
        <thead>
        <tr>
            <td style="background: #515663;border-top: 1px solid #F8F8F9;border-bottom: 1px solid #D9D9D9;border-left: #F8F8F9;color: #FFF;border: none;">
                <img src="<?php echo $spacer ?>" alt="" width="17" style="display: block;">
            </td>
            <th style="background: #515663;border-top: 1px solid #F8F8F9;border-bottom: 1px solid #D9D9D9;color: #FFF;font-size: 16px;text-align: left;vertical-align: middle;border: none;">
                <div>
                    <img src="<?php echo $spacer ?>" alt="" height="13" width="300" style="display: block;">
                </div>
                <img src="<?php echo base_url() . $follower['photo_src'] ?>" class="ib" width="33" alt="<?php echo $follower['fullname'] ?>'s photo" style="display: inline-block;vertical-align: middle;">
                <img src="<?php echo $spacer ?>" class="ib" alt="" width="12" style="display: inline-block;vertical-align: middle;">
                <a href="<?php echo base_url() . 'expertise/' . $follower['uid'] ?>" style="color: #40a7e2;text-decoration: underline;"><?php echo $follower['fullname'] ?></a> is now following you on GViP
                <div>
                    <img src="<?php echo $spacer ?>" alt="" height="13" width="300" style="display: block;">
                </div>
            </th>
            <td style="background: #515663;border-top: 1px solid #F8F8F9;border-bottom: 1px solid #D9D9D9;color: #FFF;border: none;">
                <img src="<?php echo $spacer ?>" alt="" width="17" style="display: block;">
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <img src="<?php echo $spacer ?>" alt="" width="17" style="display: block;">
            </td>
            <td>
                <div>
                    <img src="<?php echo $spacer ?>" alt="" height="24" width="300" style="display: block;">
                </div>

                <!--                        <h1 style="font-size: 21px;color: #373b43;padding-bottom: 9px;font-weight: bold;margin: 0;">Heading 1</h1>-->
                <p style="font-size: 13px;color: #373b43;line-height: 1.3;padding-bottom: 27px;margin: 0; word-break: break-word">
                    <?php echo $following['firstname'] ?>, you have a new follower: <br><br>
                    <strong>Name:</strong> <?php echo $follower['fullname'] ?> <br>
                    <?php if (! empty($follower['organization'])) { ?>
                    <strong>Organization:</strong> <?php echo $follower['organization'] ?> <br>
                    <?php if (! empty($follower['title'])) { ?>
                    <strong>Title:</strong> <?php echo $follower['title'] ?> <br>
                    <?php } ?>
                    <?php } ?>
                </p>
                <p>
                    To learn more, <a href="<?php echo base_url() . 'expertise/' . $follower['uid'] ?>">view <?php echo $follower['fullname'] ?>'s profile</a>. <br>
                    Or, <a href="<?php echo base_url() . 'mygvip/myfollowers' ?>">see all your followers</a>.
                </p>

                <div>
                    <img src="<?php echo $spacer ?>" alt="" height="24" width="300" style="display: block;">
                </div>
            </td>
            <td>
                <img src="<?php echo $spacer ?>" alt="" width="17" style="display: block;">
            </td>
        </tr>
        </tbody>
    </table>
</div><!-- message -->

