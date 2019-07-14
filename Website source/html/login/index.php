<html>
<head>
<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>

<title>ElementiumDev | Login</title>

<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
<script src="../assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="../assets/sweetalert.css">

<link rel="stylesheet" href="../css/login_register.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../js/flat-ui.min.js" type="text/javascript"></script>

<title>ElementiumDev | Register</title>

<script type="text/javascript">
function login() {

	var username = $("#username").val();
	var password = $("#password").val();

	swal("Login", "Logging in as " + username, "info");
	
	$("#submit").prop("disabled", true);
	$.ajax({
		url:"login.php?username="+username+"&password="+password,
		dataType:"json",
		success:function(data){
			swal.close();
			setTimeout(function(){
				if(data['success'] == true ) {
					swal({
						title:"Login",
						text:"Logged in. Welcome, " + username + ".",
						type:"success",
						showCancelButton: false,
						confirmButtonText:"Proceed",
						closeOnConfirm: true
					},
					function(){
						swal("Redirecting...", "Redirecting you to the dashboard...", "success");
						setTimeout(function(){
							window.location="../";
						},2500);
					});
				} else {
					swal("Login", "Error: " + data['error'], "error");
				}
			}, 250);
		}
	});
}

</script>
</head>

<body>

<div class="container">
	<div class="wrapper">
		<form action="javascript:login()" class="form-signin">       
		    <h3 class="form-signin-heading">Login</h3>
			  <hr class="colorgraph"><br>
			  <input id="username" type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
			  <input id="password" type="password" class="form-control" name="password" placeholder="Password" required=""/>     		  
			 
			  <button class="btn btn-lg btn-primary btn-block"  name="Submit" value="Login" type="Submit" id="submit">Login</button>  			
		</form>			
	</div>
</div>

<center><div class="alert alert-danger" role="alert" style="max-width: 420px">
	<a href="../" class="alert-link">Cancel and return to homepage.</a>
</div></center>

</body>
</html>

