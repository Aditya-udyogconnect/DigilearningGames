<?php

namespace DigiLearning\Engine;

class ModuleManager
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function list_modules(): array
    {
        return [
            ['id' => 'google-email', 'title' => 'Google Workspace Email Setup'],
            ['id' => 'microsoft-email', 'title' => 'Microsoft 365 Email Setup'],
            ['id' => 'wordpress-site', 'title' => 'WordPress Website Starter'],
            ['id' => 'google-business', 'title' => 'Google Business Profile'],
            ['id' => 'plugins-security', 'title' => 'Plugin Installation & Security'],
            ['id' => 'seo', 'title' => 'SEO Fundamentals'],
            ['id' => 'crm', 'title' => 'CRM Onboarding'],
            ['id' => 'marketing', 'title' => 'Digital Marketing Launch'],
        ];
    }

    public function index_of(string $module_id): int
    {
        foreach ($this->list_modules() as $index => $module) {
            if ($module['id'] === $module_id) {
                return $index;
            }
        }

        return -1;
    }
}
