<?php

// crypt function
function crypt_apr1_md5($a){$b='';$c=substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"),0,8);$d=strlen($a);$e=$a.'$apr1$'.$c;$f=pack("H32",md5($a.$c.$a));for($g=$d;$g>0;$g-=16){$e.=substr($f,0,min(16,$g));}for($g=$d;$g>0;$g>>=1){$e.=($g&1)?chr(0):$a{0};}$f=pack("H32",md5($e));for($g=0;$g<1000;$g++){$h=($g&1)?$a:$f;if($g%3)$h.=$c;if($g%7)$h.=$a;$h.=($g&1)?$f:$a;$f=pack("H32",md5($h));}for($g=0;$g<5;$g++){$l=$g+6;$m=$g+12;if($m==16)$m=5;$b=$f[$g].$f[$l].$f[$m].$b;}$b=chr(0).chr(0).$f[11].$b;$b=strtr(strrev(substr(base64_encode($b),2)),"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/","./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");return"$"."apr1"."$".$c."$".$b;}

// template for htaccess
$htaccess = <<<EOT
AuthType Basic
AuthName "Auth"
AuthUserFile #AuthUserFile#
Require valid-user
EOT;

if ( isset($_POST['submit']) && $_POST['username'] && $_POST['password'] ) {

	$htaccess = str_replace('#AuthUserFile#', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'.htpasswd', $htaccess);
	file_put_contents('.htaccess', $htaccess);

	$htpasswd = $_POST['username'].':'.crypt_apr1_md5($_POST['password']);
	file_put_contents('.htpasswd', $htpasswd);
}
 
?>
<html>
<head>
<title>htpasswdgen.php</title>
<style type="text/css">
h1 {font-family: Papyrus, fantasy; text-align: center;}
form {;max-width: 400px;margin: 0 auto;margin-top: 10%;}
.row > *{float: left;width: 49%;display: inline-block;}
.row > *:last-child {margin-left: 2%;}
input {text-align: center;font-size: 20px;margin: 5px 0;}
input[type="submit"] {width: 100%;display: block;}
</style>
</head>
<body>
<form method="post">
<h1>htpasswdgen</h1>
<div class="row">
	<input type="text" name="username" placeholder="username" autocomplete="off">
	<input type="text" name="password" placeholder="password" autocomplete="off">
</div>
<input type="submit" name="submit" value="Generate">
</form>
</body>
</html>