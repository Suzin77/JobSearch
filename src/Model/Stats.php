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

    public function jobsPerDay()
    {
        $db = $this->db;
        $sql = "SELECT DATE(published_at) publish,COUNT(DISTINCT id) total_per_day FROM `job` WHERE published_at > '2019-05-25'GROUP BY DATE(published_at)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    public function getMostPopularSkills(int $num): array 
    {
        $db = $this->db;
        $sql = "SELECT COUNT(skill_name) AS 'ilosc', skill_name FROM skills INNER JOIN job_skills ON skills.id = job_skills.skills_id GROUP BY skill_name  
        ORDER BY `ilosc`  DESC LIMIT $num";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

}

// SELECT DATE(published_at) publish,DAYOFWEEK(NOW()),COUNT(DISTINCT id) total_per_day FROM `job` GROUP BY DATE(published_at)
// num of jobs with skill given : SELECT * FROM job_skills WHERE skills_id = any(SELECT id FROM skills WHERE skill_name = 'PHP')
// num of specific skill SELECT COUNT(skill_name) FROM skills INNER JOIN job_skills ON skills.id = job_skills.skills_id WHERE skill_name = "JavaScript";
// SELECT COUNT(skill_name) AS 'ilosc', skill_name FROM skills INNER JOIN job_skills ON skills.id = job_skills.skills_id GROUP BY skill_name ORDER BY `ilosc` DESC LIMIT $num
/*
SELECT * FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))

SELECT s.skill_name, s.skill_level FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))

SELECT COUNT(skill_level) FROM (
SELECT s.skill_name, s.skill_level FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))) AS many WHERE 
skill_name = 'JavaScript' and skill_level = 4

SELECT result.skill_name, result.skill_level, COUNT(*) AS freq
FROM 
(SELECT s.skill_name, s.skill_level FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))) AS result
GROUP BY skill_name,skill_level

ilosc ofert tygodniowo z nr tygodnia 
select count(*), yearweek(published_at)
from job 
group by yearweek(published_at)

select count(*), yearweek(published_at,7)
from job 
group by yearweek(published_at,7)

*/