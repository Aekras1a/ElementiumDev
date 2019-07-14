<html>
<head>

<title>ElementiumDev | Build</title>

<script type="text/javascript">

function build() {
	var prefix = $("#prefix").val();
	var host = $("#host").val();
	var port = $("#port").val();
	var channel = $("#channel").val();
	var filename = $("#filename").val();
	var id = $("#id").val();

	$("#build").html("Building...");
	window.location="https://elementiumdev.com/build/process_build.php?id="+id+"&prefix="+prefix+"&host="+host+"&port="+port+"&channel="+channel+"&filename="+filename;
	
}

</script>
</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">Build: IRC</div>
	<div class="panel-body">
	
		<form action="javascript:build();">
		<input class="form-control" id="prefix" type="text" name="prefix" required placeholder="Command prefix"><br>
		<input class="form-control" id="host" type="text" name="host" required placeholder="Host"><br>
		<input class="form-control" id="port" type="text" name="port" required placeholder="Port"><br>
		<input class="form-control" id="channel" type="text" name="channel" required placeholder="Channel"><br>
		<input class="form-control" id="filename" type="text" name="filename" required placeholder="Install name"><br>
		<input type="hidden" id="id" name="id" value="2">
		<button id="build" class="btn btn-success">Build</button></form>
		
	</div>
	
	<div class="panel-footer"><i>Do NOT include the "#" before the channel name.</i></div>
	
</div>

</body>
</html>