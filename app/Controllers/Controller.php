<?php
namespace App\Controllers;

abstract class Controller
{
    /** Current page key, used by the sidebar for the active state */
    protected string $page = 'dashboard';

    /** Text displayed in <title> */
    protected string $title = 'Khotwa';

    /**
     * Render a content view (app/Views/pages/{$view}.php) inside the common layout.
     */
    protected function render(string $view, array $data = [], ?string $scriptsView = null): void
    {
        $content      = $this->capture(__DIR__ . '/../Views/pages/' . $view . '.php', $data);
        $extraScripts = $scriptsView
            ? $this->capture(__DIR__ . '/../Views/pages/' . $scriptsView . '.php', $data)
            : '';
        $title       = $this->title;
        $currentPage = $this->page;

        require_once __DIR__ . '/../Views/layout.php';
    }

    private function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    protected function redirect(string $page, array $params = []): never
    {
        header('Location: ' . url($page, $params));
        exit;
    }

    protected function validDate(string $d): bool
    {
        $dt = \DateTime::createFromFormat('Y-m-d', $d);
        return $dt !== false && $dt->format('Y-m-d') === $d;
    }
}
