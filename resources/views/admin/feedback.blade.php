<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Customer Feedback</title>
  <link rel="shortcut icon" type="image" href="./images/logo.png">
  <link rel="stylesheet" href="style/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
  <style>
    .active{
      color: #f1f1f1;
      background-color:#1b203d;
}
  </style>
</head>

<body>
<div id="mySidenav" class="sidenav">
    <div class="brand">
	<p class="logo"><span >House</span>Rental</p>
    </div>
    <a href="/dashboardAdmin" class="icon-a active"><i class="fa-sharp fa-thin fa-grid-horizontal"></i>&nbsp;&nbsp;Dashboard</a>
    <a href="/aprove"class="icon-a"><i class="fa-solid fa-thumbs-up"></i>&nbsp;&nbsp;Approve Rent</a>
    <a href="/users"class="icon-a"><i class="fa fa-users icons"></i> &nbsp;&nbsp;Users Management</a>
    <a href="/feedback"class="icon-a"><i class="fa-sharp fa-solid fa-comment-dots"></i> &nbsp;&nbsp;Feedback</a>

</div>

<div class="container" style="margin-bottom:6%;">
        <div class="header">
        <h2 class="title">بەشی فیدباکەکان</h2>
            </div>
</div>




<table class="table">
  <thead>
    <th>ناو</th>
    <th>ئیمەیل</th>
    <th>دەربارە</th>
    <th>پەیام</th>
    <th></th>
  </thead>
  <tbody>
    <tr>
      <td data-label="ناو">ashkan</td>
      <td data-label="ئیمەیل">ashkan@gmail.com</td>
      <td data-label="دەربارە">website</td>
      <td data-label="پەیام">zor spas</td>
      <td><a href="/Feedback"><button class="delete-btn" style="width:70%;">سڕینەوە</button></a></td>
    </tr>
  </tbody>
</table>





           
</body>

</html>