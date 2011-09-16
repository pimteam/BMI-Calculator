<?php
/* This calculator is your for free by Calendarscripts.info. You have no obligations for anything - you can modify, redistribute, sell it or whatever you want to do.
We will appreciate if you don't remove the link at the bottom, but that's not required. */

/* Feel free to modify the CSS and the texts below. - no problem at all. Just don't touch the PHP code or the specual codes which are surrounded with %% unless you know what you are doing. */
session_start();

$firsttext="Your Body Mass Index (BMI) is %%BMI%%. This means your weight is within the %%BMIMSG%% range.";

$normaltext="You seem to keep your weight normal. Well done!";

$lowertext="Your current BMI is lower than the recommended range of <strong>18.5</strong> to <strong>24.9</strong>. <br>To be within the right range for your height, you should weigh between <strong>%%LOWERLIMIT%% lbs</strong> / <strong>%%LOWERLIMITKG%% kg</strong> and <strong>%%UPPERLIMIT%% lbs</strong> / <strong>%%UPPERLIMITKG%% kg</strong>";

$uppertext="Your current BMI is greater than the recommended range of <strong>18.5</strong> to <strong>24.9</strong>. <br>To be within the right range for your height, you should weigh between <strong>%%LOWERLIMIT%% lbs</strong> / <strong>%%LOWERLIMITKG%% kg</strong> and <strong>%%UPPERLIMIT%% lbs</strong> / <strong>%%UPPERLIMITKG%% kg</strong>";
?>
<style type="text/css">
.calculator_div
{
	font-size:11px;
	font-family:verdana, arial, sans-serif;
	border:2pt solid #4444FF;
	padding:25px;
    margin:auto;
    width:300px;
}

