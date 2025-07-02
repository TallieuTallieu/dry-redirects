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
        $countQuery = Connection::get()->query('SELECT COUNT(*) as count FROM redirects_redirect_log');

        if (empty($countQuery)) {
            return 0;
        }

        return $countQuery->current()['count'];
      return $count;
    } catch (\Exception $e) {
      Debug::log('Could not count redirect_log', $e);
      return 0;
    }
  }

  /**
  * Deletes all but the last $count redirect logs
  * Using a two-step approach to avoid the MariaDB limitation with LIMIT in subqueries
  * @param int $count
  * @return bool
  */
  public static function deleteAllBut($count = 20000) {
    try {
      $result = Connection::get()->query('SELECT id FROM redirects_redirect_log ORDER BY id DESC LIMIT 1 OFFSET ' . ($count - 1));

      if (!$result->valid()) {
        return true;
      }

      $cutoffId = $result->current()['redirects_redirect_log.id'];

      Connection::get()->query('DELETE FROM redirects_redirect_log WHERE id < ' . $cutoffId);
      return true;
    } catch (\Exception $e) {
      Debug::log('Could not clean up redirect_log', $e);
      return false;
    }
  }
}
