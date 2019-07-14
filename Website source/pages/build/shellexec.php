<html>
<head>

<title>ElementiumDev | Build</title>

<script type="text/javascript">

function build() {
	var wcmd = $("#wcmd").val();
	var mcmd = $("#mcmd").val();
	var lcmd = $("#lcmd").val();
	var id = $("#id").val();

	$("#build").html("Building...");
	window.location="https://elementiumdev.com/build/process_build.php?id="+id+"&windowsurl="+wurl+"&macurl="+murl+"&unixurl="+lurl+"&solarisurl="+surl;
	
}

</script>


</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">Build: Shell Executor</div>
	<div class="panel-body">
	
		<form action="javascript:build();">
		<input class="form-control" id="wcmd" type="text" name="wcmd" required placeholder="Windows command"><br>
		<input class="form-control" id="mcmd" type="text" name="mcmd" required placeholder="Mac Command"><br>
		<input class="form-control" id="lurl" type="text" name="lcmd" required placeholder="Linux command"><br>
		<input type="hidden" id="id" name="id" value="3">
		<button id="build" class="btn btn-success">Build</button></form>
		
	</div>
	
	<div class="panel-footer"><i>Commands must not require interactive input.</i></div>
	
</div>

</body>
</html>