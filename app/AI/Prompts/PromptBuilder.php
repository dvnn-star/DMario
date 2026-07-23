<?php

namespace App\AI\Prompts;

use App\AI\DTO\ChatMessage;

/**
 * Utility class to build and manage conversation prompts and context.
 */
class PromptBuilder
{
    /** @var ChatMessage[] */
    protected array $messages = [];

    /**
     * Add a system message to set the behavior or persona.
     * Often the first message in the array.
     */
    public function setSystemPrompt(string $content): self
    {
        // Typically system prompt goes first
        array_unshift($this->messages, new ChatMessage('system', $content));
        return $this;
    }

    /**
     * Add a user message to the context.
     */
    public function addUserMessage(string $content): self
    {
        $this->messages[] = new ChatMessage('user', $content);
        return $this;
    }

    /**
     * Add an assistant message (useful for maintaining conversation history).
     */
    public function addAssistantMessage(string $content): self
    {
        $this->messages[] = new ChatMessage('assistant', $content);
        return $this;
    }

    /**
     * Add raw ChatMessage objects directly.
     *
     * @param ChatMessage[] $messages
     */
    public function addMessages(array $messages): self
    {
        $this->messages = array_merge($this->messages, $messages);
        return $this;
    }

    /**
     * Compile a template string with variables.
     * Useful for dynamic prompts.
     */
    public static function compile(string $template, array $variables = []): string
    {
        $compiled = $template;
        foreach ($variables as $key => $value) {
            $compiled = str_replace('{{' . $key . '}}', (string)$value, $compiled);
        }
        return $compiled;
    }

    /**
     * Get the final array of messages ready to be sent to the provider.
     *
     * @return ChatMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Clear the current message history.
     */
    public function clear(): self
    {
        $this->messages = [];
        return $this;
    }
}