.calculator_div label
{
    display:block;
    float:left;
    width:100px;    
}
</style>
<?php
if(!empty($_POST['calculator_ok']))
{
    // set vars in session
    foreach($_POST as $key=>$var)
    {
        $_SESSION['bmi_calc_'.$key]=$var;
    }

    if($_POST['system']=='english')
    {
        $height=$_POST['height_ft_en']*12+$_POST['height_in_en'];
        $bmi=($_POST['weight_en']*703) / ($height*$height);    
    }
    else
    {
        $height=$_POST['height_met']/100;
        $bmi=$_POST['weight_met'] / round(($height*$height),2);
    }    
    
	$bmi=number_format($bmi,1,".","");
	
	// prepare message for the user
	if($bmi<=18.5)
	{
		$bmimsg="Underweight";
	}
	
	if($bmi>18.5 and $bmi<=24.9)
	{
		$bmimsg="Normal";	
	}
	
	if($bmi>=25 and $bmi<=29.9)
	{
		$bmimsg="Overweight";			
	}
	
	if($bmi>=30)
	{
		$bmimsg="Obese";		
	}
	
	// what is the weight range?
	if($bmimsg!='Normal')
	{
        if($_POST['system']=='english')
        {            
            $lowerlimit=number_format(( 18.5 * ($height*$height) ) / 703);
    		$lowerlimitkg=number_format($lowerlimit*0.453,1,".","");
    		
    		$upperlimit=number_format(( 24.9 * ($height*$height) ) / 703);
    		$upperlimitkg=number_format($upperlimit*0.453,1,".","");    
        }
		else
        {
            $lowerlimit=number_format( 18.5 * ($height*$height) * 2.204 );
    		$lowerlimitkg=number_format(18.5 * ($height*$height),1,".","");
    		
    		$upperlimit=number_format( 24.9 * ($height*$height) * 2.204 );
    		$upperlimitkg=number_format(24.9 * ($height*$height),1,".","");    
        }
	}
	
	//prepare texts
	$firsttext=str_replace("%%BMI%%",$bmi,$firsttext);
	$firsttext=str_replace("%%BMIMSG%%",$bmimsg,$firsttext);
	$lowertext=str_replace("%%LOWERLIMIT%%",$lowerlimit,$lowertext);
	$lowertext=str_replace("%%LOWERLIMITKG%%",$lowerlimitkg,$lowertext);
	$lowertext=str_replace("%%UPPERLIMIT%%",$upperlimit,$lowertext);
	$lowertext=str_replace("%%UPPERLIMITKG%%",$upperlimitkg,$lowertext);
	$uppertext=str_replace("%%LOWERLIMIT%%",$lowerlimit,$uppertext);
	$uppertext=str_replace("%%LOWERLIMITKG%%",$lowerlimitkg,$uppertext);
	$uppertext=str_replace("%%UPPERLIMIT%%",$upperlimit,$uppertext);
	$uppertext=str_replace("%%UPPERLIMITKG%%",$upperlimitkg,$uppertext);		
}
?>
	<form method="post">
	<div class="calculator_div">
        <div><input type="radio" value="english" name="system" <?php if($_SESSION['bmi_calc_system']=="" or $_SESSION['bmi_calc_system']=='english') echo "checked='true'";?> onclick="changeSystem('english');"> English
        &nbsp;
        <input type="radio" value="metric" name="system" <?php if($_SESSION['bmi_calc_system']!='' and $_SESSION['bmi_calc_system']=='metric') echo "checked='true'";?> onclick="changeSystem('metric');"> Metric</div>
	    <div><label>Your Weight:</label>
            <span id="englishWeight" style="display:<?php echo ($_SESSION['bmi_calc_system']=='' or $_SESSION['bmi_calc_system']=='english')?'block':'none'?>;"><input type="text" name="weight_en" size="6" value="<?php echo !empty($_SESSION['bmi_calc_weight_en'])?$_SESSION['bmi_calc_weight_en']:""?>"> lbs</span>
            <span id="metricWeight" style="display:<?php echo (($_SESSION['bmi_calc_system']=="" or $_SESSION['bmi_calc_system']=='english'))?'none':'block'?>;"><input type="text" name="weight_met" size="6" value="<?php echo !empty($_SESSION['bmi_calc_weight_met'])?$_SESSION['bmi_calc_weight_met']:""?>"> kg</span>
        </div>	 
    	<div><label>Your Height:</label>
            <span id="englishHeight" style="display:<?php echo (($_SESSION['bmi_calc_system']=='' or $_SESSION['bmi_calc_system']=='english'))?'block':'none'?>;"><input type="text" size="6" name="height_ft_en" value="<?php echo !empty($_SESSION['bmi_calc_height_ft_en'])?$_SESSION['bmi_calc_height_ft_en']:""?>"> ft
            &nbsp; <input type="text" size="6" name="height_in_en" value="<?php echo ($_SESSION['bmi_calc_height_in_en']!='')?$_SESSION['bmi_calc_height_in_en']:""?>"> in</span>
            <span id="metricHeight" style="display:<?php echo ($_SESSION['bmi_calc_system']=='' or $_SESSION['bmi_calc_system']=='english')?'none':'block'?>;">
            <input type="text" name="height_met" size="6" value="<?php echo ($_SESSION['bmi_calc_height_met']!='')?$_SESSION['bmi_calc_height_met']:""?>"> cm
            </span>
        </div>
    	<div align="center">
        	<input type="hidden" name="calculator_ok" value="ok">
        	<input type="submit" value="Are You Overweight?">
    	</div>
    	<div align="center"><a href="http://calendarscripts.info/bmr-calculator.html">BMR calculator</a></div>
    </div>    
	</form>	

<?php if(!empty($_POST['calculator_ok'])):?>
<div class="calculator_table">
	<p><?=$firsttext?></p>
	<?php	
	switch($bmimsg)
    {	
       case 'Normal':
     		// you can echo here if you want for normal weight people
       break;
       
       case 'Underweight':       		
       		echo $lowertext;
       break;
                                         	
       default:
       		echo $uppertext;
        break;                              
      }
      ?>
      
      <p align="center"><a href="http://<?=$_SERVER['HTTP_HOST'];?><?=$_SERVER['REQUEST_URI']?>">Calculate again</a></p>
</div>
<?php endif;?>


<script type="text/javascript">
function changeSystem(s)
{
    if(s=='english')
    {
        document.getElementById('englishWeight').style.display='block';
        document.getElementById('englishHeight').style.display='block';
        document.getElementById('metricWeight').style.display='none';
        document.getElementById('metricHeight').style.display='none';
    }
    else
    {
        document.getElementById('englishWeight').style.display='none';
        document.getElementById('englishHeight').style.display='none';
        document.getElementById('metricWeight').style.display='block';
        document.getElementById('metricHeight').style.display='block';
    }
}
</script>