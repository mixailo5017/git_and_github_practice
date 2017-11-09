<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>This Week in GViP</title>
      <style>
        body, p, div {font-size:13px;color:#373b43;word-break:break-word;}
        body, div {margin:0}
      </style>
    </head>
    <body>
        <div>
            <div style="background:#f3f3f3">
                <div style="width:575px;margin:auto">
                    <div>
                        <div>
                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                        <a href="https://www.gvip.io/" target="_blank"><img alt="GViP" src="https://www.gvip.io/images/email/logo.png" style="display:block" /></a>
                        <div>
                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" /></div>
                    </div>
                    <div style="border:1px solid #d9d9d9;background:#fff">
                        <table style="border-collapse:collapse;font-family:'Open Sans',Helvetica,Arial,sans-serif;font-weight:400" width="100%">
                            <thead>
                                <tr>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;border-left:#f8f8f9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <th style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;font-size:16px;text-align:left;vertical-align:middle;border:none">
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        This week in GViP<br />
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                    </th>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <td>
                                        {{ content }}
                                    </td>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                                <tr>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;border-left:#f8f8f9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <th style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;font-size:16px;text-align:left;vertical-align:middle;border:none">
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        Featured Experts
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                    </th>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <td>
                                        <div>
                                            <img alt="" height="24" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        <table width="100%">
                                            <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <div>
                                                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[0]; ?>"><img height="120" src=<?php echo '"' . $experts[0]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[0]; ?>"><?php echo htmlspecialchars($experts[0]['firstname'] . ' ' . $experts[0]['lastname'], ENT_QUOTES); ?></a><br />
                                                            <?php echo htmlspecialchars($experts[0]['title'], ENT_QUOTES); ?><br />
                                                            <?php echo htmlspecialchars($experts[0]['organization'], ENT_QUOTES); ?></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[1]; ?>"><img height="120" src=<?php echo '"' . $experts[1]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[1]; ?>"><?php echo htmlspecialchars($experts[1]['firstname'] . ' ' . $experts[1]['lastname'], ENT_QUOTES); ?></a><br />
                                                            <?php echo htmlspecialchars($experts[1]['title'], ENT_QUOTES); ?><br />
                                                            <?php echo htmlspecialchars($experts[1]['organization'], ENT_QUOTES); ?></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[2]; ?>"><img height="120" src=<?php echo '"' . $experts[2]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[2]; ?>"><?php echo htmlspecialchars($experts[2]['firstname'] . ' ' . $experts[2]['lastname'], ENT_QUOTES); ?></a><br />
                                                            <?php echo htmlspecialchars($experts[2]['title'], ENT_QUOTES); ?><br />
                                                            <?php echo htmlspecialchars($experts[2]['organization'], ENT_QUOTES); ?></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img alt="" height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[3]; ?>"><img height="120" src=<?php echo '"' . $experts[3]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/expertise/<?php echo $uid[3]; ?>"><?php echo htmlspecialchars($experts[3]['firstname'] . ' ' . $experts[3]['lastname'], ENT_QUOTES); ?></a><br />
                                                            <?php echo htmlspecialchars($experts[3]['title'], ENT_QUOTES); ?><br />
                                                            <?php echo htmlspecialchars($experts[3]['organization'], ENT_QUOTES); ?></p>
                                                    </td>                                                                                   
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                                <tr>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;border-left:#f8f8f9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <th style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;font-size:16px;text-align:left;vertical-align:middle;border:none">
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        Featured Projects<br />
                                        <div>
                                            <img alt="" height="13" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                    </th>
                                    <td style="background:#515663;border-top:1px solid #f8f8f9;border-bottom:1px solid #d9d9d9;color:#fff;border:none">
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                                <tr>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <td>
                                        <div>
                                            <img alt="" height="24" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        <table width="100%">
                                            <tbody>
                                                <tr>
                                                    <td valign="top">
                                                        <div>
                                                            <img height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[0]; ?>"><img height="120" src=<?php echo '"' . $projects[0]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[0]; ?>"><?php echo htmlspecialchars($projects[0]['projectname'], ENT_QUOTES); ?></a></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[1]; ?>"><img height="120" src=<?php echo '"' . $projects[1]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[1]; ?>"><?php echo htmlspecialchars($projects[1]['projectname'], ENT_QUOTES); ?></a></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[2]; ?>"><img height="120" src=<?php echo '"' . $projects[2]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[2]; ?>"><?php echo htmlspecialchars($projects[2]['projectname'], ENT_QUOTES); ?></a></p>
                                                    </td>
                                                    <td valign="top">
                                                        <div>
                                                            <img height="1" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="126" /></div>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[3]; ?>"><img height="120" src=<?php echo '"' . $projects[3]['imageURL'] . '"'; ?> width="120" /></a></p>
                                                        <p style="font-size:13px;color:#373b43;line-height:1.3;padding-bottom:27px;margin:0;word-break:break-word">
                                                            <a href="https://www.gvip.io/projects/<?php echo $slug[3]; ?>"><?php echo htmlspecialchars($projects[3]['projectname'], ENT_QUOTES); ?></a></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <table style="border-collapse:collapse;font-family:'Open Sans',Helvetica,Arial,sans-serif;font-weight:400">
                            <tbody>
                                <tr>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                    <td>
                                        <div>
                                            <img alt="" height="28" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="300" /></div>
                                        <p style="font-size:11px;color:#798397;line-height:1.2;padding-bottom:27px;margin:0">
                                            GViP &reg; is a registered trademark of <a href="https://www.cg-la.com/" target="_blank">CG/LA Infrastructure</a> &copy; <?php echo date("Y"); ?><br>
                                            {{ unsubscribe_link }}</p>
                                    </td>
                                    <td>
                                        <img alt="" src="https://www.gvip.io/images/email/spacer.gif" style="display:block" width="17" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>