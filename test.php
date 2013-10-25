<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Unit Test Page</title>
<!-- This is the QUnit testing suite. You can get rid of this on the live server unless something's going wrong. -->
<script type="text/javascript" src="jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="qunit/qunit-1.12.0.js"></script>
<script type="text/javascript" src="main.js"></script>
<link rel="stylesheet" href="qunit/qunit-1.12.0.css" />

<script type="text/javascript">
test("checkBid",function() {
	equal(checkBid(0,1,18),-1);
	equal(checkBid(1,1,18),0);
	equal(checkBid(8,1,18),0);
	equal(checkBid(18,1,18),0);
	equal(checkBid(19,1,18),1);
});
</script>
</head>

<body>
<div id="qunit"></div>
</body>
</html>
