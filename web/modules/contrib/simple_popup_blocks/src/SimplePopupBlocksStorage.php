<?php

namespace Drupal\simple_popup_blocks;

use Drupal\Core\Database\Connection;
use Drupal\Core\Messenger\Messenger;


/**
 * Class SimplePopupBlocksStorage.
 */
class SimplePopupBlocksStorage {


  /**
   * Save an entry in the simple_popup_blocks table.
   *
   * @param array $entry
   *   An array containing all the fields of the database record.
   *
   * @param Connection $database
   *   Connection object.
   *
   * @param Messenger $messenger
   *   Messenger object.
   *
   * @return int
   *   The number of updated rows.
   *
   * @throws \Exception
   *   When the database insert fails.
   *
   * @see \Drupal\Core\Database\Connection::insert()
   */
  public static function insert(array $entry, Connection $database, Messenger $messenger) {
    $return_value = NULL;
    try {
      $return_value = $database->insert('simple_popup_blocks')
        ->fields($entry)
        ->execute();
    }
    catch (\Exception $e) {
      $messenger->addError(t('db_insert failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ));
    }
    return $return_value;
  }

  /**
   * Update an entry in the database.
   *
   * @param array $entry
   *   An array containing all the fields of the item to be updated.
   *
   * @param Connection $database
   *   Connection object.
   *
   * @param Messenger $messenger
   *   Messenger object.
   *
   * @return int
   *   The number of updated rows.
   *
   * @see \Drupal\Core\Database\Connection::update()
   */
  public static function update(array $entry, Connection $database, Messenger $messenger) {
    $count = 0;
    try {
      // db_update()...->execute() returns the number of rows updated.
      $count = $database->update('simple_popup_blocks')
        ->fields($entry)
        ->condition('pid', $entry['pid'])
        ->execute();
    }
    catch (\Exception $e) {
      $messenger->addError(t('db_update failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ));
    }
    return $count;
  }

  /**
   * Load single popup from table with pid.
   */
  public static function load($pid, Connection $database) {
    $select = $database->select('simple_popup_blocks', 'pb');
    $select->fields('pb');
    $select->condition('pid', $pid);

    // Return the result in object format.
    return $select->execute()->fetchAll();
  }

  /**
   * Load single popup from table with identifier.
   */
  public static function loadCountByIdentifier($identifier, Connection $database, Messenger $messenger) {
    try {
      $select = $database->select('simple_popup_blocks', 'pb');
      $select->fields('pb', ['pid']);
      $select->condition('identifier', $identifier);
      // Return the result in object format.
      // countQuery()->execute()->fetchField();//
      return $select->execute()->fetchAll();
    }
    catch (\Exception $e) {
      $messenger->addError(t('db_select loadCountByIdentifier failed. Message = %message, query= %query', [
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      ]
      ));
    }
  }

  /**
   * Load all popup from table.
   */
  public static function loadAll(Connection $database) {
    $select = $database->select('simple_popup_blocks', 'pb');
    $select->fields('pb');

    // Return the result in object format.
    return $select->execute()->fetchAll();
  }

  /**
   * Delete popup from table.
   */
  public static function delete($pid, Connection $database) {
    $select = $database->delete('simple_popup_blocks');
    $select->condition('pid', $pid);

    // Return the result in object format.
    return $select->execute();
  }

}
