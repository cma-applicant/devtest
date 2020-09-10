<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/progressive-image.js/dist/progressive-image.css">
        <script src="https://cdn.jsdelivr.net/npm/progressive-image.js/dist/progressive-image.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <div style="width:650px; margin:0 auto;">
<?php 
    // create db connection
    $con=mysqli_connect("localhost","[user]","[pass]","CMADemo");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else {
        $batchId = clean_param($_GET['batchId']);
        
        // get all departments associated with uploaded batch
        $sql = "select distinct department from Artwork where BatchId='" . $batchId . "'";
        $result = mysqli_query($con,$sql);
        $depts = array();
        $deptLinks = '<a href="index.php">Home</a><br /><br />';
        while($row = mysqli_fetch_assoc($result)) {
            array_push($depts, $row['department']);
            $deptLinks .= "<a href=\"results.php?batchId=" . $batchId . "&dept=" . $row['department'] . "\">" . $row['department'] . "</a> | ";
        }
        
        // remove last spacer from link string
        $deptLinks = substr($deptLinks, 0, -2) . "<br /><br /><a href=\"results.php?batchId=" . $batchId . "&dept=all\">View All</a><br /><br />";
        
        // display department links
        echo '<div style="text-align:center">' . $deptLinks . '</div>';
        
        // get artwork with optional department
        $sql = "select distinct a.AccessionNumber, a.Title, a.Tombstone, a.Department, c.Role, c.Description from Artwork a inner join Creator c on c.ArtworkAccessionNumber=a.AccessionNumber and a.BatchId='" . $batchId . "'";
        $dept = clean_param($_GET['dept']);
        if (strlen($dept) != 0) {
            if ($dept != 'all') {
                $sql .= " and a.Department='" . $dept . "'";
            }
        }
        else {
            // display random department when first viewing page, so as to not display all data at once
            $random_keys=array_rand($depts,3);
            $dept = $depts[$random_keys[0]];
            $sql .= " and a.Department='" . $dept . "'";
        }
        $sql .= " order by a.AccessionNumber"; // required for loop below to prevent duplicates
        $result = mysqli_query($con,$sql);
        
        $imageDir = 'images/';              // location of full sized img
        $blurDir = 'images/blur/images/';   // location of reduced size img for lazy loading
        $suffix = '_reduced.jpg';           // file suffix after Accession Number
        $i = '';
        
        // dept header
        if ($dept != 'all') {
            echo '<br /><div style="text-align:center"><b>' . $dept . '</b></div><br />';
        }
        
        // iterate through query results, formatting display
        while($row = mysqli_fetch_assoc($result)) {
            
            $img = $imageDir . $row['AccessionNumber'] . $suffix;
            $smallImg = $blurDir .  $row['AccessionNumber'] . $suffix;
            
            // if image does not exist in directory, do not display
            if (file_exists($img)) {
                
                // since the Artwork to Creator relationship is one-to-many, only display the Artwork data once
                if($i != $row['AccessionNumber']) {
                    echo '<br /><br /><br /><br />';
                    echo '<a href="' . $img . '" class="progressive replace">';
                    echo '<img src="' . $smallImg . '" class="preview" alt="image" /></a>';
                    echo '<br />Tombstone: ' . $row['Tombstone'] . '<br />';
                    echo "<br />Department: <a href=\"results.php?batchId=" . $batchId . "&dept=" .$row['Department'] . "\">" . $row['Department'] . "</a><br />";
                    echo '<br />Accession Number: ' . $row['AccessionNumber'] . '<br />';
                }
                echo '<br />' . $row['Role'] . ': ' . $row['Description'] . '<br />';
                $i = $row['AccessionNumber'];
            }
        }
    }
    
    mysqli_close($con);
    
    function clean_param($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = str_replace('\'', '&#39;', $data);
      $data = str_replace('--', '', $data);
      return $data;
    }
?> 
        </div>
    </body>
</html>
