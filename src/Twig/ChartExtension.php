<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;
use App\Service\RateInfoService;
use App\Service\TaskService;
use App\Form\ChartChoiceType;

class ChartExtension extends AbstractExtension
{
    private $rateInfoService;

    private $taskService;

    private $twig;

    private $user;

    private $formFactory;
    
    public function __construct(RateInfoService $rateInfoService, Environment $twig, TokenStorageInterface $tokenStorage, FormFactoryInterface $formFactory, TaskService $taskService)
    {
        $this->rateInfoService = $rateInfoService;
        $this->taskService = $taskService;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
        }
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('render_chart', [$this, 'renderChart']),
            new TwigFunction('positive_marks', [$this, 'getPositiveMarks']),
            new TwigFunction('negative_marks', [$this, 'getNegativeMarks']),
            new TwigFunction('tasks_count', [$this, 'getTasksCount']),
            new TwigFunction('last_five_days', [$this, 'getLastFiveDays']),
        ];
    }

    public function renderChart()
    {
        $form = $this->formFactory->create(ChartChoiceType::class)->createView();

        return $this->twig->render('dashboard/chart/chart.html.twig', [
            'form' => $form
        ]);
    }
    
    public function getPositiveMarks(): ?array
    {
        $rates = $this->rateInfoService->allIncomingPositiveByUserLastFiveDays($this->user);
        
        return $this->sortData($rates);
    }

    public function getNegativeMarks(): ?array
    {
        $rates = $this->rateInfoService->allIncomingNegativeByUserLastFiveDays($this->user);
        
        return $this->sortData($rates);
    }

    public function getTasksCount(): ?array
    {
        $tasks = $this->taskService->allTasksInAllTeamWhereUserParticipate($this->user);

        return $this->sortData(new ArrayCollection($tasks));
    }

    public function getLastFiveDays(): ?array
    {
        for ($i = 0; $i < 5; $i++) {
            $days[] = date('j F', strtotime("-$i days"));
        }

        return array_reverse($days);
    }

    private function sortData(Collection $collection): ?array
    {
        $days = $this->getLastFiveDays();
        $sortedArray = [];
        $countedArray = [];

        foreach ($days as $day) {
            foreach ($collection as $item) {
                if (method_exists($item, 'getTask')) {
                    if ($day == $date = $item->getTask()->getCreatedAtWithoutFormat()->format('j F')) {
                        $sortedArray[$date][] = $item;
                        $collection->removeElement($item);
                    }
                } else {
                    if ($day == $date = $item->getCreatedAtWithoutFormat()->format('j F')) {
                        $sortedArray[$date][] = $item;
                        $collection->removeElement($item);
                    }
                }
            }

            if (!array_key_exists($day, $sortedArray)) {
                $sortedArray[$day] = [];
            }
        }

        foreach ($sortedArray as $item) {
            $countedArray[] = count($item);
        }

        return $countedArray;
    }
}

