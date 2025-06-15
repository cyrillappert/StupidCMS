<?php

declare(strict_types=1);

namespace StupidCMS\Http\Controllers;

use StupidCMS\Content\ContentProxy;

class ProjectController extends BaseController
{
    public function show(): string
    {
        $index = (int)($_GET['index'] ?? 0);

        $workContent = $this->contentService->getContentBySlug('work');
        if (!$workContent) {
            return $this->notFound('Work section not found');
        }

        $children = $this->contentService->getChildren('work');
        if ($index < 0 || $index >= count($children)) {
            return $this->notFound('Project not found');
        }

        $project = $children[$index];
        $workProxy = $this->contentService->getContentBySlug($project['slug']);

        if (!$workProxy) {
            return $this->notFound('Project content not found');
        }

        $workProxy = new ContentProxy($workProxy, $this->contentService);
        $navigation = $this->buildNavigation($index, $children);

        return $this->renderProjectResponse($workProxy, $navigation);
    }

    private function buildNavigation(int $currentIndex, array $children): array
    {
        $totalProjects = count($children);
        
        return [
            'current' => $currentIndex,
            'total' => $totalProjects,
            'hasPrev' => $currentIndex > 0,
            'hasNext' => $currentIndex < $totalProjects - 1,
            'prevIndex' => max(0, $currentIndex - 1),
            'nextIndex' => min($totalProjects - 1, $currentIndex + 1)
        ];
    }

    private function renderProjectResponse(ContentProxy $workProxy, array $navigation): string
    {
        return $this->renderTemplate('project', [
            'foo' => $workProxy,
            'nav' => $navigation
        ]);
    }
}