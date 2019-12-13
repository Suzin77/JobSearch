<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\Job;
use App\Entity\Skills;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManager;

class JobOfferWriter extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settingJob($data)
    { 
        $job = new Job();
        $job->setTitle($data['title'])
            ->setCity($data['city'])
            ->setCountryCode($data['country_code'])
            ->setRemote($data['remote'])
            ->setExperienceLevel($data['experience_level'])
            ->setCompanyName($data['company_name'])
            ->setCompanyUrl($data['company_url'])
            ->setCompanyAddressText($data['address_text'])
            ->setSalaryFrom($data['salary_from'])
            ->setSalaryTo($data['salary_to'])
            ->setSalaryCurrency($data['salary_currency'])
            ->setPublishedAt($data['published_at'])
            ->setEmploymentType($data['employment_type'])
            ->setSkills($this->prepSkills($data['skills']))
            ->setLatitude($data['latitude'])
            ->setLongitude($data['longitude'])
            ->setMarkerIcon($data['marker_icon'])
            ->setPublishTime($this->prepTime($data['published_at']));

        //for some reason code below dont work here but it works in maincontroller file.
        //2019-06-11 update , now it works.
        $this->saveNewSkills($data['skills']);
        $this->skillsHandler($job, $data['skills']);
        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $job;
    }

    public function prepSkills(array $skills): string
    {
        $result = '';
        foreach($skills as $skill){
            $result = $result.$skill['name'].",";
        }
        return $result;
    }

    public function skillsHandler(Job $job , array $skills): void
    {
        foreach($skills as $skill){

            $skill_from_db = $this->entityManager->getRepository(Skills::class)->findOneBy(['skill_name'=>$skill['name'], 'skill_level'=>$skill['level']]);
            //$skill_from_db = $this->doctrine->getRepository(Skills::class)->findOneBy(['skill_name'=>$skill['name'], 'skill_level'=>$skill['level']]);
            $job->addJobSkillCollection($skill_from_db);
        }
    }

    public function saveNewSkills(array $skills): void
    {
        foreach($skills as $skill){
            $skillCheck = $this->entityManager->getRepository(Skills::class)->findOneBy(['skill_name'=>$skill['name'], 'skill_level'=>$skill['level']]);
            if($skillCheck === null){
                $skill_to_save = new Skills();
                $skill_to_save->setSkillName($skill['name']);
                $skill_to_save->setSkillLevel($skill['level']);
                $this->entityManager->persist($skill_to_save);
                $this->entityManager->flush();
            }
        }
    }

    public function dateConverter(string $date): string
    {
        $sql = "SELECT UNIX_TIMESTAMP(STR_TO_DATE($date, '%Y-%m-%dT%H:%i:%s.000Z'))";
    }

    public function prepTime(string $date): string
    {   
        date_default_timezone_set('UTC');
        $newdate = strtotime($date);
        $newdate = date('Y-m-d H:i:s',$newdate);
        return $newdate;
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;

    }

    public function test(int $id)
    {
        return $this->getDoctrine()->getRepository(Skills::class)->find(['id'=>$id]);
    }
}