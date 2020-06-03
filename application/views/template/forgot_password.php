<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
</head>
<body>
	<table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tbody>
			<tr>
				<td colspan="2" style="background:#3FA6A6;color:#ffffff;padding:10px;font-size:14px">
					<strong>
						<span class="il"><?php echo WEBSITE_NAME; ?></span>
					</strong>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="background:#f6f6f6;color:#000;padding:20px 25px 0px;line-height:20px;">
					<strong>Dear <?php if(!empty($name)) echo ucwords($name); ?>,</strong><br>
					<p style="font-family:Verdana">
						<font size="2">Welcome to <?php echo WEBSITE_NAME; ?>.</font>
					</p>
					<p style="font-family:Verdana">
						<?php if($type=='api') { ?>
							<font size="2">Your reset password code is <b><?php echo $code; ?></b>.</font>
						<?php } else { ?>
							<font size="2">Please click on the link below to reset your password</font>
						<?php } ?>
					</p>
				</td>
			</tr>
			<?php if($type=='admin') { ?>
				<tr>
					<td colspan="2" style="background:#f6f6f6;color:#000;padding:0 25px;line-height:20px;">
						<p style="font-family:Verdana">
							<a href="<?php echo $reset_password_url; ?>" target="_blank"><?php echo $reset_password_url; ?></a>
						</p>
					</td>
				</tr>
			<?php } ?>
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
					<?php if($type=='api') { ?>
						<font size="2">Above code is valid for next 24 hours only.</font>
					<?php } else { ?>
						<font size="2">Above link is valid for next 24 hours only.</font>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	</body>
</html>