<html>
<head>
<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
<script src="../assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="../assets/sweetalert.css">

<title>ElementiumDev | Register</title>

<link rel="stylesheet" type="text/css" src="../css/login_register.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../js/flat-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
function register() {

	var username = $("#username").val();
	var password = $("#password").val();

	swal("Register", "Registering: " + username, "info");
	
	$("#submit").prop("disabled", true);
	$.ajax({
		url:"register.php?username="+username+"&password="+password,
		dataType:"json",
		success:function(data){
			swal.close();
			setTimeout(function(){
				if(data['success'] == true ) {
					swal({
						title:"Register",
						text:"Registered. Welcome, " + username + ".",
						type:"success",
						showCancelButton: false,
						confirmButtonText:"Proceed",
						closeOnConfirm: true
					},
					function(){
						swal("Redirecting...", "Redirecting you to the login page...", "success");
						window.location="../login";
					});
				} else {
					swal("Register", "Error: " + data['error'], "error");
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
		<form action="javascript:register()" class="form-signin">       
		    <h3 class="form-signin-heading">Register</h3>
			  <hr class="colorgraph"><br>
			  <input id="username" type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
			  <input id="password" type="password" class="form-control" name="password" placeholder="Password" required=""/>     		  
			 
			  <button class="btn btn-lg btn-primary btn-block"  name="Submit" value="Register" type="Submit" id="submit">Register</button>  			
		</form>			
	</div>
</div>

<center><div class="alert alert-danger" role="alert" style="max-width: 420px">
	<a href="../" class="alert-link">Cancel and return to homepage.</a>
</div></center>

</body>