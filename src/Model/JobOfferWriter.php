<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\Job;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Doctrine\ORM\EntityManager;

class JobOfferWriter extends Controller
{
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
            ->setMarkerIcon($data['marker_icon']);

        //for some reason code below dont work here but it works in maincontroller file.
         //$entityManager = $this->getDoctrine()->getManager();
         //$entityManager->persist($job);
         //$entityManager->flush();

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
}