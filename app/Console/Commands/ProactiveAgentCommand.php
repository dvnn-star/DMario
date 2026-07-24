<?php

namespace App\Console\Commands;

use App\Events\TriggerProactiveAgent;
use Illuminate\Console\Command;

class ProactiveAgentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:proactive {user_id=3 : The ID of the user to send the insight to} {--context= : The business context for the AI}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger the Proactive AI Agent to analyze the system and push insights to the user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $sessionId = 'user_' . $userId;
        
        $context = $this->option('context');
        
        if (!$context) {
            $context = "Look at today's revenue and the top selling menu items. Give me a brief 2 sentence summary of how we are doing today. Pretend you noticed this on your own and decided to reach out.";
        }

        $this->info("Triggering Proactive Agent for session: {$sessionId}");
        $this->line("Context: {$context}");

        // Dispatch the event which fires the Queue Job
        event(new TriggerProactiveAgent($sessionId, $context));

        $this->info("Event dispatched! If the queue worker is running, the AI will reason in the background and the chat UI will update shortly.");
    }
}
