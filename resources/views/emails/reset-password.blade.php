<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
</head>

<body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table width="500" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;margin-top:40px;padding:30px;box-shadow:0 5px 20px rgba(0,0,0,0.05);">

<tr>
<td align="center">

<h2 style="margin-bottom:10px;color:#333;">
CommunityLetters
</h2>

<p style="color:#666;font-size:14px;margin-bottom:30px;">
Secure Password Reset
</p>

</td>
</tr>

<tr>
<td>

<p style="font-size:15px;color:#333;">
Hello {{ $user->name ?? 'User' }},
</p>

<p style="font-size:14px;color:#555;line-height:1.6;">
We received a request to reset your password. Click the button below to set a new one.
</p>

</td>
</tr>

<tr>
<td align="center" style="padding:30px 0;">

<a href="{{ $url }}"
style="
background:linear-gradient(135deg,#667eea,#764ba2);
color:white;
padding:14px 28px;
border-radius:8px;
text-decoration:none;
font-weight:bold;
display:inline-block;
">
Reset Password
</a>

</td>
</tr>

<tr>
<td>

<p style="font-size:13px;color:#777;">
This link will expire in 60 minutes.
</p>

<p style="font-size:13px;color:#777;">
If you did not request a password reset, you can safely ignore this email.
</p>

</td>
</tr>

<tr>
<td style="padding-top:30px;border-top:1px solid #eee;">

<p style="font-size:12px;color:#aaa;">
© {{ date('Y') }} CommunityLetters. All rights reserved.
</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>