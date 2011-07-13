<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax test 1 :: with jQuery</title>
<script  src="http://lib.az.mn/functions_js/jquery.js" language="Javascript" type="text/javascript"></script>
<script language="javascript">

$(document).ready(function(){
	//global vars
	var userName = $("#name");
	var userEmail = $("#email");
	var userTxt = $("#txt");
	var targetDiv = $("#targetDiv");
	
	//Нэр имэйл нүднүүдийг бөглөсөн эсэхийг шалгах функц
	function checkCommentsForm(){
		if(userName.attr("value") && userTxt.attr("value"))
			return true;
		else
			return false;
	}
	
	//Form submit хийх үед ажиллана
	$("#form1").submit(function(){
		if(checkCommentsForm()){
			targetDiv.show('fast');
			//test.php файл руу илгээх
			$.ajax({
				type: "POST", url: 'test.php', data: "name="+userName.val()+"&email="+userEmail.val()+"&txt="+userTxt.val(),
				complete: function(data){
					//test.php файлын үр дүнг араас нь хэвлэж харуулах
					targetDiv.append(data.responseText);
					//test.php файлын үр дүнг targetDiv-d хэвлэж харуулах
					//targetDiv.html(data.responseText);
				}
			});
		}
		else alert("Нэр имэйл нүдийг бөглөнө үү!");
		return false;
	});
});


</script>
<style type="text/css">
<!--
body,td,th {
	font-family: Tahoma;
	font-size: 11px;
}
body {
	margin-left: 10px;
	margin-top: 10px;
	margin-right: 10px;
	margin-bottom: 10px;
}
-->
</style></head>

<body>
<h2>AJAXT DEMO</h2>
<p>Та div элэмент ашиглаж вэбсайтаа угсарч сурвал AJAX болон Javascript ашиглан үйлдэл хийхэд илүү хялбар зүгээр байх болно. </p>
<p>Уг кодыг татахын тулд <a href="ajax1.rar">энд дарна</a> уу.</p>
<div>
  <form id="form1" name="form1" method="post" action="" onsubmit="return false;">
    <p>
      <input type="text" name="name" id="name" />
      Нэр</p>
    <p>
      <input type="text" name="email" id="email" />
      Имэйл
    </p>
    <p>
      <input type="text" name="txt" id="txt" />
      Мэдээлэл
    </p>
    <p>
      <input type="submit" name="button" id="button" value="go go..." />
    </p>
  </form>
</div>
<div id="targetDiv" style="display:none;">

</div>
</body>
</html>