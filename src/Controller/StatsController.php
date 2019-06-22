<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Job;
use App\Model\Stats;
//use Symfony\Component\Debug\Debug;
use \Doctrine\Common\Util\Debug;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class StatsController extends AbstractController
{
    /**
    * @Route("/stats", name = "stats")
    */

    public function main()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection(); 
        $stats = new Stats($conn);
        $firms_num = $stats->getDistinctNumberOf('job','company_name');
        $skills_num = $stats->getDistinctNumberOf('skills', 'skill_name');
        $skills_list = $stats->getSkills();
        $offerAmounts = $this->getNumberOfRows();

        $stats->getJob();
        $data = [
            'offersAmounts' =>$offerAmounts,
            'firms' => $firms_num,
            'skills' => $skills_num,
            'skills_list' => $skills_list
        ];
        return $this->render('stats.html.twig', ['data'=>$data]);
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