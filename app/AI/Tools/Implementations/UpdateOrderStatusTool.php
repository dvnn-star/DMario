<?php

namespace App\AI\Tools\Implementations;

use App\AI\Contracts\AITool;
use App\Repositories\Contracts\OrderRepositoryInterface;

class UpdateOrderStatusTool implements AITool
{
    public function __construct(
        protected OrderRepositoryInterface $repository
    ) {
    }

    public function name(): string
    {
        return 'update_order_status';
    }

    public function description(): string
    {
        return 'Update the status of an order. CRITICAL: This is a mutation tool. You MUST set user_approved to false by default. If it is false, you will receive a pending_approval response. You must then explicitly ask the user for permission. ONLY set user_approved to true if the user has explicitly stated they approve the action.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'order_id' => [
                    'type' => 'integer',
                    'description' => 'The ID of the order to update.',
                ],
                'status' => [
                    'type' => 'string',
                    'description' => 'The new status of the order (e.g., pending, processing, completed, cancelled).',
                    'enum' => ['pending', 'processing', 'completed', 'cancelled'],
                ],
                'user_approved' => [
                    'type' => 'boolean',
                    'description' => 'Must be false initially. Set to true ONLY if the user has explicitly approved the update in the chat history.',
                    'default' => false,
                ],
            ],
            'required' => ['order_id', 'status', 'user_approved'],
        ];
    }

    public function execute(array $parameters): mixed
    {
        $orderId = $parameters['order_id'] ?? null;
        $status = $parameters['status'] ?? null;
        $userApproved = $parameters['user_approved'] ?? false;

        if (!$orderId || !$status) {
            return [
                'status' => 'error',
                'message' => 'order_id and status are required.',
            ];
        }

        // Human-in-the-Loop Guardrail
        if ($userApproved !== true) {
            return [
                'status' => 'pending_approval',
                'order_id' => $orderId,
                'new_status' => $status,
                'message' => "ACTION PENDING APPROVAL. You MUST ask the user for explicit permission to update Order #{$orderId} to '{$status}'. Tell the user: 'Please reply with \"I approve\" to authorize this action.' DO NOT execute this tool again until they approve.",
            ];
        }

        // Execute Mutation
        $success = $this->repository->updateOrderStatus((int)$orderId, $status);

        if ($success) {
            return [
                'status' => 'success',
                'order_id' => $orderId,
                'new_status' => $status,
                'message' => "Order #{$orderId} has been successfully updated to '{$status}'. Inform the user.",
            ];
        }

        return [
            'status' => 'error',
            'message' => "Failed to update Order #{$orderId}. Order may not exist.",
        ];
    }
}
