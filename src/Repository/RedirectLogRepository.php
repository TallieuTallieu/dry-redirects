<?php

namespace Tnt\Redirects\Repository;

use dry\db\Connection;
use dry\Debug;

class RedirectLogRepository 
{
  /**
   * Returns a count of all redirect logs
   * @return int
   */
  public static function count() {
    try {
      $count = Connection::get()->query('SELECT COUNT(*) as count FROM redirect_log')->toArray()['count'];
      return $count;
    } catch (\Exception $e) {
      Debug::log('Could not count redirect_log', $e);
      return 0;
    }
  }

  /**
  * Deletes all but the last $count redirect logs
  * @param int $count
  * @return bool
  */
  public static function deleteAllBut($count = 20000) {
    try {
      Connection::get()->query('DELETE FROM redirect_log WHERE id NOT IN (SELECT id FROM redirect_log ORDER BY id DESC LIMIT ' . $count . ')');
      return true;
    } catch (\Exception $e) {
      Debug::log('Could not clean up redirect_log',$e);
      return false;
    }
  }
}
