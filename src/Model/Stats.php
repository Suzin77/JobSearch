<?php declare(strict_types= 1);

namespace App\Model;

use App\Entity\Job;
use App\Entity\Skills;
use \Doctrine\Common\Util\Debug;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManager;

class Stats
{
    public function __construct($db){
        $this->db = $db;
    }

    public function getDistinctNumberOf(string $table, string $field)
    {
        $db = $this->db;
        $sql = "SELECT COUNT(DISTINCT($field)) AS $field FROM $table ORDER BY $field ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        //dump($rows);
        return $rows[0][$field];
    }

    public function getSkills()
    {
        $db = $this->db;
        $sql = "SELECT DISTINCT(skill_name) FROM skills ORDER BY skill_name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        //dump($rows);
        return $rows; 
    }

    public function getJob()
    {
        $db = $this->db;
        $sql = "SELECT * FROM job LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        dump($rows);
        return $rows; 
    }
}

// SELECT DATE(published_at) publish,DAYOFWEEK(NOW()),COUNT(DISTINCT id) total_per_day FROM `job` GROUP BY DATE(published_at)
// num of jobs with skill given : SELECT * FROM job_skills WHERE skills_id = any(SELECT id FROM skills WHERE skill_name = 'PHP')