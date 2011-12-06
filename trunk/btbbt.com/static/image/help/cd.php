<html>
<head>
<title>HTML Form for uploading image to server</title>
<script language="JavaScript">
<!--
function setFileFileds(num){ 
 for(var i=0,str="";i<num;i++){
  str+="<input name=\"pictures[]\" type=\"file\" id=\"strFile"+i+"\"><br>";
 }
 objFiles.innerHTML=str;
}
//-->
</script></head>
<body>
<?php
//places files into same dir as form resides
if($_FILES["pictures"]){
foreach ($_FILES["pictures"]["error"] as $key => $error) {
   if ($error == UPLOAD_ERR_OK) {
       echo"$error_codes[$error]";
       move_uploaded_file(
         $_FILES["pictures"]["tmp_name"][$key], 
         $_FILES["pictures"]["name"][$key] 
       ) or die("Problems with upload");
   }
}
}
?>
<form action="" method="post" enctype="multipart/form-data" name="form1">
<table width="389" border="1">
  <tr>
    <td width="297">　</td>
    <td>　</td>
    <td width="19">　</td>
  </tr>
  <tr>
    <td valign="top" width="297"><select name="select" onChange="setFileFileds(this.value)">
                  <option value="1" selected>1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option>
                  <option value="16">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                  <option value="21">21</option>
                  <option value="22">22</option>
                  <option value="23">23</option>
                  <option value="24">24</option>
                  <option value="25">25</option>
                  <option value="26">26</option>
                  <option value="27">27</option>
                  <option value="28">28</option>
                  <option value="29">29</option>
                  <option value="30">30</option>
                  <option value="31">31</option>
                  <option value="32">32</option>
                  <option value="33">33</option>
                  <option value="34">34</option>
                  <option value="35">35</option>
                  <option value="36">37</option>
                  <option value="37">37</option>
                  <option value="38">38</option>
                  <option value="39">39</option>
                  <option value="40">40</option>
                  <option value="41">41</option>
                  <option value="42">42</option>
                  <option value="43">43</option>
                  <option value="44">44</option>
                  <option value="45">45</option>
                  <option value="46">46</option>
                  <option value="47">47</option>
                  <option value="48">48</option>
                  <option value="49">49</option>
                  <option value="50">50</option>
                  <option value="51">51</option>
                  <option value="52">52</option>
                  <option value="53">53</option>
                  <option value="54">54</option>
                  <option value="55">55</option>
                  <option value="56">56</option>
                  <option value="57">57</option>
                  <option value="58">58</option>
                  <option value="59">59</option>
                  <option value="60">60</option>
				  <option value="61">61</option>
				  <option value="62">62</option>

                </select></td>
    <td  id="objFiles"></td>
    <td width="19">　</td>
  </tr>
  <tr>
    <td width="297"><label>
      <input type="submit" name="button" id="button" value="submit">
    </label></td>
    <td>　</td>
    <td width="19">　</td>
  </tr>
</table>
</form>
<script language="JavaScript">setFileFileds(form1.select.value)</script>

</body>
</html> 
