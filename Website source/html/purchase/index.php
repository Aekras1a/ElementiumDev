<?php 

include_once '/var/www/ctrl/accounts/operations.php';

$data = product_data($_GET['id']);

?>

<html>
<head>
<title>ElementiumDev | Purchase</title>

<script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="../js/flat-ui.min.js" type="text/javascript"></script>
<script src="../assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="../assets/sweetalert.css">


<script type="text/javascript">

function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
         }
    }

    document.body.appendChild(form);
    form.submit();
}

var price = <?php echo $data['price'];?>;

function getPrice() {
	return price;
}

var coupon = "0";

function buy() {
	if(coupon !== "0"){
		url = "handle.php?id=<?php echo $_GET['id'];?>&coupon="+coupon+"&method="+$("#method").val();
	} else {
		url = "handle.php?id=<?php echo $_GET['id'];?>&method="+$("#method").val();
	}
		
	$.ajax({
		url:url,
		dataType:"json",
		success:function(data){
			if(data['success'] == true) {
				if(data['free'] == true) {
					swal({
						title:"Transaction",
						text:"Free product added to your account!",
						type:"success",
						showCancelButton:false,
						confirmButtonText:"Proceed",
						closeOnConfirm: true
					},function(){
						window.location="../";
					});
				} else {
					swal({
						title:"Transaction",
						text:"Invoice created",
						type:"success",
						showCancelButton:false,
						confirmButtonText:"View invoice",
						closeOnConfirm:true
					},function(){
						if($("#method").val() == "cp"){
							window.location=data['url'];
						} else if($("#method").val() == "pm") {
							post("https://perfectmoney.is/api/step1.asp", data['fields']);
						} else if($("#method").val() == "cfy") {
							window.location=data['url'];
						}
					});
				}
			} else {
				swal("Transaction", data['error'], "error");
			}
		}

	});
	
}

function useCoupon() {

	swal({   
		title: "Coupon",   
		text: "Enter your coupon",   
		type: "input",   
		showCancelButton: true,   
		closeOnConfirm: false,   
		animation: "pop",   
		inputPlaceholder: "Coupon code" }, 
		function(inputValue)
		{   
			if (inputValue === false) return false;      
			if (inputValue === "") {     
				swal.showInputError("Enter the coupon code.");     
				return false   
			}      
			swal("Processing", "Using coupon: " + inputValue, "success"); 

			$.ajax({
				url:"coupon.php?code=" + inputValue + "&price=" + <?php echo $data['price'];?> + "&id=" + <?php echo $_GET['id'];?>,
				dataType:"json",
				success:function(data){
					swal.close();
					if(data['success'] == true) {
						$("#price").html("<b>Price:</b> $" + data['new_price']);
						//$("#buy").html("<b><a href=\"handle.php?id=<?php echo $_GET['id']; ?>&coupon=" + inputValue + "\">Purchase now.</a></b>");

						coupon = inputValue;
						price = data['new_price'];
						setTimeout(function(){
							swal("Coupon", "Discount applied.", "success");
						},500);
					} else {
						setTimeout(function(){
							swal("Coupon", "Error: " + data['error'], "error");
						},500);
					}
				}
			});

	});

}

function displayTOS() {
	var html = $("#tos").val();
	swal({
		title:"Agree",
		text:html,
		confirmButtonText:"Agree",
		cancelButtonText:"Decline",
		showCancelButton:true
	},function(confirm){
		if(!confirm){
			window.location="https://elementiumdev.com";
		}
	});
		
}

</script>
</head>

<body onload="javascript:displayTOS();">

<?php 

$user = "0";

if(isset($_COOKIE['user'])) { 
	$result = check_auth($_COOKIE['user']);
	if($result['success'] == true) {
		$user = $result['username'];
	} else {
		$stop = true;
	}
} else {
	?>
<div class="alert alert-danger" role="alert">
	
<?php 
	echo "<b>You need to be logged in to purchase an item. <a href=\"../\">Homepage</a>";
	$stop = true;
}

if(!isset($_GET['id'])) {
	?>
	<div class="alert alert-danger" role="alert">
		
	<?php 
	echo "<b>Product ID missing.</b>";
	$stop = true;
	?></div><?php
}

if(!product_exists($_GET['id'])) {
	?>
	<div class="alert alert-danger" role="alert">
		
	<?php 
	echo "<b>Product does not exist.</b>";
	$stop = true;
	?></div><?php
}

if(user_owns($_GET['id'], $user)) {
	?>
	<div class="alert alert-danger" role="alert">
		
	<?php 
	echo "<b>You already own this product.</b>";
	$stop = true;
	?></div><?php
}

if(isset($stop)) {
	if($stop) {
		die();
	}
}

?>


<div class="panel panel-primary">
	<div class="panel-heading"><?php echo $data['disp_name']?></div>
	<div class="panel-body">
		<div id="price"><b>Price:</b> <?php echo $data['price'];?></div>
		<div><select class="form-control" id="method"><option value="cp">CoinPayments (BTC; 2 confirms)</option><option value="pm" disabled="true">PerfectMoney (USD) - disabled</option></select></div>
		<div id="buy"><b><a href="javascript:buy();">Purchase now.</a></b></div>
	</div>
	<div class="panel-footer"><a href="javascript:useCoupon();">Use coupon.</a></div>
</div>

<div style="display:none">
	<textarea id="tos" rows="10" cols="10" disabled>Terms of Service
	1. All sales are final and refunds are at our discretion only.
	2. Leaking, cracking, or attempting to do the aforementioned activities may result in the termination of your license(s).
	3. The free extensive support provided is only given on issues directly concerning our products. We will not help you setup external programs required. Please purchase our add-ons if you require extra assistance.
	4. ElementiumDev reserves the right to append to, delete, or modify our Terms of Service at any point in time. Consumers will not be notified and their cooperation with our new terms will be assumed.
	5. Violating the current Terms of Service will result in the termination of your license(s).
	6. We reserve the right to not provide support, termination of support services will be at our discretion. 
	7. By buying anything from ElementiumDev, you agree to these Terms of Service.</textarea>
</div>

</body>

</html>
