<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\KnowledgeBaseRepository;

class SearchKnowledgeBaseTool implements AITool
{
    public function __construct(
        protected KnowledgeBaseRepository $repository
    ) {
    }

    public function name(): string
    {
        return 'search_knowledge_base';
    }

    public function description(): string
    {
        return 'Search the restaurant\'s internal Knowledge Base for Standard Operating Procedures (SOPs), recipes, store policies, or historical rules. Use this whenever the user asks "how to", "what is the policy", "recipe for", etc.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'query' => [
                    'type' => 'string',
                    'description' => 'The keyword or phrase to search for (e.g. "refund policy", "nasi goreng recipe").',
                ],
            ],
            'required' => ['query'],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $query = $parameters['query'] ?? '';

        if (empty($query)) {
            return [
                'status' => 'error',
                'message' => 'Search query is required.',
            ];
        }

        $results = $this->repository->search($query);

        if (empty($results)) {
            return [
                'status' => 'not_found',
                'message' => "No relevant knowledge base documents found for '{$query}'.",
            ];
        }

        return [
            'status' => 'success',
            'query' => $query,
            'documents' => $results,
        ];
    }
}
