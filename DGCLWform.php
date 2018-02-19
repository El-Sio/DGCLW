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
    -moz-box-shadow: 0 0 8px black;
    -webkit-box-shadow: 0 0 8px black;
    box-shadow: 0 0 8px black;
    border: 1px solid black;
}
.form-style-1 .field-divided{
    width: 49%;
}

        .readonly {
            background-color: lightgrey;
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
    background: yellow;
    padding: 8px 15px 8px 15px;
    border: none;
    color: black;
}
.form-style-1 input[type=submit]:hover, .form-style-1 input[type=button]:hover{
    background: gold;
    box-shadow:none;
    -moz-box-shadow:none;
    -webkit-box-shadow:none;
}

.form-style-1 .required{
    color:red;
}
</style>
    
    

<body>
    
    <datalist id="xmls">
    <?php

if ($handle = opendir('./xmlin/')) {

    $ignoredFiles = array('.', '..', '_notes', '@eaDir', 'index.html');

    while (false !== ($entry = readdir($handle))) {
        if(in_array(pathinfo($entry, PATHINFO_BASENAME), $ignoredFiles)) continue;
        echo "<option value=\"./xmlin/" . pathinfo($entry, PATHINFO_BASENAME) . "\"/>";
    }

    closedir($handle);
}
?>
    </datalist>


<div>
    <img src="LogoDG.jpg" alt="Deutsche Grammophon" height="100" style="margin-left:50%">
    </div>
<form name="selector">
    
<ul class="form-style-1">
    <fieldset>
    <li><label>Search by Asset Number : </label><input type="text" list="xmls" name="select_xml" id="select_xml" /></li>
    <li><input type="button" name="Submit" value="Load XML Data" onClick="dataload(select_xml.value)">
    </li>
        </fieldset>
    </ul>
        
    </form>
    
    <form name="cablelabs" action="DGCLWxml.php" method="post" onsubmit="return validateForm()">
    <ul class="form-style-1">
                <fieldset>
            <legend>Album Info</legend>
        <li><label>Asset ID: </label><input type="text" class="readonly" id="assetID" name="asset_ID" readonly="readonly" /></li>
        <li><label>Title : </label><input type="text" id="assetTitle" name="asset_Title"/></li>
        <li><label>Genre : </label><input type="text" id="assetGenre" name="asset_Genre"/></li>
        <li><label>Cover Art : </label><input type="text" class="readonly" id="coverart" name="cover_Art" readonly="readonly" /></li>
        </fieldset>
        <fieldset id="dataform"><legend>Track Info</legend>
        <li id="last-item"><input type="submit" value="Save Translation"></li>
            </fieldset>
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
      
      var resourcelist = xmlDoc.getElementsByTagName("ResourceList")[0];
      var tracklist = resourcelist.getElementsByTagName("SoundRecording");
      if(tracklist.length == 0) {
          tracklist = resourcelist.getElementsByTagName("Video");
          var video = true;
      }
      
      $("#assetID").val(xmlDoc.getElementsByTagName("ICPN")[0].childNodes[0].nodeValue);
      $("#assetTitle").val(xmlDoc.getElementsByTagName("Release")[0].getElementsByTagName("ReferenceTitle")[0].getElementsByTagName("TitleText")[0].childNodes[0].nodeValue);
      $("#assetGenre").val(xmlDoc.getElementsByTagName("GenreText")[0].childNodes[0].nodeValue);
      if(xmlDoc)
      if(!video) {
          $("#coverart").val(xmlDoc.getElementsByTagName("TechnicalImageDetails")[0].getElementsByTagName("FileName")[0].childNodes[0].nodeValue);
      } else {
          $("#coverart").val("N/A");
      }
      
      
      for(var i=0; i< tracklist.length; i++){
        if(!video) {
            var titles = tracklist[i].getElementsByTagName("SoundRecordingDetailsByTerritory")[0].getElementsByTagName("Title");
            var index = 0;
            for(var j=0; j<titles.length; j++) {
                if(titles[j].getAttribute("TitleType") == "DisplayTitle") {index = j;}
            }
            var text = tracklist[i].getElementsByTagName("SoundRecordingDetailsByTerritory")[0].getElementsByTagName("TitleText")[index].childNodes[0].nodeValue;
            var trkfilename = tracklist[i].getElementsByTagName("SoundRecordingDetailsByTerritory")[0].getElementsByTagName("FileName")[0].childNodes[0].nodeValue;
        }
        if(video) {
            var titles = tracklist[i].getElementsByTagName("VideoDetailsByTerritory")[0].getElementsByTagName("Title");
            var index = 0;
            for(var j=0; j<titles.length; j++) {
                if(titles[j].getAttribute("TitleType") == "DisplayTitle") {index = j;}
            }
            var text = tracklist[i].getElementsByTagName("VideoDetailsByTerritory")[0].getElementsByTagName("TitleText")[index].childNodes[0].nodeValue;
            var trkfilename = tracklist[i].getElementsByTagName("VideoDetailsByTerritory")[0].getElementsByTagName("FileName")[0].childNodes[0].nodeValue;
        }
        var generateHere = document.getElementById("dataform");
        var frag = create("<li class='tracks'><label>Track "+(i+1)+": </label><input type='text' id='track"+(i+1)+"' name='track_"+(i+1)+"'/></li><li class='tracks'><label>File Name "+(i+1)+": </label><input class='readonly' type='text' id='file"+(i+1)+"' name='file_"+(i+1)+"' readonly='readonly' /></li>");
        generateHere.insertBefore(frag, document.getElementById("last-item"));
        $("#track"+(i+1)).val(text);
        $("#file"+(i+1)).val(trkfilename);
      }
}
    </script>
</html>