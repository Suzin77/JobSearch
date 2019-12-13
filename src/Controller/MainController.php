<?php declare(strict_types= 1);

namespace App\Controller;

use App\Model\JobOfferContent;
use App\Model\JobOfferWriter;
use App\Entity\Job;
use App\Entity\Skills;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @var JobOfferWriter
     */
    private $jobOfferWriter;

    public function __construct(JobOfferWriter $jobOfferWriter)
    {
        $this->jobOfferWriter = $jobOfferWriter;
    }

    /**
     * @Route("", name = "index")
     */
    public function index()
    {
        return $this->render('main_body.html.twig');
    }

    /**
     * @Route("/get_offers", name = "get_offers")
     */
    public function update()
    {
        $url = 'https://justjoin.it/api/offers';
        $data = [];
        $jobOffers = new JobOfferContent();
        $offers = $jobOffers->getJobOffers($url);
        $this->saveOffers($offers);

        return $this->redirectToRoute('index');        
    }

    public function saveOffers(array $offers): void
    {
        $count = 0;
        //$this->jobOfferWriter->setDoctrine($this->getDoctrine());
        $lastUpdate = $this->lastByDate();
        foreach($offers as $job){
            if($lastUpdate < \strtotime($job['published_at'])){
                $count++;
                $this->jobOfferWriter->settingJob($job);
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
}