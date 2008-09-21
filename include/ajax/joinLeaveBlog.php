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
 * Подлючение/отключение от блога
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$sType=@$_REQUEST['type'];
$idBlog=@$_REQUEST['idBlog'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$sState='';
$iCountUser=0;
if ($oEngine->User_IsAuthorization()) {
	if (in_array($sType,array('join','leave'))) {
		if ($oBlog=$oEngine->Blog_GetBlogById($idBlog)) {
			/**
			 * Как только заработают другие виды блогов(кроме open) тут нужно внести коррективы, чтоб можно было покинуть блог по приглашениям
			 */
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			if ($oBlog->getType()=='open') {
				$oBlogUser=$oEngine->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$oUserCurrent->getId());				
				if (!$oBlogUser and $sType=='join') {
					if ($oBlog->getOwnerId()!=$oUserCurrent->getId()) {
						/**
					 	* Присоединяем юзера к блогу
					 	*/
						$oBlogUserNew=new BlogEntity_BlogUser();
						$oBlogUserNew->setBlogId($oBlog->getId());
						$oBlogUserNew->setUserId($oUserCurrent->getId());
						if ($oEngine->Blog_AddRelationBlogUser($oBlogUserNew)) {
							$bStateError=false;
							$sMsgTitle='Поздравляем!';
							$sMsg='Вы вступили в блог';
							$sState='join';
							/**
							 * Увеличиваем число читателей блога
							 */
							$oBlog->setCountUser($oBlog->getCountUser()+1);
							$oEngine->Blog_UpdateBlog($oBlog);
							$iCountUser=$oBlog->getCountUser();
						} else {
							$sMsgTitle='Ошибка!';
							$sMsg='Внутреняя ошибка, попробуйте позже';
						}
					} else {
						$sMsgTitle='Внимание!';
						$sMsg='Зачем вы хотите вступить в этот блог? Вы и так его хозяин!';
					}
				}
				if (!$oBlogUser and $sType=='leave') {
					$sMsgTitle='Ошибка!';
					$sMsg='Вы точно когда то состояли в этом блоге?';
				}
				if ($oBlogUser and $sType=='join') {
					$sMsgTitle='Ошибка!';
					$sMsg='Вы уже состоите в этом блоге';
				}
				if ($oBlogUser and $sType=='leave') {
					/**
					 * Покидаем блог
					 */					
					if ($oEngine->Blog_DeleteRelationBlogUser($oBlogUser)) {
						$bStateError=false;
						$sMsgTitle='Внимание!';
						$sMsg='Вы покинули в блог';
						$sState='leave';
						/**
						 * Уменьшаем число читателей блога
						 */
						$oBlog->setCountUser($oBlog->getCountUser()-1);
						$oEngine->Blog_UpdateBlog($oBlog);
						$iCountUser=$oBlog->getCountUser();
					} else {
						$sMsgTitle='Ошибка!';
						$sMsg='Внутреняя ошибка, попробуйте позже';
					}
				}				
			} else {
				$sMsgTitle='Ошибка!';
				$sMsg='Присоедениться к этому блогу можно только по приглашению!';
			}
		} else {
			$sMsgTitle='Ошибка!';
			$sMsg='Блог не найден!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Что вы пытаетесь сделать с этим блогом?!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для подключения/отключения от блога необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sState"   => $sState,
"iCountUser" => $iCountUser,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>