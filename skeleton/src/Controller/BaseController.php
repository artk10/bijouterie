<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}
