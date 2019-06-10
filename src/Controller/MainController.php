<?php declare(strict_types= 1);

namespace App\Controller;

use App\Model\JobOfferContent;
use App\Model\JobOfferWriter;
use App\Entity\Job;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MainController extends Controller
{
    /**
     * @Route("", name = "index ")
     */
    public function index()
    {
        
        $url = 'https://justjoin.it/api/offers';
        $data = [];
        $jobOffers = new JobOfferContent();
        $offers = $jobOffers->getJobOffers($url);

        $this->saveOffers($offers);
        //$this->getFirm($offers);
        return $this->render('base.html.twig');
    }

    public function getFirm($data)
    {
        echo count($data);
        foreach ($data as $firm){              
            echo $firm['title']." ".$firm['company_name']."<a href=\"".$firm['company_url']."\"/></br>" ;
        }

    }

    public function saveOffers(array $offers): void
    {
        $count = 0;
        $jobOfferWriter = new JobOfferWriter();
        $lastUpdate = $this->lastByDate();
        foreach($offers as $job){
            if($lastUpdate < \strtotime($job['published_at'])){
                $count++;
                $jobToSave =  $jobOfferWriter->settingJob($job);
                $this->saveJob($jobToSave);
            }
               
        }
        echo "Dodano nowych ofert ".$count;
    }

    public function lastByDate()
    {
        $lastentry = $this->getDoctrine()->getRepository(Job::class)->findOneBy([],['published_at'=>'DESC']);
        return \strtotime($lastentry->getPublishedAt());    
    }

    public function saveJob(Job $job): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($job);
        $entityManager->flush();
    }

    public function getNumberOfRows()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = "SELECT NUM_ROWS FROM information_schema.INNODB_SYS_TABLESTATS where NAME = 'jobsearch/job'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows[0]['NUM_ROWS'];
    }

    /**
     * @Route("/update", name = "update")
     */
    public function update()
    {
        $url = 'https://justjoin.it/api/offers';
        $data = [];
        $jobOffers = new JobOfferContent();
        $offers = $jobOffers->getJobOffers($url);

        $offers_from_db = $this->getDoctrine()->getRepository(Job::class)->findAll();
        var_dump($offers_from_db);
        $entityManager = $this->getDoctrine()->getManager();
    }
}