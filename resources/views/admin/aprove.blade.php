<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Customer Orders</title>
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
.remove-button{
  border: none;
  background: none;
  color: inherit;
  padding: 0;
  font: inherit;
  cursor: pointer;
}
  </style>
</head>

<body>

    <div id="mySidenav" class="sidenav">
        <div class="brand">
        <p class="logo"><span >House</span>Rental</p>
        </div>
      <a href="/dashboard" class="icon-a active"><i class="fa-sharp fa-thin fa-grid-horizontal"></i>&nbsp;&nbsp;Dashboard</a>
      <a href="/aprove"class="icon-a"><i class="fa-solid fa-thumbs-up"></i>&nbsp;&nbsp;Approve Rent</a>
      <a href="/users"class="icon-a"><i class="fa fa-users icons"></i> &nbsp;&nbsp;Users Management</a>
      <a href="/feedback"class="icon-a"><i class="fa-sharp fa-solid fa-comment-dots"></i> &nbsp;&nbsp;Feedback</a>
    
    </div>

<div class="container" style="margin-bottom:6%;">
        <div class="header">
        <h2 class="title">بەشی ئەپروف</h2>
            </div>
</div>





<table class="table">
  <thead>
    <th>جۆری خانوو</th>
    <th>ناونیشانی 1</th>
    <th>ناونیشانی 2</th>
    <th>شار</th>
    <th>ژمارەی ژور</th>
    <th>ژمارەی قات</th>
    <th>نرخ</th>
    <th>پەیام</th>
    <th>ڕەسم</th>
    <th>ئەپروف</th>
  </thead>
  <tbody>
    <tr>
      <td data-label="ناو">فێلا</td>
      <td data-label="داواکاری">هەواری شار</td>
      <td data-label="کۆی گشتی"> گەسی ئامانج</td>
      <td data-label="ژمارەی مۆبایل">سلێمانی</td>
      <td data-label="داواکاریەکی تایبەت">6</td>
      <td data-label="داواکاریەکی تایبەت">2</td>
      <td data-label="گەیشتووە بەدەستی">500،000</td>
      <td data-label="داواکاریەکی تایبەت">ڕووە قیبلەیە</td>
      <td data-label="ناونیشان">ڕەسمەكە</td>
      <td data-label="وەرگرتن" style="width: 16%;">
      <a href=""><button class="changeuser-btn" style="width: 50%;">وەرگرتن</button></a><br>
      <a href=""><button class="delete-btn" style="width: 50%;">سڕینەوە</button></a>
      </td>
    </tr>
  </tbody>
</table>



           
</body>

</html>