<!-- Profile Completeness Index Meter -->
<div id="meter" class="profile-meter" data-value="<?php echo $pci ?>" data-max="100">
    <p><?php echo sprintf(lang('MemberPciMeter'), $pci) ?></p>
    <div class="bar">
        <div class="progress"></div>
    </div>
    <div class="cta-container">
        <button><?php echo lang('DismissReminder') ?></button>
        <a href="/profile/account_settings"><?php echo lang('GoToYourProfile') ?></a>
    </div>
</div>
