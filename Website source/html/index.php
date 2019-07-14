<?php 

include_once "/var/www/ctrl/accounts/operations.php";

$user = "0";

$signedin = false;

if(isset($_COOKIE['user'])) { 
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$signedin = true;
		//echo "<b>Welcome, " . $result['username'] . ".</b>";
		$user = $result['username'];
	} else { 
		//echo "<b>Before you get started, we'll need you to <a href=\"/register\">register</a> or <a href=\"/login\">login</a>.</b>";
	}
} else {
	//echo "<b>Before you get started, we'll need you to <a href=\"/register\">register</a> or <a href=\"/login\">login</a>.</b>";
}

?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="js/flat-ui.min.js" type="text/javascript"></script>
<script src="assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="assets/sweetalert.css">

<script type="text/javascript">

function redirect(url) {
	swal("Redirecting...", "On to way!", "info");
	setTimeout(function(){
		window.location=url;
	},3000);
}

function extras() {
	var html = "<form><select class='form-control' id='extra'><option value='none'>Select extra</option><option value='ircsetup'>IRC Setup - $25</option><option value='extendedsupport'>Extended Support - $15</option><option value='custom'>Custom</option></select></form>";
	swal({
		title:"Extras",
		html:true,
		text:html,
		type:"info",
		showCancelButton:true,
		confirmButtonText:"Buy",
		cancelButtonText:"Cancel",
		closeOnConfirm:false
	},function(confirm){
		swal.close();
		if(confirm) {
			var extra = $("#extra").val();
			if(extra !== "none") {
				if(extra !== "custom") {
					$.ajax({
						url:"extras.php?id="+extra,
						dataType:"json",
						success:function(data) {
							if(data['success'] == true) {
								var purchasehtml = "<b>Amount:</b> " + data['amount'] + "<br><b>Address:</b> " + data['address'] + "<br><a href='" + data['url'] + "' target='_blank'>View status / invoice.</a>";
								swal({
									title:"Purchase",
									html:true,
									text:purchasehtml,
									type:"success",
									showCancelButton:false,
									confirmButtonText:"Close",
									closeOnConfirm:true
								});
							} else {
								setTimeout(function(){swal("Error", data['error'], "error");},500);
							}
						}
					});
				} else {
					swal.close();
					setTimeout(function(){
						swal({   
							title: "Custom amount",   
							text: "Use this for extra amounts such as server fees, donations, etc.",   
							type: "input",   
							showCancelButton: true,   
							closeOnConfirm: false,   
							animation: "pop",   
							inputPlaceholder: "Amount in USD" }, 
							function(inputValue)
							{   
								if (inputValue === false) return false;      
								if (inputValue === "") {     
									swal.showInputError("Enter an amount in USD.");     
									return false   
								}      
								swal("Please wait", "Processing payment of: $" + inputValue, "success"); 
	
								$.ajax({
									url:"extras.php?id="+extra+"&amount="+inputValue,
									dataType:"json",
									success:function(data) {
										if(data['success'] == true) {
											var purchasehtml = "<b>Amount:</b> " + data['amount'] + "<br><b>Address:</b> " + data['address'] + "<br><a href='" + data['url'] + "' target='_blank'>View status / invoice.</a>";
											swal({
												title:"Custom purchase",
												html:true,
												text:purchasehtml,
												type:"success",
												showCancelButton:false,
												confirmButtonText:"Close",
												closeOnConfirm:true
											});
										} else {
											setTimeout(function(){swal("Error", data['error'], "error");},500);
										}
									}
								});
	
						});
					},1000);
				}
			} else {
				setTimeout(function(){swal("Extras", "No extra selected.", "error");},500);
			}
		} else {

		}
	});
}

</script>

<title>ElementiumDev | Home</title>
</head>

<body>

<p>
<center><img alt="Logo" src="img/logo.png"></center>
</p>

<div class="container container-fluid">
	<?php 
	
	include "/var/www/ctrl/news.php";
	
	$news = get_news();
	
	foreach ($news as $one) {
		?> 
		
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $one['time'] . " - " . $one['title'];?></h3>
		</div>	
		<div class="panel-body">
				<?php echo $one['content'];?>
		</div>
	</div>
	<?php }?>

	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Account</h3>
		</div>
		<div class="panel-body">
			
			<?php
	
			if($signedin) { 
				echo "<b>Welcome, $user.</b><br>";
			} else { 
				echo "<b>Before you get started, we'll need you to <a href=\"javascript:redirect('/register');\">register</a> or <a href=\"javascript:redirect('/login');\">login</a>.</b>";
			}
			
			
			?>
			
		</div>
		<div class="panel-footer clearfix">
		
			<btn class="btn btn-success" onclick="javascript:extras();" style="float:left;width:45%">Purchase premium extras.</btn>
			<btn class="btn btn-primary" onclick="javascript:infoExtras();" style="float:right;width:45%">Learn about premium extras.</btn>
		</div>
	
	</div>

	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title"><h3 class="panel-title">Multi-OS Downloader</h3>
	  </div>
	  <div class="panel-body">
	    Price: $15<br>
	
		Duration: Lifetime<br>
		</div>
	  <div class="panel-footer">
	  
	  	<?php 
		if($user !== "0") {
			if(user_owns(1, $user)) {?>
				<b><a href='javascript:redirect("/build?id=1");'>Build</a></b>
			<?php } else { ?>
				<b><a href='javascript:redirect("/purchase?id=1");'>Purchase</a></b>
			<?php }
		} else {
			echo "Login or register first.";
		}
	
		?>
		
	  </div>
	</div>
	
	<div class="panel panel-default">
	  <div class="panel-heading"><h3 class="panel-title">IRC Remote Controller</h3></div>
	  <div class="panel-body">
	    Price: $25<br>
	
		Duration: Lifetime<br>
		</div>
	  <div class="panel-footer">
	  
	  	<?php 
		if($user !== "0") {
			if(user_owns(2, $user)) {?>
				<b><a href="javascript:redirect('/build?id=2');">Build</a></b>
			<?php } else { ?>
				<b><a href="javascript:redirect('/purchase?id=2');">Purchase</a></b>
			<?php }
		} else {
			echo "Login or register first.";
		}
		
		?>
	  
	  </div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">Shell Executor</h3></div>
		<div class="panel-body">
			Price: $2<br>
			
			Duration: Lifetime<br>
			</div>
		<div class="panel-footer">
			
		<?php 
		if($user !== "0") {
			if(user_owns(3, $user)) {?>
				<b><a href="javascript:redirect('/build?id=3');">Build</a></b>
			<?php } else { ?>
				<b><a href="javascript:redirect('/purchase?id=3');">Purchase</a></b>
			<?php }
		} else {
			echo "Login or register first.";
		}
		
		?>
		
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">InfectFlu</h3></div>
		<div class="panel-body">
			Price: $30<br>
			
			Duration: Lifetime<br>
		</div>
		<div class="panel-footer">
			
		<?php 
		if($user !== "0") {
			if(user_owns(4, $user)) {?>
				<b><a href="javascript:redirect('/build?id=4');">Build</a></b>
			<?php } else { ?>
				<b><a href="javascript:redirect('/purchase?id=4');">Purchase</a></b>
			<?php }
		} else {
			echo "Login or register first.";
		}
		
		?>
		
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-body">
			Coming soon...
		</div>
	</div>
</div>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5717082eaa1a4dbe40f80efc/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>

</html>