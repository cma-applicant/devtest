<html>
    <head>
        <title>Upload Response</title>
    </head>
    <body>
<?php
    // get json text in a format we can use
    $jsonobj =  trim($_POST['jsonText']);
    $arr = json_decode($jsonobj);
    $results = (array)$arr;
    
    // connect to db
    $con=mysqli_connect("localhost","[user]","[pass]","CMADemo");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else {
        // each upload will have a unique batch id associated with it
        $batchId = uniqid();
        
        // insert data in db
        foreach ($arr as &$artwork) {
            $insert = "INSERT INTO Artwork(BatchId, AccessionNumber, Title, Tombstone, Department) Values('" . $batchId . "', '" . $artwork->AccessionNumber . "', '" . $artwork->Title . "', '" . $artwork->Tombstone . "', '" . $artwork->Department . "')";
            $result = mysqli_query($con,$insert);
            
            foreach($artwork->Creator as &$creator){
                $insert = "INSERT INTO Creator(BatchId, Role, Description, ArtworkAccessionNumber) VALUES('" . $batchId . "', '" . $creator->Role . "', '" . $creator->Description . "', '" . $artwork->AccessionNumber . "')";
                $result = mysqli_query($con,$insert);
            }
        }
        
        echo "Upload Success! <br /><br />";
        echo "<a href=\"results.php?batchId=" . $batchId . "\">Click here</a> for results";
    }
    
    mysqli_close($con);
?>
    </body>
</html>