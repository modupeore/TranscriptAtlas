<?php
  session_start();
  require_once('atlas_header.php'); //Display heads
  require_once('atlas_fns.php'); //All the routines
  d_kaks_header();
  $phpscript = "kaksratios.php";
  $json = file_get_contents('kaks/rossillinoisBM.json');
  $yummy = json_decode($json,true);
  unset($_SESSION['kaksdata']);
?>
  <table width=100%><tr><td>
	<div class="explain"><p><center>This provides a list of compiled kaks from a subset of our Ross and Illinois libraries.
  	<br>
  	<br>View kaks ratios based on a specific gene of interest.</center></p></div>
  </td><td align="right"><br>
      <table class="summary">
        <tr>
	 <th class="summary">dN/dS ratio</th>
	 <th class="summary">Meaning</th>
	</tr>
        <tr>
	 <td class="summary">>1</td>
	 <td class="summary">Positive selection (Adaptive evolution)</td>
	</tr>
        <tr>
	 <td class="summary"><1</td>
	 <td class="summary">Negative selection (Conservation)</td>
	</tr>
        <tr>
	 <td class="summary">=1</td>
	 <td class="summary">Neutral selection (Drift)</td>
	</tr>
        <tr>
         <td class="summary">NA / 50</td>
         <td class="summary">No synonymous SNPs found</td>
        </tr>
        <tr>
         <td class="summary">0</td>
         <td class="summary">No nonsynonymous SNPs found</td>
        </tr>
        <tr>
         <td class="summary">nan</td>
         <td class="summary">No gene expression</td>
        </tr>
      </table>


  </td></tr></table>
<?php

  echo '<form id="query" class="top-border" action="'.$phpscript.'" method="post">'; 
