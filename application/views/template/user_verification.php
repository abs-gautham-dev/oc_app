<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Verification </title>
</head>
<body>
	<table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tbody>
			<tr>
				<td style="background:#3FA6A6;height:40px; padding-left:15px; color:#ffffff;">
					<strong>
						<span class="il" style="color:#ffffff;"><font size="4"><?php echo WEBSITE_NAME; ?></font></span>
					</strong>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="background:#f6f6f6;padding-left:20px;padding-top:20px;padding-bottom:10px;line-height:20px;">
					<strong>Dear <?php if(!empty($name)) echo ucwords($name); ?>,</strong><br>
					<p style="font-family:Verdana">
						<font size="2">Welcome to <?php echo WEBSITE_NAME; ?>.</font>
					</p>
					<p style="font-family:Verdana">
						<font size="2">Click on the link below to complete your registration</font>
					</p>
				</td>
			</tr>
			
			<tr>
				<td colspan="2" style="background:#f6f6f6;padding-left:20px; padding-bottom:20px;line-height:20px;">
					<p style="font-family:Verdana">
						<a href="<?php echo $verification_url; ?>" target="_blank"><?php echo $verification_url; ?></a>
					</p>
				</td>
			</tr>
			
			<tr>
				<td style="background:#f6f6f6;color:#000;padding:25px;line-height:20px;" colspan="2" height="10" >
					<p style="font-family:Verdana">
						<font size="2">
							<a style="text-decoration:none;color:#000">Best Regards,<br>
								<span class="il"><?php echo WEBSITE_NAME.' Team.';?></span> 
							</a> 
						</font>
					</p>
				
				</td>
			</tr>
			<tr>
				<td style="background:#c4c4c4;height:30px; padding-left:15px;">
					<b>Note :</b> 
					<font size="2">Above link is valid for next 12 hours only.</font>
				</td>
			</tr>
		</tbody>
	</table>
	</body>
</html>