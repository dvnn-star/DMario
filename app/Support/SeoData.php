<?php

namespace App\Support;

/**
 * Data Transfer Object for SEO metadata.
 *
 * Passed from controllers via Inertia props and read by app.blade.php
 * to render meta tags server-side (critical for social media bots that
 * don't execute JavaScript).
 */
class SeoData
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public string $ogImage = '',
        public string $ogType = 'website',
        public string $canonical = '',
        public string $robots = 'index, follow',
    ) {}

    /**
     * Convert to array for Inertia serialization.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'ogImage' => $this->ogImage,
            'ogType' => $this->ogType,
            'canonical' => $this->canonical,
            'robots' => $this->robots,
        ];
    }
}
