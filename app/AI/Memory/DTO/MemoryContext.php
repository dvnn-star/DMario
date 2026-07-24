<?php

namespace App\AI\Memory\DTO;

/**
 * Immutable representation of the user's current environmental state.
 */
readonly class MemoryContext
{
    /**
     * @param string|null $currentTopic Extracted intent/topic (e.g., 'sales')
     * @param string|null $activePage The dashboard URL/route they are viewing
     * @param string|null $activeEntity Specific entity ID (e.g., Order #123)
     */
    public function __construct(
        public ?string $currentTopic = null,
        public ?string $activePage = null,
        public ?string $activeEntity = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'current_topic' => $this->currentTopic,
            'active_page' => $this->activePage,
            'active_entity' => $this->activeEntity,
        ];
    }
}
