<?php declare(strict_types= 1);

namespace App\Controller;

use App\Entity\Job;
//use Symfony\Component\Debug\Debug;
use \Doctrine\Common\Util\Debug;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UpdateController extends AbstractController
{
    /**
     * @Route("/update", name = "update")
     */
    public function mainupdate()
    {
        $test_jobs = $this->getRandomJobs(10);
        //dump($test_jobs[0]);
        $entityManager = $this->getDoctrine()->getEntityManager();


        return $this->render('update.html.twig');
    }
    
    public function getRandomJobs(int $number)
    {
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findBy([],['id'=>'DESC'],10);
        dump($jobs);
        return $jobs;
    }

    public function skillsToArray(string $skills): array
    {
        
    }
}