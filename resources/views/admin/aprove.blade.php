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
      <a href="/dashboardAdmin" class="icon-a active"><i class="fa-sharp fa-thin fa-grid-horizontal"></i>&nbsp;&nbsp;Dashboard</a>
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
    <th>ناونیشانی <br>1</th>
    <th>ناونیشانی<br>2</th>
    <th>شار</th>
    <th>ژمارەی قات</th>
    <th>ژمارەی ژور</th>
    <th>قەبارە بە مەتر</th>
    <th>نرخی كرێ</th>
    <th> باسكردنی خانوو</th>
    <th>ڕەسم</th>
    <th>ئەپروف</th>
  </thead>
  <tbody>
    @foreach($data as $house)
    <tr>
      <td data-label="ناو">{{$house->title}}</td>
      <td data-label="داواکاری">{{$house->first_address}}</td>
      <td data-label="کۆی گشتی">{{$house->second_address}}</td>
      <td data-label="ژمارەی مۆبایل">{{$house->city}}</td>
      <td data-label="داواکاریەکی تایبەت">{{$house->num_floor}}</td>
      <td data-label="داواکاریەکی تایبەت">{{$house->num_room}}</td>
      <td data-label="قەبارە بە مەتر">{{$house->square_footage}}</td>
      <td data-label="گەیشتووە بەدەستی">{{$house->rent_amount}}$</td>
      <td data-label="داواکاریەکی تایبەت">{{$house->description}}</td>
      <td data-label="ناونیشان"></td>
      <td data-label="وەرگرتن" style="width: 16%;">
      <a href=""><img class="check" src="images/check.png" alt=""></a><a href=""><img class="check" src="images/reject.png" alt=""></a>
      
      </td>
    </tr>
    @endforeach
  </tbody>
</table>



           
</body>

</html>