?>

    <p class="pages"><span>Specify your gene name: </span>
      <?php

        if (!empty($_POST['reveal']) && !empty($_POST['skname'])) { /* GeneName */
           $_SESSION['skname'] = $_POST['skname'];
        }
  
          if (!empty($_SESSION['skname'])) {
            echo '<input type="text" name="skname" id="genename" size="50%" value="' . $_SESSION["skname"] . '"/>';
          } else {
            echo '<input type="text" name="skname" id="genename" size="50%" placeholder="Enter Gene Name(s)" />';
          }
      ?>
      </p><br>
    <center><input type="submit" name="reveal" value="View Results"></center>
  </form></div> <hr>
     <?php
  if (isset($_POST['reveal']) && !empty($_SESSION['skname'])) {
    echo '<table><tr><td align="right">';
		$explodeskname = explode(", ", rtrim($_SESSION['skname'],", "));
		echo '<table class="gened">
		<tr>
			<th class="geneds">Gene</th>
			<th class="geneds">Line</th>
			<th class="geneds">Htseq</th>
			<th class="geneds">Cufflinks</th>
		</tr>';
    foreach ($explodeskname as $theskname) {
			$sabc = $yummy[$theskname][0];
				echo '<tr>
              <td class="gened" rowspan="2">'.$theskname.'</td>
							<td class="gened"><b>Ross</b></td>
              <td class="gened">'.$sabc['Rhtseq'].'</td>
              <td class="gened">'.$sabc['Rcuff'].'</td>
            </tr>';
				echo '<tr>
              <td class="gened"><b>Illinois</b></td>
              <td class="gened">'.$sabc['Ihtseq'].'</td>
              <td class="gened">'.$sabc['Icuff'].'</td>
            </tr>'; $number++;
		}
		echo '</table><br>';

    echo '</td></tr><tr></tr><tr><td>';
    echo '<table class="gened">';
    echo '<tr>
            <th class="geneds" rowspan=2>Gene</th>
						<th></th>
						<th class="gened" colspan=5>Chromosomal Location</th>
            <th></th>
            <th class="gened" colspan=5>Ross</th>
            <th></th>
            <th class="gened" colspan=5>Illinois</th>
          </tr>';      
    echo '<tr>
            <th></th>
						<th class="geneds" colspan=2>Chr</th>
            <th class="geneds">Start</th>
            <th class="geneds">Stop</th>
            <th class="geneds">Orn</th>
            <th></th>
            <th class="geneds">MLWL</th>
            <th class="geneds">GY</th>
            <th class="geneds">YN</th>
            <th class="geneds">MYN</th>
            <th class="geneds">MA</th>
            <th></th>
            <th class="geneds">MLWL</th>
            <th class="geneds">GY</th>
            <th class="geneds">YN</th>
            <th class="geneds">MYN</th>
            <th class="geneds">MA</th>
          </tr>';
    $list = array();
		$explodeskname = explode(", ", rtrim($_SESSION['skname'],", "));
    foreach ($explodeskname as $theskname) {
			$number =  0;
			foreach ($yummy[$theskname] as $sabc) {
			$number++;
			if ($number > 1) {
				if ($number = 2){ $liner = rtrim($liner,","); $liner .= "-1\","; }
				$liner .= "\"".$theskname."-".$number."\",";
			} elseif($number == 1){
				$liner .= "\"".$theskname."\",";
			}
			print '<tr><td class="gened">'.$theskname.'</td><td></td>
						<td class="gened">'.$number.'</td>
						<td class="gened">'.$sabc['chr'].'</td>
						<td class="gened">'.$sabc['start'].
						'</td><td class="gened">'.$sabc['stop'].'</td>
						<td class="gened">'. $sabc['orn'].'</td>
						<td></td>
						<td class="gened">'.$sabc['Rmlwl'].'.</td>
						<td class="gened">'.$sabc['Rgy'].'</td>
						<td class="gened">'.$sabc['Ryn'].'</td>
						<td class="gened">'.$sabc['Rmyn'].'</td>
						<td class="gened">'.$sabc['Rma'].'</td>
						<td></td>
						<td class="gened">'.$sabc['Imlwl'].'</td>
						<td class="gened">'. $sabc['Igy'].'</td>
						<td class="gened">'.$sabc['Iyn'].'</td>
						<td class="gened">'.$sabc['Imyn'].'</td>
						<td class="gened">'.$sabc['Ima']."</td>
					</tr>";
	
				#Graph image options
				if ($sabc['Rmlwl'] == 50) {$sabc['Rmlwl'] = 0;} if ($sabc['Imlwl'] == 50) {$sabc['Imlwl'] = 0;}
				if ($sabc['Rgy'] == 50) {$sabc['Rgy'] = 0;} if ($sabc['Igy'] == 50) {$sabc['Igy'] = 0;}
				if ($sabc['Ryn'] == 50) {$sabc['Ryn'] = 0;} if ($sabc['Iyn'] == 50) {$sabc['Iyn'] = 0;}
				if ($sabc['Rmyn'] == 50) {$sabc['Rmyn'] = 0;} if ($sabc['Imyn'] == 50) {$sabc['Imyn'] = 0;}
				if ($sabc['Rma'] == 50) {$sabc['Rma'] = 0;} if ($sabc['Ima'] == 50) {$sabc['Ima'] = 0;}
				
				$Rsum = 0; $Rsum = (($sabc['Rmlwl']+$sabc['Rgy']+$sabc['Ryn']+$sabc['Rmyn']+$sabc['Rma'])/5);
				$Isum = 0; $Isum = (($sabc['Imlwl']+$sabc['Igy']+$sabc['Iyn']+$sabc['Imyn']+$sabc['Ima'])/5);
				
				$naming = $_SESSION['skname'];
				#pushing to the big array
				array_push($list,$Rsum,$Isum);
		}} $liner = rtrim($liner,",");
    exec("rm -rf OUTPUT/*png");
    echo "</table>";
    echo '</td></tr></table>';
    #Output files
    $picture = "$base_path/OUTPUT/kakspic_".$explodedate.".png";
    $output1 = "$base_path/OUTPUT/listofinput.txt";;
    $output2 = "$base_path/OUTPUT/rscript.R";
    #formating the array to be php plot friendly
    $result = count($list); 
    if ($result > 10){ $las = 2; }else { $las = 1;} //$liner = 1;
    $newlist = $list[0].",".$list[1];
    $y = 0; $z = 1;
    for ($x = 2; $x < $result; $x++) {
			$newlist .= "," .$list[$x]; 
			if($y == 1) {
		    $z++;
			  //$liner .= ",".$z;
        $y = 0;
      } else {
				$y++;
      }
    }
    $newlist .= "\n";
    file_put_contents($output1, $newlist);
    #writing the Rscript
    $rscript = 'reader <- as.numeric(read.table("OUTPUT/listofinput.txt", sep=","))'."\n"; #read file
    $rscript .= 'cnames <- c('.$liner.")\n"; #row names
    $rscript .= 'rnames <- c("Ross", "Illinois")'."\n"; #column names
    $rscript .= 'mymatrix <- matrix(reader,nrow=2,byrow=FALSE,dimnames=list(rnames,cnames))'."\n"; #convert to matrix
    $rscript .= 'png(filename="'.$picture.'", width = 1000, height = 500)'."\n"; #output images
    $rscript .= 'mar.default <- c(5,4,4,2) + 0.1'."\n".'par(mar = mar.default + c(10, 0, 0, 0))'."\n";
    $rscript .= 'barplot(rep(NA,length(mymatrix)),width=2,ylim=c(min(0,mymatrix),max(mymatrix,1.2)),axes=FALSE)'."\n"; #create empty plot
    $rscript .= 'abline(h=1, col="green",lwd=5)'."\n"; #line of dN/dS neutrality score
    $rscript .= 'barplot(mymatrix, col=c("darkblue","red"), main="'.rtrim($_SESSION['skname'],", ").'", las='.$las.'.,ylab = "dN/dS ratio", ylim=c(min(0,mymatrix),max(mymatrix,1)),legend = rownames(mymatrix), beside=TRUE, add=T)'."\n"; #bar plot 
    $rscript .= 'dev.off()'."\n"; #exit png
    file_put_contents($output2, $rscript);
    
    #Execute Rscript
    exec("Rscript $output2"); 
    exec("chmod 777 OUTPUT/*");
    exec ("rm -rf $output1 $output2");
    #return image
    echo ("<br><center><img src=\"OUTPUT/kakspic_".$explodedate.".png\"></center>");
   
  }
?>
