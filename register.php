<?php
   include("config.php");
   session_start();
   $database = "transcriptatlas";
   $phpscript = "login.php";
   if($_SERVER["REQUEST_METHOD"] == "POST") {
			if (!empty($_POST['username'] && $_POST['password'])) {
				// username and password sent from form 
				$firstname = mysqli_real_escape_string($db_conn,$_POST['firstname']);
				$lastname = mysqli_real_escape_string($db_conn,$_POST['lastname']);
				$inst = mysqli_real_escape_string($db_conn,$_POST['inst']);
				$myusername = mysqli_real_escape_string($db_conn,$_POST['username']);
				$mypassword = mysqli_real_escape_string($db_conn,$_POST['password']);
				$myemail = mysqli_real_escape_string($db_conn,$_POST['email']);
				$sql = "insert into user (firstname, lastname, organization,username, password, email,active) values ('$firstname', '$lastname', '$inst','$myusername', '$mypassword', '$myemail',1)";
				$result = mysqli_query($db_conn,$sql);
				if ($result) {
					$error = "Registration successful login";
					$subject = '[transcriptAtlas] -- Activate ';
					$message = "Activate $myusername";
					$retval = mail($email_to, $subject, $message);
					$error = "Registration successful ".'<input type = "button" value = " Login " onclick="window.location.href=\'login.php\'"/>';
				} else {
					$error = "Username already exists ".'<input type = "button" value = " Login " onclick="window.location.href=\'login.php\'"/>';
				}
		 } else {
				$error = "You forgot to register a username and/or password";
		 }
	 }
?>

<html>
   
   <head>
      <title>Login Page</title>
      <link rel="icon" type="image/ico" href="images/atlas_ico.png"/>
      <style type = "text/css">
         body {
            width: 100%;
	    font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }

        div.file {
           position: absolute;
           top:0;
    	   bottom: 0;
    	   left: 0;
    	   right: 0;
	   margin: auto;
        }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
						width:150px;
         }
         footer {
	    left:80%;
            clear: both;
            position: fixed;
	    bottom:0;
            z-index: 10;
            height: 3em;
            margin-top: -3em;
         }
				 label {
					padding-right:20px;
  width:70px;
  display: inline-block;
	text-align:right;
}
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFF6">
                      <table>
                                <tr>
                                        <td width=30px></td>
                                        <td align="center">
                                                <a href="index.php"><img src="images/atlas_main.png" alt="Transcriptome Atlas" ></a>
                                        </td>
                                </tr>
                        </table>        
      <div align = "center">
         <div class="file" style = "width:300px; height: 260px; background-color: #FFF; border: solid 1px #306269; ">
            <div style = "background-color:#306269; color:#FFFFFF; padding:3px;"><b>Registration</b></div>
                                
            <div style = "margin:10px">
               
               <form action = "" method = "post">
                  <label>FirstName  </label><input type = "text" name = "firstname" class = "box"/><br /><br />
							    <label>LastName  </label><input type = "text" name = "lastname" class = "box"/><br /><br />
									<label>Organization </label><input type = "text" name = "inst" class = "box"/><br /><br />
									<label>UserName<font color=red>*</font></label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password<font color=red>*</font></label><input type = "password" name = "password" class = "box" /><br/><br />
									<label>Email     </label><input type = "text" name = "email" class = "box"/><br/><br />
                  <input type = "submit" name = "register" value = " Submit "/> <br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                                        
            </div>
         </div>
      </div>
<footer align="right"><p align="right" ><font size="1">- Created by Modupe Adetunji at the University of Delaware - </font></p></footer>
</body>
</html>

