<?php declare(strict_types= 1);

namespace App\Model;

use App\Entity\Job;
use App\Entity\Skills;
use \Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManager;

class Stats
{
    private $db;

    public const DEFAULT_SKILLS = [
        0=>['skill_name'=>"JavaScript"],
        1=>['skill_name'=>"Java"],
        2=>['skill_name'=>"Python"],
        3=>['skill_name'=>"PHP"],
        4=>['skill_name'=>"SQL"],
        5=>['skill_name'=>"C#"]
    ];
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getDataWithPopularSkills(array $skillsArray = self::DEFAULT_SKILLS, int $limit=5)
    {
        $manySkills = [];
        foreach ($skillsArray as $sk){

            $manySkills[$sk['skill_name']]= $this->getMostPopularSkillWith($sk['skill_name'],$limit+1);
        }

        return $manySkills;
    }

    public function getNumberOfRows()
    {
        $sql = "SELECT NUM_ROWS FROM information_schema.INNODB_SYS_TABLESTATS where NAME = 'jobsearch/job'";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows[0]['NUM_ROWS'];
    }

    public function getDistinctNumberOf(string $table, string $field)
    {
        $sql = "SELECT COUNT(DISTINCT($field)) AS $field FROM $table ORDER BY $field ASC";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return $rows[0][$field];
    }

    public function getSkills()
    {
        $sql = "SELECT DISTINCT(skill_name) FROM skills ORDER BY skill_name ASC";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getJob()
    {
        $sql = "SELECT * FROM job LIMIT 10";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function jobsPerDay()
    {
        $sql = "SELECT DATE(published_at) publish,COUNT(DISTINCT id) total_per_day 
                FROM `job` 
                WHERE published_at > '2019-05-25'
                GROUP BY DATE(published_at)";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getMostPopularSkills(int $num): array 
    {
        $sql = "SELECT COUNT(skill_name) AS 'ilosc', skill_name 
                FROM skills 
                INNER JOIN job_skills 
                ON skills.id = job_skills.skills_id 
                GROUP BY skill_name  
                ORDER BY `ilosc`  DESC LIMIT $num";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMostPopularSkillWith(string $skillName, int $limit)
    {
        $sql = "SELECT COUNT(skill_name) AS 'amount', skill_name 
                FROM skills 
                INNER JOIN (
                    SELECT * 
                    FROM `job_skills` 
                    WHERE job_id IN (
                        SELECT job_id 
                        FROM `skills` 
                        INNER JOIN job_skills
                        ON skills.id = job_skills.skills_id 
                        WHERE skill_name = '".$skillName."'
                        )
                    ) AS new_js 
                ON skills.id = new_js.skills_id 
                GROUP BY skill_name 
                ORDER BY `amount` 
                DESC LIMIT ".$limit.";"
        ;

        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
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

jakie skille popularne z danyj jezykiem 
SELECT COUNT(skill_name) AS 'ilosc', skill_name 
FROM skills 
INNER JOIN (
    SELECT * 
    FROM `job_skills` 
    WHERE job_id IN (
        SELECT job_id 
        FROM `skills` 
        INNER JOIN job_skills
        ON skills.id = job_skills.skills_id 
        WHERE skill_name = 'JavaScript'
        )
    ) AS new_js 
ON skills.id = new_js.skills_id 
GROUP BY skill_name 
ORDER BY `ilosc` 
DESC LIMIT 10

ilosc ofert z podzialem namiasta w danym miesiacy 
SELECT COUNT(*) AS Many, city 
FROM job 
WHERE published_at BETWEEN '2019-07-01' AND '2019-07-31' 
GROUP BY city 
ORDER BY Many DESC

most popular skills in city
SELECT COUNT(skill_name) AS 'ilosc', skill_name 
                FROM skills 
                INNER JOIN job_skills 
                ON skills.id = job_skills.skills_id
                INNER JOIN job
                ON job_skills.job_id = job.id
                WHERE city = 'krakow'
                GROUP BY skill_name  
                ORDER BY `ilosc`  DESC LIMIT 10

napopularniejsze skille wraz z poziomem zaawansowania

SELECT skill_name, skill_level, COUNT(skill_name) AS num 
FROM 
    (SELECT s.skill_name, s.skill_level 
     FROM skills AS s
     JOIN job_skills AS j
     ON s.id = j.skills_id
     WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))) 
AS result
GROUP BY result.skill_name, result.skill_level
ORDER BY num DESC

najpopularniesze skille z poziomem z zakresem dni 
SELECT s.skill_name, s.skill_level FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
JOIN job AS js
ON j.job_id = js.id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))
AND js.published_at BETWEEN '2019-08-01' AND NOW()

most freq skills in last month (with divide on skill level)

SELECT skill_name, skill_level, COUNT(skill_name) AS num FROM (SELECT s.skill_name, s.skill_level FROM skills AS s
JOIN job_skills AS j
ON s.id = j.skills_id
JOIN job AS js
ON j.job_id = js.id
WHERE s.skill_level IN(SELECT DISTINCT(s.skill_level))
AND js.published_at BETWEEN DATE_ADD(NOW(), INTERVAL -1 MONTH) AND NOW()) AS result
GROUP BY result.skill_name, result.skill_level
ORDER BY num DESC

*/