<html>
<head>
<title>zeromsg</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="js/flat-ui.min.js" type="text/javascript"></script>
<script src="assets/sweetalert.min.js"></script> <link rel="stylesheet" type="text/css" href="assets/sweetalert.css">
<script src="js/sha256.js"></script>
<script src="js/aes.js"></script>

<script type="text/javascript">
var refresh = false;
var phrase = "";

function loaded() {

	$("#append").prop('disabled',true);

	setInterval(function(){
		if(refresh) {
			get();
		}
	},5000);
}

function send() {
	var passphrase = phrase;
	
	if($("#passphrase").val() !== "") {
		passphrase = $("#passphrase").val();
	}
	
	var hashedPassphrase = CryptoJS.SHA256(passphrase).toString();

	var content = $("#content").val();
	var hashedContent = CryptoJS.AES.encrypt(content,passphrase,{iv:""})

	//$("#content").val("Hash: " + hashedContent);
	
	$.ajax({
		url:"handle.php?phrase="+encodeURI(hashedPassphrase)+"&content="+encodeURI(hashedContent),
		dataType:"json",
		success:function(data){
		
		}
	});
}

function get() {
	var passphrase = phrase;
		
	if($("#passphrase").val() !== "") {
		passphrase = $("#passphrase").val();
		$("#submit").html("checking...");
	} else {
		$("#submit").html("updating...");
	}
	phrase = passphrase;
	refresh = true;
	$("#passphrase").val('');
	var hashedPassphrase = CryptoJS.SHA256(passphrase).toString();

	$.ajax({
		url:"handle.php?phrase="+encodeURI(hashedPassphrase),
		dataType:"json",
		success:function(data){
			var hashedContent = decodeURIComponent(data['content']);
			/*hashedContent = hashedContent.replace(new RegExp("%2F","g"),"/");
			hashedContent = hashedContent.replace(new RegExp("%3D","g"),"=");
			hashedContent = hashedContent.replace(new RegExp(escapeRegExp("+"),"g"),"");
			*/
			$("#submit").html("check");
			if(hashedContent !== "") {
				// prompt("Copy: ",hashedContent);
				$("#content").val(CryptoJS.AES.decrypt(hashedContent,passphrase,{iv:""}).toString(CryptoJS.enc.Utf8));
			} else {
				$("#content").val('');
			}
		}
	});

	$("#append").prop('disabled',false);
}

function escapeRegExp(str) {
  return str.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"); // $& means the whole matched string
}

function append() {
	swal({   
		title: "Append",   
		text: "Enter message to append.",   
		type: "input",   
		showCancelButton: true,   
		closeOnConfirm: false,   
		animation: "slide-from-top",   
		inputPlaceholder: "Hi guys, ..." }, 
		function(inputValue)
		{   
			if (inputValue === false) return false;      
			if (inputValue === "") {     
				swal.showInputError("Write something! :P");     
				return false   
			}      
			swal("Appending", "Adding your content...", "success"); 
			setTimeout(function(){
				swal.close();
				$("#content").val($("#content").val()+"\n["+timeStamp() + "] " + inputValue);
				send();
			},2000);
	});
}

function timeStamp() {
	  var now = new Date();
	  var date = [ now.getMonth() + 1, now.getDate(), now.getFullYear() ];
	  var time = [ now.getHours(), now.getMinutes(), now.getSeconds() ];
	  var suffix = ( time[0] < 12 ) ? "AM" : "PM";
	  time[0] = ( time[0] < 12 ) ? time[0] : time[0] - 12;
	  time[0] = time[0] || 12;
	  for ( var i = 1; i < 3; i++ ) {
	    if ( time[i] < 10 ) {
	      time[i] = "0" + time[i];
	    }
	  }
	  return date.join("/") + " " + time.join(":") + " " + suffix;
}

</script>
</head>

<body onload="loaded();" style="background-image:url('http://www.wallpaperup.com/uploads/wallpapers/2015/05/04/678628/4439d947dec3836725eaa55cefcf879e.jpg')">

<div class="container container-fluid" style="padding-top: 20px">

	<div class="panel panel-primary">
		
		<div class="panel-heading">
		
			<h3 class="panel-title">zeromsg</h3>
		
		</div>
		
		<div class="panel-body">
		
			Description here.
		
		</div>
		
	</div>
	
	<div class="panel panel-success">
	
		<div class="panel-heading">
		
			<form action="javascript:get();">
			
				<input type="text" required placeholder="Passphrase" id="passphrase" class="form-control"> 
				<br>
				<button class="form-control btn btn-primary" id="submit">check</button>
				
			</form>
		
		</div>
		
		<div class="panel-body" style="width:100%">
		
			<textarea disabled id="content" rows="25" cols="25" style="box-sizing:border-box;width:100%">How to use:
1. Enter passphrase and click check
2. If passphrase exists, message will be shown. If passphrase does not exist, box will be empty.
3. Edit content in the box.
4. Click the update button.
5. Voila! You can click the check-passphrase button again to retrieve updated content.

Remember, only people with the passphrase can access your content! Be careful with who you share the phrase with, as he or she will have complete control over your content!

Copyright 2016 @ Vortex20000</textarea>
		
		</div>
		
		<div class="panel-footer clearfix">
			
			<button id="append" onclick="javascript:append();" class="btn btn-danger" style="width:100%">Append</button>
		
		</div>
	</div>

</div>

</body>

</html>