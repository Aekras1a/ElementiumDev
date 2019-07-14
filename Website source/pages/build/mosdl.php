<html>
<head>

<title>ElementiumDev | Build</title>

<script type="text/javascript">

function build() {
	var wurl = $("#wurl").val();
	var murl = $("#murf").val();
	var lurl = $("#lurl").val();
	var surl = $("#surl").val();
	var id = $("#id").val();

	$("#build").html("Building...");
	window.location="https://elementiumdev.com/build/process_build.php?id="+id+"&windowsurl="+wurl+"&macurl="+murl+"&unixurl="+lurl+"&solarisurl="+surl;
	
}

</script>


</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">Build: Multi-OS Downloader</div>
	<div class="panel-body">
	
		<form action="javascript:build();">
		<input class="form-control" id="wurl" type="text" name="wurl" required placeholder="Windows URL"><br>
		<input class="form-control" id="murl" type="text" name="murl" required placeholder="Mac URL"><br>
		<input class="form-control" id="lurl" type="text" name="lurl" required placeholder="Linux URL"><br>
		<input class="form-control" id="surl" type="text" name="surl" required placeholder="Solaris URL"><br>
		<input type="hidden" id="id" name="id" value="1">
		<button id="build" class="btn btn-success">Build</button></form>
		
	</div>
	
	<div class="panel-footer"><i>URLs do NOT have to be different.</i></div>
	
</div>

</body>
</html>