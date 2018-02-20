<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<?
    function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
    
    if (isset($_POST))
    {
        $asset_id = $_POST['asset_ID'];
        $title = $_POST['asset_Title'];
        $genre = $_POST['asset_Genre'];
        $cover = $_POST['cover_Art'];
        $description = $_POST['asset_Desc'];
        $type = $_POST['asset_Type'];
        
        $tracks = array();
        $files = array();
        
        foreach($_POST as $key=>$val) {
            
            if(startsWith($key,"track_")) {
                array_push($tracks,$val);
            }
            if(startsWith($key,"file_")) {
                array_push($files,$val);
            }
        }
        
        $filename = "./xmlout/".$asset_id.".xml";
        $cablelabs = fopen($filename, 'w') or die("can't open file");
        libxml_use_internal_errors(true);
    }
    
    $domtree = new DOMDocument('1.0', 'utf-8');
    $xmlRoot = $domtree->createElement("assets");
    $xmlRoot = $domtree->appendChild($xmlRoot);
    
    $asset = $domtree->createElement("asset");
    $asset = $xmlRoot->appendChild($asset);
    
    $asset->appendChild($domtree->createElement('id', $asset_id));
    
    $node_title = $domtree->createElement("title");
    $node_title = $asset->appendChild($node_title);
    
    $node_genre = $domtree->createElement("genre");
    $node_genre = $asset->appendChild($node_genre);
    
    $node_cover = $domtree->createElement("cover");
    $node_cover = $asset->appendChild($node_cover);
        
    $node_type = $domtree->createElement("type");
    $node_type = $asset->appendChild($node_type);
    
    $node_desc = $domtree->createElement("description");
    $node_desc = $asset->appendChild($node_desc);
    
    $node_tracks = $domtree->createElement("tracks");
    $node_tracks = $asset->appendChild($node_tracks);
    
    foreach($tracks as $key=>$val) {
        
        $node_track = $domtree->createElement("track");
        $node_track = $node_tracks->appendChild($node_track);
        $node_track->setAttribute("TrackNumber", $key+1);
        
        $node_track_title = $domtree->createElement("title");
        $node_track_title = $node_track->appendChild($node_track_title);
        $node_track_title->appendChild($domtree->createCDATASection($tracks[$key]));
        
        $node_track_filename = $domtree->createElement("file");
        $node_track_filename = $node_track->appendChild($node_track_filename);
        $node_track_filename->appendChild($domtree->createTextNode($files[$key]));
    }
    
    $node_title->appendChild($domtree->createCDATASection($title));
    $node_genre->appendChild($domtree->createCDATASection($genre));
    $node_cover->appendChild($domtree->createTextNode($cover));
    $node_type->appendChild($domtree->createCDATASection($type));
    $node_desc->appendChild($domtree->createCDATASection($description));
    
    $domtree->save($filename);
?>
  
    <div>
    <img src="LogoDG.jpg" alt="Deutsche Grammophon" height="100" style="margin-left:50%">
    </div>
    
    <ul class="form-style-1">
        <fieldset>
            <legend>Translation saved</legend>
        <form action="DGCLWform.php">
        <li><input type="submit" value="Do another Translation"></li>
        </form>
        <form action="<? echo $filename; ?>" target="_blank">
        <li><input type="submit" value="View Translation"></li>
            </form>
            </fieldset>
    </ul>
        </body>
</html>