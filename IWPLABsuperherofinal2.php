<html>
<head>
  <?php


    //For insertion into csv/txt
    if(isset($_POST['form_submit']))
    {
      //for file .txt
      $file=fopen("IWPfile.txt","w") or die("Unable to open file");
      $name=$_POST["Uname"];
      $age=$_POST["age"];
      $spower=$_POST["spower"];
      $sname=$_POST["sname"];
      fwrite($file,$name);
      fwrite($file,$age);
      fwrite($file,$spower);
      fwrite($file,$sname);
      fclose($file);

      $file=fopen("IWPfile.txt","r") or die("Unable to open file");
      while(!feof($file))
      {
        echo fgets($file)."<br>";
      }
      //for csv
    $file = fopen("iwp_lab.csv", "a");
    $list = "$name,$age,$spower,$sname";
    fputcsv($file,explode(',',$list));
    fclose($file);
    //for duplicate
    $file = fopen("iwp_labdup.csv", "a");
    $list = "$name,$age,$spower,$sname";
    fputcsv($file,explode(',',$list));
    echo "Records inserted successfully into csv.";
    }

    //Foe database
    if(isset($_POST['db_submit']))
    {
      $con=mysqli_connect("localhost","Administrator","#Kalizen0123") or die("Unable to connect");
      mysqli_select_db($con,"studentlogin");
      $name=$_POST["Uname"];
      $age=$_POST["age"];
      $spower=$_POST["spower"];
      $sname=$_POST["sname"];

      //$query = "insert into formelements values()";
      $sql = "INSERT INTO formelements (name,age,superpower,superheroname) VALUES ('$name', '$age', '$spower','$sname')";
      if(mysqli_query($con, $sql)){
        echo "Records inserted successfully.";
      }
      else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
    }


    mysqli_close($con);

    }

    //For search in DB by name
    if(isset($_POST['db_search']))
    {
      $con=mysqli_connect("localhost","Administrator","#Kalizen0123") or die("Unable to connect");
      mysqli_select_db($con,"studentlogin");
      $name=$_POST["Uname"];
      $age=$_POST["age"];
      $spower=$_POST["spower"];
      $sname=$_POST["sname"];

      //$query = "insert into formelements values()";
      $sql = "select * from formelements where name='$name'";
      $result = $con->query($sql);
      //$query_run=mysqli_query($con,$sql);
      //if(mysqli_num_rows($query_run)>0){
      //if($query_run)
      if ($result->num_rows > 0){
        echo "Present in DB";
        echo "<table><tr><th>Name  </th><th>Age  </th><th>Superpower  </th><th>Superhero Name  </th></tr>";
        while($row = $result->fetch_assoc()) {
          echo "<tr><td>".$row["name"]."</td><td>".$row["age"]."</td><td>".$row["superpower"]."</td><td>".$row["superheroname"]."</td></tr>";
        }
        echo "</table>";
      }
      else{
    echo "Not present in DB";}
    mysqli_close($con);

    }


  //For search in csv by name
  if(isset($_POST['form_search']))
  {
    $name=$_POST["Uname"];
    $age=$_POST["age"];
    $spower=$_POST["spower"];
    $sname=$_POST["sname"];

  if (($handle = fopen("iwp_lab.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if($data[0] == $name)
        {
      echo "Present in CSV File: ";
            echo "<table><tr><th>Name  </th><th>Age  </th><th>Superpower  </th><th>Superhero Name  </th></tr>";
            echo "<tr><td>".$data[0]."</td><td>".$data[1]."</td><td>".$data[2]."</td><td>".$data[3]."</td></tr>";
            echo "</table>";

        }
      }
      fclose($handle);
  }
  }

  //Deletion in database
  if(isset($_POST['db_delete']))
  {
  	$name=$_POST['Uname'];
    $con=mysqli_connect("localhost","Administrator","#Kalizen0123") or die("Unable to connect");
    mysqli_select_db($con,"studentlogin");
  	$query = "delete from formelements where name='$name'";
  	if(mysqli_query($con, $query))
  	{
  		echo "Deleted Sucessfully from Database";
  	}
    else{
      echo "Not found in Database'";
  	}
    }

  //Deletion in csv
  if(isset($_POST['form_delete']))
  {
      $name=$_POST['Uname'];
    if (($handle1 = fopen("iwp_labdup.csv", "r")) !== FALSE)
    {//temp file
        if (($handle2 = fopen("iwp_lab.csv", "w")) !== FALSE)
        {//actual file
            while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE)
            {
               // Alter your data
               if($data[0]==$name)
               {
               	$data[0]="";
               	$data[2]="";
               	$data[3]="";
               	$data[4]="";
               	$data[1]="";
               }

               // Write back to CSV format
               fputcsv($handle2, $data);
            }

            fclose($handle2);
        }
        fclose($handle1);
        echo "Successfully deleted from the CSV File";
    }
  }
  //for updation in db
  if(isset($_POST['db_update']))
  {
    $con=mysqli_connect("localhost","Administrator","#Kalizen0123") or die("Unable to connect");
    mysqli_select_db($con,"studentlogin");
    $name=$_POST["Uname"];
    $age=$_POST["age"];
    $spower=$_POST["spower"];
    $sname=$_POST["sname"];
  	$query = "update formelements set name='$name', age='$age', superpower='$spower', superheroname='$sname' where name='$name'";
  	$query_run=mysqli_query($con,$query);
  	if($query_run)
  	{
  		echo "Successfully updated in the database.";
      echo "Check updated details using 'Search in DB' button.";
  	}
    else{
      echo($query_run);
    }
  }

  //for updation in csv
  if(isset($_POST['form_update']))
  {
    $name=$_POST["Uname"];
    $age=$_POST["age"];
    $spower=$_POST["spower"];
    $sname=$_POST["sname"];
  //for updation in csv
  if (($handle1 = fopen("iwp_labdup.csv", "r")) !== FALSE)
  {//temporary file
    if (($handle2 = fopen("iwp_lab.csv", "w")) !== FALSE)
    {//actual file
        while (($data = fgetcsv($handle1, 1000, ",")) !== FALSE)
        {
           // Alter your data
           if($data[0]==$name)
           {
            $data[0]=$name;
            $data[1]=$age;
            $data[2]=$spower;
            $data[3]=$sname;
           }

           // Write back to CSV format
           fputcsv($handle2, $data);
        }

        fclose($handle2);
    }
    fclose($handle1);
    echo "Successfully updated in CSV file.";
    echo "Check updated details using 'Search in DB' button.";
  }
  }






  ?>
</head>
<body>

  <form action="IWPLABsuperherofinal2.php" method="post">
    <hr>
    Name:<input type="text" name="Uname" id="Uname"><br><br>
    Age:<input type="text" name="age" id="age"><br><br>
    Superpower:<input type="text" name="spower" id="spower"><br><br>
    Superhero Name:<input type="text" name="sname" id="sname"><br><br>
    <input type="submit" value="Generate in DB" id="db_submit" name="db_submit">
    <input type="submit" value="Generate in CSV" id="form_submit" name="form_submit">
    <input type="submit" value="Search in DB" id="db_search" name="db_search">
    <input type="submit" value="Search in CSV" id="form_search" name="form_search">
    <input type="submit" value="Delete from DB" id="db_delete" name="db_delete">
    <input type="submit" value="Delete from CSV" id="form_delete" name="form_delete">
    <input type="submit" value="Update in database" id="db_update" name="db_update">
    <input type="submit" value="Update in CSV" id="form_update" name="form_update">
    <hr>
</body>
</html>
