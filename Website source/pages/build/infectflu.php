<html>
<head>

<title>ElementiumDev | Build</title>

<script type="text/javascript">

function build() {
	var message = $("#message").val();
	var url = $("#url").val();
	var id = $("#id").val();

	$("#build").html("Building...");
	window.location="https://elementiumdev.com/build/process_build.php?id="+id+"&message="+message+"&url="+url;
	
}

</script>


</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">Build: Shell Executor</div>
	<div class="panel-body">
	
		<form action="javascript:build();">
		<textarea rows="5" class="form-control" id="message" type="text" name="message" required placeholder="Skype message"></textarea><br>
		<input class="form-control" id="url" type="text" name="url" required placeholder="URL"><br>
		<input type="hidden" id="id" name="id" value="4">
		<button id="build" class="btn btn-success">Build</button></form>
		
	</div>
	
	<div class="panel-footer"><i>Commands must not require interactive input.</i></div>
	
</div>

</body>
</html>