<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

<div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">

```
<h2 style="color:#dc2626;">❌ Request Rejected</h2>

<p>Hello {{ $request->user->name }},</p>

<p>
    Unfortunately, your request for a <strong>Resident Letter</strong> has been rejected.
</p>

<p><strong>Reason:</strong></p>

<div style="background:#fee2e2; padding:15px; border-radius:6px; color:#7f1d1d;">
    {{ $reason }}
</div>

<p style="margin-top:20px;">
    You may review your request and submit a new one if necessary.
</p>


<p style="margin-top:30px;">
    Regards,<br>
    Community Services Team
</p>
```

</div>

</body>
</html>
