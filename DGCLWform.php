<html>
<head>
<title>Deutsche Grammofon Cable Labs Generator</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <style type="text/css">
.form-style-1 {
    margin:10px auto;
    max-width: 400px;
    padding: 20px 12px 10px 20px;
    font: 13px "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
.form-style-1 li {
    padding: 0;
    display: block;
    list-style: none;
    margin: 10px 0 0 0;
}
.form-style-1 label{
    margin:0 0 3px 0;
    padding:0px;
    display:block;
    font-weight: bold;
}
.form-style-1 input[type=text], 
.form-style-1 input[type=date],
.form-style-1 input[type=datetime],
.form-style-1 input[type=number],
.form-style-1 input[type=search],
.form-style-1 input[type=time],
.form-style-1 input[type=url],
.form-style-1 input[type=email],
textarea, 
select{
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    border:1px solid #BEBEBE;
    padding: 7px;
    margin:0px;
    -webkit-transition: all 0.30s ease-in-out;
    -moz-transition: all 0.30s ease-in-out;
    -ms-transition: all 0.30s ease-in-out;
    -o-transition: all 0.30s ease-in-out;
    outline: none;
    width: 500px;
}
.form-style-1 input[type=text]:focus, 
.form-style-1 input[type=date]:focus,
.form-style-1 input[type=datetime]:focus,
.form-style-1 input[type=number]:focus,
.form-style-1 input[type=search]:focus,
.form-style-1 input[type=time]:focus,
.form-style-1 input[type=url]:focus,
.form-style-1 input[type=email]:focus,
.form-style-1 textarea:focus, 
.form-style-1 select:focus{
    -moz-box-shadow: 0 0 8px #88D5E9;
    -webkit-box-shadow: 0 0 8px #88D5E9;
    box-shadow: 0 0 8px #88D5E9;
    border: 1px solid #88D5E9;
}
.form-style-1 .field-divided{
    width: 49%;
}

.form-style-1 .field-long{
    width: 100%;
}
.form-style-1 .field-select{
    width: 100%;
}
.form-style-1 .field-textarea{
    height: 100px;
}
.form-style-1 input[type=submit], .form-style-1 input[type=button]{
    background: #4B99AD;
    padding: 8px 15px 8px 15px;
    border: none;
    color: #fff;
}
.form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
    background: #4691A4;
    box-shadow:none;
    -moz-box-shadow:none;
    -webkit-box-shadow:none;
}
.form-style-1 .required{
    color:red;
}
</style>
    
    

<body>
    
<form name="selector">
<ul class="form-style-1">
    <li><label>Asset : </label><select name="select_xml" id="select_xml">
    <option value="">---Select XML---</option>

<?php

if ($handle = opendir('./xmlin/')) {

    $ignoredFiles = array('.', '..', '_notes', '@eaDir', 'index.html');

    while (false !== ($entry = readdir($handle))) {
        if(in_array(pathinfo($entry, PATHINFO_BASENAME), $ignoredFiles)) continue;
        echo "<option value=\"./xmlin/" . pathinfo($entry, PATHINFO_BASENAME) . "\">" . pathinfo($entry, PATHINFO_FILENAME) . "</option>";
    }

    closedir($handle);
}
?>

    </select></li>
    <li><input type="button" name="Submit" value="Load XML Data" onClick="dataload(select_xml.value)">
    </li>
    </ul>
    </form>
    
    <form name="cablelabs" action="DGCLWxml.php" method="post" onsubmit="return validateForm()">
    <ul id="dataform" class="form-style-1">
        <li><label>Asset ID: </label><input type="text" id="assetID" name="asset_ID"/></li>
        <li><label>Title : </label><input type="text" id="assetTitle" name="asset_Title"/></li>
        <li><label>Genre : </label><input type="text" id="assetGenre" name="asset_Genre"/></li>
        <li id="last-item"><input type="submit" value="Save Translation"></li>
        </ul>
    </form>
    </body>

<script>
    function validateForm() {
    var x = document.forms["cablelabs"]["asset_ID"].value;
    if (x == "") {
        alert("Asset ID must be filled out");
        return false;
    }
}
    </script>

<script>
    
    function create(htmlStr) {
    var frag = document.createDocumentFragment(),
        temp = document.createElement('li');
    temp.innerHTML = htmlStr;
    while (temp.firstChild) {
        frag.appendChild(temp.firstChild);
      }
        return frag;
    }

    function removetracks() {
        var paras = document.getElementsByClassName('tracks');

        while(paras[0]) {
            paras[0].parentNode.removeChild(paras[0]);
        }
    }
    
  function dataload(xmlpath) {
      removetracks();
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", xmlpath, false);
      xmlhttp.setRequestHeader('Content-Type', 'text/xml');
      xmlhttp.send("");
      xmlDoc = xmlhttp.responseXML;
      $("#assetID").val(xmlDoc.getElementsByTagName("ICPN")[0].childNodes[0].nodeValue);
      $("#assetTitle").val(xmlDoc.getElementsByTagName("Release")[0].getElementsByTagName("ReferenceTitle")[0].getElementsByTagName("TitleText")[0].childNodes[0].nodeValue);
      $("#assetGenre").val(xmlDoc.getElementsByTagName("GenreText")[0].childNodes[0].nodeValue);
      
      var resourcelist = xmlDoc.getElementsByTagName("ResourceList")[0];
      var tracklist = resourcelist.getElementsByTagName("SoundRecording");
      if(tracklist.length == 0) {
          tracklist = resourcelist.getElementsByTagName("Video");
          var video = true;
      }
      
      for(var i=0; i< tracklist.length; i++){
        if(!video) {var text = tracklist[i].getElementsByTagName("SoundRecordingDetailsByTerritory")[0].getElementsByTagName("TitleText")[0].childNodes[0].nodeValue;}
        if(video) {var text = tracklist[i].getElementsByTagName("VideoDetailsByTerritory")[0].getElementsByTagName("TitleText")[0].childNodes[0].nodeValue;}
        var generateHere = document.getElementById("dataform");
        var frag = create("<li class='tracks'><label>Track "+i+": </label><input type='text' id='track"+i+"' name='track_"+i+"'/></li>");
        generateHere.insertBefore(frag, document.getElementById("last-item"));
        $("#track"+i).val(text);
      }
}
    </script>
</html>