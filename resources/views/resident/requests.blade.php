@extends('layout.app')

@section('content')

<h2>My Letter Requests</h2>

<table border="1">

<tr>
<th>Letter Type</th>
<th>Purpose</th>
<th>Status</th>
<th>Date</th>
</tr>

@foreach($requests as $request)

<tr>

<td>{{ $request->letter_type }}</td>

<td>{{ $request->purpose }}</td>

<td>

@if($request->status == 'pending')
<span style="color:orange">Pending</span>
@endif

@if($request->status == 'approved')
<span style="color:green">Approved</span>
@endif

@if($request->status == 'rejected')
<span style="color:red">Rejected</span>
@endif

</td>

<td>{{ $request->created_at }}</td>

</tr>

@endforeach

</table>

@endsection