<?php $settings = App\Models\AdminSettings::first(); ?>
<table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
	<tbody><tr>
		<td align="center" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" style="font-size:16px;line-height:21px;font-family:'Source Sans Pro',Helvetica,sans-serif;color:#484646" width="600">
				<tbody><tr>
				<td>
				<div style="padding:20px 0;font-size:18px;font-weight:bold;color:#f02d00;border-bottom:1px solid #dcdcdc">
			<a href="{{{ url('/')}}}" style="display:inline-block;color:#fff;text-decoration:none" target="_blank">
			<img  src="{{{asset('public/img/logo-email.png')}}}" width="150">
		</a>
		</div>
	</td>
</tr>

<tr>
	<td style="padding:30px 0 50px 0">
		<div style="text-align:center">
			<img src="{{{URL::asset('public/avatar/default.jpg')}}}" style="width:72px; border-radius: 50px;">
			<div style="margin:10px 0 5px 0;font-size:26px;font-weight:bold;line-height:24px;letter-spacing:-1px">
			{{{ trans('auth.password_reset_2') }}}
			</div>
<div style="font-size:22px;line-height:25px;letter-spacing:-1px;color:#888686">
		{{{ trans('auth.password_reset_mail') }}}
	</div>
	<div style="margin:30px 0 35px 0">
		<a href="{{{ url('password/reset/'.$token) }}}" style="display:inline-block;padding:4px 20px;font-weight:bold;line-height:24px;text-decoration:none;color:#f02d00;border:1px solid #f02d00;border-radius:3px" target="_blank">
			{{{ trans('auth.password_reset_2') }}}
			</a>
		</div>
</div>

  </td>
</tr>
<tr>
</tr>
<tr>
<td>
	<div style="padding:20px 30px;font-size:13px;line-height:17px;color:#888686;background:#f7f7f7">

<div style="text-align:center">
&copy; {{{ $settings->title }}} - <?php echo date('Y'); ?>
</div>
</div>
</td>
</tr>
</tbody></table>
</td>
</tr>
<tr>
<td height="30" style="height:30px"></td>
</tr>
</tbody></table>