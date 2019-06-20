<?php declare(strict_types= 1);

namespace App\Controller;

use App\Model\JobOfferContent;
use App\Model\JobOfferWriter;
use App\Entity\Job;
use App\Entity\Skills;

use Symfony\Component\Debug\Debug;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MainController extends AbstractController
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
        return $this->render('base.html.twig');
    }

    public function saveOffers(array $offers): void
    {
        $count = 0;
        $jobOfferWriter = new JobOfferWriter();
        //passing doctrine to jobofferwritter bc for some reason getDoctrine() method not working in JobofferWriter file , 
        //dont know why.
        $jobOfferWriter->setDoctrine($this->getDoctrine());
        $lastUpdate = $this->lastByDate();
        foreach($offers as $job){
            if($lastUpdate < \strtotime($job['published_at'])){
                $count++;
                $jobOfferWriter->settingJob($job);
            }          
        }
    }

    public function lastByDate():int
    {
        $lastentry = $this->getDoctrine()->getRepository(Job::class)->findOneBy([],['published_at'=>'DESC']);
        return \strtotime($lastentry->getPublishedAt());    
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
        Debug::enable();
        $url = 'https://justjoin.it/api/offers';
        $data = [];
        $jobOffers = new JobOfferContent();
        $offers = $jobOffers->getJobOffers($url);
        
        $offers_from_db = $this->getDoctrine()->getRepository(Job::class)->findAll();
        
        \Doctrine\Common\Util\Debug::dump($offers_from_db[0]);
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('base.html.twig');
    }
}