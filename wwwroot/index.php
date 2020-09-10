<html>
    <head>
        <title>Upload Json</title>
    </head>
    <body>
        <div style="width:800px; margin:0 auto;">
            <form action="process-upload.php" method="post" enctype="multipart/form-data">
              json output from python script:<br>
              <textarea name="jsonText" rows="30" cols="80"></textarea><br />
              <input type="submit" value="Submit" name="submit">
            </form>
        </div>
    </body>
</html>