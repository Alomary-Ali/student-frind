<?php

declare(strict_types=1);

namespace Modules\StudentServices\Presentation\Http\Controllers;

use Illuminate\View\View;
use Modules\StudentServices\Domain\Contracts\FaqRepositoryInterface;

final readonly class FaqController
{
    public function __construct(
        private FaqRepositoryInterface $faqs,
    ) {}

    public function index(): View
    {
        $items = $this->faqs->findAll();

        return view('student-services.faq.index', ['faqs' => $items]);
    }
}
