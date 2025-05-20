<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Panel</title>
  <link rel="shortcut icon" type="image" href="./images/logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="style/style.css">
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
  <a href="{{route('AdminDashboard')}}" class="icon-a active"><i class="fa-sharp fa-thin fa-grid-horizontal"></i>&nbsp;&nbsp;Dashboard</a>
  <a href="{{route('aprove')}}"class="icon-a"><i class="fa-solid fa-thumbs-up"></i>&nbsp;&nbsp;Approve Rent</a>
  <a href="{{route('users')}}"class="icon-a"><i class="fa fa-users icons"></i> &nbsp;&nbsp;Users Management</a>
  <a href="{{route('feedback')}}"class="icon-a"><i class="fa-sharp fa-solid fa-comment-dots"></i> &nbsp;&nbsp;Feedback</a>
  <a href="/register"class="icon-a"><i class="fa-sharp fa-solid fa-comment-dots"></i> &nbsp;&nbsp;Register</a>

</div>

<div class="container" style="margin-bottom:6%;">
        <div class="header">
        <h2 class="title"> بەشی سەرەكی</h2>
            </div>
</div>


<div id="main">

	<div class="head">
	
	<div class="clearfix"></div>
</div>

	<div class="clearfix"></div>
	<br/>

	
	<div class="col-div-4" style="margin-top: 3%;">
		<div class="box">
			<p style="margin-left: 10%;">246<br/><span>بەكارهێنەران</span></p>
			<img src="images/team.png" class="box-icon" style="width:20%; height:65px;">
		</div>
	</div>


	<div class="col-div-4" style="margin-top: 3%;">
		<div class="box">
			<p style="margin-left: 0%;">20<br/><span>وەرگرتنی خانوو</span></p>
			<img src="images/house.png" class="box-icon" style="width:20%; height:50px;">
		</div>
	</div>


	<div class="col-div-4" style="margin-top:3%;">
		<div class="box">
			<p style="margin-left: 10%;">5<br/><span>مۆركردن خانوو</span></p>
			<img src="images/stamp.png" class="box-icon" style="width:20%; height:50px;">
		</div>
	</div>

	<div class="col-div-4" style="margin-top:3%;">
		<div class="box">
			<p style="margin-left: 8%;">1,200,000<br/><span>قازانج</span></p>
			<img src="images/compensation.png" class="box-icon" style="width:20%; height:50px;">
		</div>
	</div>

	<div class="col-div-4" style="margin-top:3%;">
		<div class="box">
			<p style="margin-left: 10%;">13<br/><span>فیدباك</span></p>
			<img src="images/satisfaction.png" class="box-icon" style="width:20%; height:50px;">
		</div>
	</div>

	
	<div class="clearfix"></div>
	<br/><br/>





           
</body>

</html>