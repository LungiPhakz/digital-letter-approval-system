<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

<div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">

```
<h2 style="color:#4f46e5;">✅ Request Approved</h2>

<p>Hello {{ $request->user->name }},</p>

<p>
    Your request for a <strong>Resident Letter</strong> has been successfully approved.
</p>

<p>
    Please make sure to check the <strong>official stamp and signature</strong> before downloading your letter from the portal.
</p>

<a href="{{ url('/') }}"
   style="display:inline-block; margin-top:20px; padding:12px 20px; background:#4f46e5; color:white; text-decoration:none; border-radius:6px;">
    View Your Letter
</a>

<p style="margin-top:30px;">Regards,<br>Community Services Team</p>
```

</div>

</body>
</html>
