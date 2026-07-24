<?php

namespace App\AI\Streaming\Events;

/**
 * Discrete streaming lifecycle events.
 * Decouples the Orchestrator from the UI — the Livewire component
 * reacts to events, never to raw HTTP data.
 */
enum StreamEvent: string
{
    case STREAM_STARTED = 'stream.started';
    case TOKEN_RECEIVED = 'stream.token';
    case TOOL_STARTED = 'stream.tool.started';
    case TOOL_FINISHED = 'stream.tool.finished';
    case STREAM_COMPLETED = 'stream.completed';
    case STREAM_CANCELLED = 'stream.cancelled';
    case STREAM_ERROR = 'stream.error';
}
