<?php
/*---------------------------------------------------------------------------
 * @Project: Alto CMS
 * @Project URI: http://altocms.com
 * @Description: Advanced Community Engine
 * @Copyright: Alto CMS Team
 * @License: GNU GPL v2 & MIT
 *----------------------------------------------------------------------------
 * Based on
 *   LiveStreet Engine Social Networking by Mzhelskiy Maxim
 *   Site: www.livestreet.ru
 *   E-mail: rus.engine@gmail.com
 *----------------------------------------------------------------------------
 */

/**
 * Маппер для работы с БД
 *
 * @package modules.vote
 * @since   1.0
 */
class ModuleVote_MapperVote extends Mapper {
    /**
     * Добавляет голосование
     *
     * @param ModuleVote_EntityVote $oVote    Объект голосования
     *
     * @return bool
     */
    public function AddVote(ModuleVote_EntityVote $oVote) {
        $sql = "INSERT INTO " . Config::Get('db.table.vote') . "
			(target_id,
			target_type,
			user_voter_id,
			vote_direction,
			vote_value,
			vote_date,
			vote_ip
			)
			VALUES(?d, ?, ?d, ?d, ?f, ?, ?)
		";
        $xResult = $this->oDb->query(
            $sql, $oVote->getTargetId(), $oVote->getTargetType(), $oVote->getVoterId(), $oVote->getDirection(),
            $oVote->getValue(), $oVote->getDate(), $oVote->getIp()
        );
        return $xResult !== false;
    }

    /**
     * Получить список голосований по списку айдишников
     *
     * @param array  $aArrayId       Список ID владельцев
     * @param string $sTargetType    Тип владельца
     * @param int    $sUserId        ID пользователя
     *
     * @return array
     */
    public function GetVoteByArray($aArrayId, $sTargetType, $sUserId) {
        if (!is_array($aArrayId) || count($aArrayId) == 0) {
            return array();
        }
        $sql = "SELECT
					*
				FROM 
					" . Config::Get('db.table.vote') . "
				WHERE
					target_id IN(?a)
					AND
					target_type = ? 
					AND
					user_voter_id = ?d ";
        $aVotes = array();
        if ($aRows = $this->oDb->select($sql, $aArrayId, $sTargetType, $sUserId)) {
            foreach ($aRows as $aRow) {
                $aVotes[] = Engine::GetEntity('Vote', $aRow);
            }
        }
        return $aVotes;
    }

    /**
     * Удаляет голосование из базы по списку идентификаторов таргета
     *
     * @param   array|int   $aTargetsId     Список ID владельцев
     * @param   string      $sTargetType    Тип владельца
     *
     * @return  bool
     */
    public function DeleteVoteByTarget($aTargetsId, $sTargetType) {
        $aTargetsId = $this->_arrayId($aTargetsId);
        $sql = "
			DELETE FROM " . Config::Get('db.table.vote') . "
			WHERE
				target_id IN(?a)
				AND
				target_type = ?
		";
        return ($this->oDb->query($sql, $aTargetsId, $sTargetType) !== false);
    }

    public function Update($oVote) {
        $sql = "UPDATE " . Config::Get('db.table.vote') . "
                    SET vote_direction=?d, vote_value=?f, vote_date=?
                    WHERE target_id=?d AND target_type=? AND user_voter_id=?d
        ";
        $bResult = $this->oDb->query(
            $sql, $oVote->getDirection(), $oVote->getValue(), $oVote->getDate(), $oVote->getTargetId(),
            $oVote->getTargetType(), $oVote->getVoterId()
        );
        return $bResult !== false;
    }

}

// EOF