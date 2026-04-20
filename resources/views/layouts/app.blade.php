<!doctype html>
<html lang="en" class="h-full">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Community Letter Management System</title>

<script src="https://cdn.tailwindcss.com/3.4.17"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>

* {
box-sizing:border-box;
}

body{
font-family:'Inter','Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

.gradient-primary{
background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
}

.card-modern{
background:white;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.08);
transition:all .3s ease;
}

.card-modern:hover{
transform:translateY(-5px);
box-shadow:0 15px 40px rgba(0,0,0,0.12);
}

</style>

</head>

<body class="h-full">

@yield('content')

</body>
</html>