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
<?
    if (isset($_POST))
    {
        $asset_id = $_POST['asset_ID'];
        $title = $_POST['asset_Title'];
        $genre = $_POST['asset_Genre'];
        
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
    
    $node_title->appendChild($domtree->createCDATASection($title));
    $node_genre->appendChild($domtree->createCDATASection($genre));
    
    $domtree->save($filename);
?>
    <ul class="form-style-1">
    <li><label>Translation Saved</label></li>
        <form action="DGCLWform.php">
        <li><input type="submit" value="Do another Translation"></li>
        </form>
        <form action="<? echo $filename; ?>" target="_blank">
        <li><input type="submit" value="View Translation"></li>
            </form>
    </ul>
        </body>
</html>