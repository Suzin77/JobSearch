<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Job;
use App\Model\Stats;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class StatsController extends AbstractController
{
    /**
     * @var Stats
     */
    private $stats;

    public function __construct(Stats $stats)
    {
        $this->stats = $stats;
    }

    /**
    * @Route("/stats", name = "stats")
    */

    public function main()
    {
        $firms_num = $this->stats->getDistinctNumberOf('job','company_name');
        $skills_num = $this->stats->getDistinctNumberOf('skills', 'skill_name');
        $skills_list = $this->stats->getSkills();
        $offerAmounts = $this->stats->getNumberOfRows();

        $this->stats->getJob();
        $data = [
            'offersAmounts' =>$offerAmounts,
            'firms' => $firms_num,
            'skills' => $skills_num,
            'skills_list' => $skills_list
        ];
        return $this->render('stats.html.twig', ['data'=>$data]);
    }


    /**
     * @Route("/charts", name = "charts")
     */

    public function charts()
    {
        $data = $this->stats->jobsPerDay();
        $data = json_encode($data);
        $mostPopularRaw = $this->stats->getMostPopularSkills(15);
        $mostPopular = json_encode($mostPopularRaw);

        $mostPopularTen = $this->stats->getMostPopularSkills(3);

        //$manySkills = $stats->getDataWithPopularSkills();
        $manySkills = $this->stats->getDataWithPopularSkills();
        //$manySkills = [];

        //line chart of skill

        //$lineData = $this->stats->getSkillPerWeek("Python");
        $lineData = $this->stats->getSkillPerMonth('JavaScript');
        $lineData = json_encode($lineData);
        //dump($lineData);

        return $this->render('chart.html.twig',
            [
                'data'=>$data,
                'most_popular'=>$mostPopular,
                'many_skills'=>$manySkills,
                'line_data'=>$lineData
            ]);
    }


}