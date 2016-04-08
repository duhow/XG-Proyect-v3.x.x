<?php
/**
 * Noobs Protection Library
 *
 * PHP Version 5.5+
 *
 * @category Library
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */

namespace application\libraries;

use application\core\XGPCore;

/**
 * NoobsProtectionLib Class
 *
 * @category Classes
 * @package  Application
 * @author   XG Proyect Team
 * @license  http://www.xgproyect.org XG Proyect
 * @link     http://www.xgproyect.org
 * @version  3.0.0
 */
class NoobsProtectionLib extends XGPCore
{
    private $protection;
    private $protectiontime;
    private $protectionmulti;

    /**
     * __construct()
     */
    public function __construct()
    {
        $this->protection       = FunctionsLib::readConfig('noobprotection');
        $this->protectiontime   = FunctionsLib::readConfig('noobprotectiontime');
        $this->protectionmulti  = FunctionsLib::readConfig('noobprotectionmulti');
    }

    /**
     * isWeak
     *
     * @param int $current_points Current points
     * @param int $other_points   Other points
     *
     * return boolean
     */
    public function isWeak($current_points, $other_points)
    {
        if ($this->protection) {
            
            if ($this->protectionmulti == 0) {
                $this->protectionmulti = 1;
            }
            
            if ($current_points > $other_points * $this->protectionmulti) {
                
                if ($other_points > $this->protectiontime && $this->protectiontime > 0) {
                    
                    return false;
                }

                return true;
            }
        }
        
        return false;
    }

    /**
     * isStrong
     *
     * @param int $current_points Current points
     * @param int $other_points   Other points
     *
     * return boolean
     */
    public function isStrong($current_points, $other_points)
    {        
        if ($this->protection) {
        
            if ($this->protectionmulti == 0) {
                $this->protectionmulti = 1;
            }
            
            if ($current_points * $this->protectionmulti  < $other_points) {

                if ($current_points > $this->protectiontime && $this->protectiontime > 0) {

                    return false;
                }

                return true;
            }
        }
        
        return false;
    }

    /**
     * returnPoints
     *
     * @param int $current_user_id Current user id
     * @param int $other_user_id   Other user id
     *
     * return int
     */
    public function returnPoints($current_user_id, $other_user_id)
    {
        $user_points    = parent::$db->queryFetch(
            "SELECT
            (SELECT user_statistic_total_points
                    FROM " . USERS_STATISTICS . "
                            WHERE `user_statistic_user_id` = ". $current_user_id ."
                            ) AS user_points,
            (SELECT user_statistic_total_points
                    FROM " . USERS_STATISTICS . "
                            WHERE `user_statistic_user_id` = ". $other_user_id ."
                            ) AS target_points"
        );
        return $user_points;
    }
}

/* end of NoobsProtectionLib.php */