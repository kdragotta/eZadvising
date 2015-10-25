<?php
/**
 * Created by PhpStorm.
 * User: DragonFire
 * Date: 10/24/2015
 * Time: 11:52 PM
 */

/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/16/15
 * Time: 1:10 PM
 */
interface RecordsListInterface
{
    public function getRecordById($id);

    public function getRecordsByCourseId($id);

    public function getCompletedRecordsForRequirement(\obj\Requirment $req);

    public function getPendingRecordsForRequirement(\obj\Requirment $req);

    public function getAllRecords($planned = true);
}