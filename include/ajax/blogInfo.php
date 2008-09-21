<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Выводит информацию о блоге(description)
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$sBlogId=@$_REQUEST['idBlog'];
$bStateError=true;
$sText='';
$oBlog=null;
if ($sBlogId==0) {
	if ($oEngine->User_IsAuthorization()) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		$oBlog=$oEngine->Blog_GetPersonalBlogByUser($oUserCurrent);
	}	
} else {
	$oBlog=$oEngine->Blog_GetBlogById($sBlogId);
}

if ($oBlog) {
	$bStateError=false;
	$sText=$oBlog->getDescription();
} 


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sText,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>