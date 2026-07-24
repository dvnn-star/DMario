<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KnowledgeBaseRepository
{
    /**
     * Search the local markdown files for keywords.
     * Extremely basic keyword-based RAG simulation.
     *
     * @param string $query
     * @return array
     */
    public function search(string $query): array
    {
        $disk = Storage::disk('local');
        $path = 'ai/knowledge';
        
        if (!$disk->exists($path)) {
            return [];
        }

        $files = $disk->files($path);
        $results = [];
        $keywords = array_filter(explode(' ', strtolower($query)), fn($k) => strlen($k) > 2);

        foreach ($files as $file) {
            if (!Str::endsWith($file, '.md')) continue;

            $content = $disk->get($file);
            $lowerContent = strtolower($content);
            $filename = basename($file);
            
            $score = 0;
            
            // Basic TF scoring
            foreach ($keywords as $keyword) {
                $score += substr_count($lowerContent, $keyword);
                
                // Boost title matches
                if (str_contains(strtolower($filename), $keyword)) {
                    $score += 5;
                }
            }

            if ($score > 0) {
                $results[] = [
                    'document' => $filename,
                    'content' => $content,
                    'relevance_score' => $score,
                ];
            }
        }

        // Sort by highest score
        usort($results, fn($a, $b) => $b['relevance_score'] <=> $a['relevance_score']);

        // Return top 3 matches
        return array_slice($results, 0, 3);
    }
}
