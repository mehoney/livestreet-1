<?
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
 * ACL(Access Control List)
 * Модуль для разруливания ограничений по карме/рейтингу юзера
 *
 */
class ACL extends Module {

	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {		
		
	}
		
	/**
	 * Проверяет может ли пользователь создавать блоги
	 *
	 * @param Entity_User $oUser
	 * @return bool
	 */
	public function CanCreateBlog(UserEntity_User $oUser) {
		if ($oUser->getRating()>=ACL_CAN_CREATE_BLOG) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет может ли пользователь создавать топики в определенном блоге
	 *
	 * @param Entity_User $oUser
	 * @param Entity_Blog $oBlog
	 * @return bool
	 */
	public function CanAddTopic(UserEntity_User $oUser, BlogEntity_Blog $oBlog) {
		/**
		 * Если юзер является создателем блога то разрешаем ему постить
		 */
		if ($oUser->getId()==$oBlog->getOwnerId()) {
			return true;
		}
		/**
		 * Если рейтинг юзера больше либо равен порогу постинга в блоге то разрешаем постинг
		 */
		if ($oUser->getRating()>=$oBlog->getLimitRatingTopic()) {
			return true;
		}
		return false;
	}

	/**
	 * Проверяет может ли пользователь создавать комментарии
	 *
	 * @param Entity_User $oUser
	 * @return bool
	 */
	public function CanPostComment(UserEntity_User $oUser) {
		if ($oUser->getRating()>=ACL_CAN_POST_COMMENT) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет может ли пользователь создавать комментарии по времени(например ограничение максимум 1 коммент в 5 минут)
	 *
	 * @param Entity_User $oUser
	 * @return bool
	 */
	public function CanPostCommentTime(UserEntity_User $oUser) {
		return true;
	}
	
	/**
	 * Проверяет может ли пользователь создавать комментарии используя HTML
	 *
	 * @param Entity_User $oUser
	 * @return bool
	 */
	public function CanUseHtmlInComment(UserEntity_User $oUser) {
		return true;
	}
	
	/**
	 * Проверяет может ли пользователь голосовать за конкретный комментарий
	 *
	 * @param Entity_User $oUser
	 * @param Entity_TopicComment $oComment
	 * @return bool
	 */
	public function CanVoteComment(UserEntity_User $oUser, CommentEntity_TopicComment $oComment) {
		if ($oUser->getRating()>=ACL_CAN_VOTE_COMMENT) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет может ли пользователь голосовать за конкретный блог
	 *
	 * @param Entity_User $oUser
	 * @param Entity_Blog $oBlog
	 * @return bool
	 */
	public function CanVoteBlog(UserEntity_User $oUser, BlogEntity_Blog $oBlog) {
		if ($oUser->getRating()>=ACL_CAN_VOTE_BLOG) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет может ли пользователь голосовать за конкретный топик
	 *
	 * @param Entity_User $oUser
	 * @param Entity_Topic $oTopic
	 * @return bool
	 */
	public function CanVoteTopic(UserEntity_User $oUser, TopicEntity_Topic $oTopic) {
		if ($oUser->getRating()>=ACL_CAN_VOTE_TOPIC) {
			return true;
		}
		return false;
	}
	
	/**
	 * Проверяет может ли пользователь голосовать за конкретного пользователя
	 *
	 * @param Entity_User $oUser
	 * @param Entity_User $oUserTarget
	 * @return bool
	 */
	public function CanVoteUser(UserEntity_User $oUser, UserEntity_User $oUserTarget) {
		if ($oUser->getRating()>=ACL_CAN_VOTE_USER) {
			return true;
		}
		return false;
	}
}
?>