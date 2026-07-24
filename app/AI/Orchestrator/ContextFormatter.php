<?php

namespace App\AI\Orchestrator;

/**
 * Converts raw tool execution results into human-readable text
 * that the LLM can reference in its response.
 */
class ContextFormatter
{
    /**
     * Format a map of tool results into a structured text block.
     *
     * @param array<string, mixed> $toolResults Map of tool_name => result
     * @return string
     */
    public function format(array $toolResults): string
    {
        if (empty($toolResults)) {
            return '';
        }

        $sections = [];

        foreach ($toolResults as $toolName => $result) {
            $formatted = $this->formatToolResult($toolName, $result);
            if ($formatted !== '') {
                $sections[] = $formatted;
            }
        }

        if (empty($sections)) {
            return '';
        }

        return "=== RESTAURANT DATA CONTEXT ===\n\n"
            . implode("\n\n", $sections)
            . "\n\n=== END CONTEXT ===";
    }

    /**
     * Format an individual tool result based on its name.
     */
    protected function formatToolResult(string $toolName, mixed $result): string
    {
        if (!is_array($result)) {
            return '';
        }

        return match ($toolName) {
            'analyze_revenue'          => $this->formatAnalyzeRevenue($result),
            'analyze_orders'           => $this->formatAnalyzeOrders($result),
            'analyze_menu_performance' => $this->formatAnalyzeMenuPerformance($result),
            'analyze_payments'         => $this->formatAnalyzePayments($result),
            'get_reservation'          => $this->formatReservationStats($result),
            'update_order_status'      => $this->formatUpdateOrderStatus($result),
            'search_knowledge_base'    => $this->formatKnowledgeBase($result),
            'get_categories'           => $this->formatCategories($result),
            'get_table_status'         => $this->formatTableStatus($result),
            'get_pending_reservations' => $this->formatPendingReservations($result),
            'get_available_menus'      => $this->formatAvailableMenus($result),
            default                    => $this->formatGeneric($toolName, $result),
        };
    }

    protected function formatAnalyzeRevenue(array $data): string
    {
        if (isset($data['difference'])) {
            return "Revenue Comparison:\n"
                . "- Period 1 ({$data['period_1']}): IDR " . number_format($data['period_1_revenue'], 0, ',', '.') . "\n"
                . "- Period 2 ({$data['period_2']}): IDR " . number_format($data['period_2_revenue'], 0, ',', '.') . "\n"
                . "- Difference: IDR " . number_format($data['difference'], 0, ',', '.') . "\n"
                . "- Trend: {$data['trend']} ({$data['percentage_change']}%)\n";
        }

        if (isset($data['metric']) && $data['metric'] === 'Average Order Value') {
            return "Average Order Value (AOV): IDR " . number_format($data['value'], 0, ',', '.') . "\n";
        }

        $revenue = $data['total_revenue'] ?? 0;
        $orders = $data['total_orders'] ?? 0;
        $period = $data['period'] ?? 'unknown';

        return "Revenue Statistics:\n"
            . "- Period: {$period}\n"
            . "- Total Revenue: IDR " . number_format($revenue, 0, ',', '.') . "\n"
            . "- Total Orders: {$orders}\n";
    }

    protected function formatAnalyzeOrders(array $data): string
    {
        // 1. If Trends
        if (isset($data['trends'])) {
            $lines = ["Order Trends ({$data['group_by']}):"];
            foreach ($data['trends'] as $t) {
                $rev = number_format($t['revenue'], 0, ',', '.');
                $lines[] = "- {$t['label']}: {$t['count']} orders (IDR {$rev})";
            }
            return implode("\n", $lines);
        }

        // 2. If Recent Orders (Array of arrays/objects usually has an 'id' or we can check if it's a list)
        if (isset($data[0]) && (is_array($data[0]) || is_object($data[0]))) {
            $lines = ["Recent Orders:"];
            foreach ($data as $order) {
                $orderArray = (array) $order;
                $id = $orderArray['id'] ?? '?';
                $status = $orderArray['status'] ?? 'unknown';
                $total = isset($orderArray['total_price']) ? 'IDR ' . number_format($orderArray['total_price'], 0, ',', '.') : (isset($orderArray['total']) ? 'IDR ' . number_format($orderArray['total'], 0, ',', '.') : '');
                $lines[] = "- Order #{$id}: {$status} {$total}";
            }
            return implode("\n", $lines);
        }

        // 3. Stats
        $today = $data['orders_today'] ?? 0;
        $month = $data['orders_this_month'] ?? 0;
        $total = $data['total_orders'] ?? 0;

        return "Order Statistics:\n- Orders Today: {$today}\n- Orders This Month: {$month}\n- Total Orders All-Time: {$total}";
    }

    protected function formatAnalyzeMenuPerformance(array $data): string
    {
        if (empty($data)) {
            return "Menu Performance:\n- No data available.";
        }

        $lines = ["Menu Performance Analysis:"];
        foreach ($data as $i => $menu) {
            $rank = $i + 1;
            $name = $menu['name'] ?? 'Unknown';
            $qty = $menu['total_quantity'] ?? 0;
            $rev = isset($menu['total_revenue']) ? 'IDR ' . number_format($menu['total_revenue'], 0, ',', '.') : 'IDR 0';
            $lines[] = "- #{$rank} {$name} — {$qty} orders ({$rev})";
        }

        return implode("\n", $lines);
    }

    protected function formatAnalyzePayments(array $data): string
    {
        $dist = $data['distribution'] ?? [];
        if (empty($dist)) {
            return "Payment Analytics:\n- No data available.";
        }

        $lines = ["Payment Analytics (Period: {$data['period']}):"];
        $lines[] = "- Total Orders: {$data['total_orders']}";
        
        foreach ($dist as $method => $stats) {
            $rev = number_format($stats['revenue'], 0, ',', '.');
            $lines[] = "- {$method}: {$stats['count']} orders ({$stats['percentage']}%) — IDR {$rev}";
        }

        return implode("\n", $lines);
    }

    protected function formatReservationStats(array $data): string
    {
        $count = $data['total_reservations'] ?? 0;
        $period = $data['period'] ?? 'unknown';

        return "Reservation Statistics:\n"
            . "- Period: {$period}\n"
            . "- Total Reservations: {$count}";
    }



    protected function formatUpdateOrderStatus(array $data): string
    {
        $status = $data['status'] ?? 'unknown';
        
        if ($status === 'pending_approval') {
            return "ACTION PENDING APPROVAL:\n" . ($data['message'] ?? 'User approval required.');
        }
        
        if ($status === 'success') {
            return "ACTION SUCCESSFUL:\n" . ($data['message'] ?? 'Action completed.');
        }

        return "ACTION FAILED:\n" . ($data['message'] ?? 'An error occurred.');
    }

    protected function formatKnowledgeBase(array $data): string
    {
        $status = $data['status'] ?? 'unknown';
        
        if ($status !== 'success' || empty($data['documents'])) {
            return "Knowledge Base Search Results:\n" . ($data['message'] ?? 'No documents found.');
        }

        $lines = ["Knowledge Base Search Results for '{$data['query']}':"];
        
        foreach ($data['documents'] as $doc) {
            $title = $doc['document'] ?? 'Unknown Document';
            $content = $doc['content'] ?? '';
            $lines[] = "--- Document: {$title} ---\n{$content}\n";
        }

        return implode("\n", $lines);
    }

    protected function formatCategories(array $categories): string
    {
        if (empty($categories)) {
            return "Menu Categories:\n- No categories found.";
        }

        $lines = ["Menu Categories:"];
        foreach ($categories as $cat) {
            $name = $cat['name'] ?? 'Unknown';
            $lines[] = "- {$name}";
        }

        return implode("\n", $lines);
    }

    protected function formatTableStatus(array $data): string
    {
        $available = $data['available'] ?? [];
        $occupied = $data['occupied'] ?? [];

        $lines = ["Table Status:"];
        
        $lines[] = "\nAvailable Tables (" . count($available) . "):";
        if (empty($available)) {
            $lines[] = "- None";
        } else {
            foreach ($available as $t) {
                $lines[] = "- Table " . ($t->tableNumber ?? 'Unknown');
            }
        }

        $lines[] = "\nOccupied Tables (" . count($occupied) . "):";
        if (empty($occupied)) {
            $lines[] = "- None";
        } else {
            foreach ($occupied as $t) {
                $lines[] = "- Table " . ($t->tableNumber ?? 'Unknown');
            }
        }

        return implode("\n", $lines);
    }

    protected function formatPendingReservations(array $reservations): string
    {
        if (empty($reservations)) {
            return "Pending Reservations:\n- No pending reservations at this time.";
        }

        $lines = ["Pending Reservations:"];
        foreach ($reservations as $res) {
            $name = $res->customerName ?? 'Unknown';
            $guests = $res->numberOfGuests ?? '?';
            $time = $res->reservationTime ? \Carbon\Carbon::parse($res->reservationTime)->format('Y-m-d H:i') : 'Unknown Time';
            $table = $res->tableIdentifier ? " (Table {$res->tableIdentifier})" : '';
            
            $lines[] = "- {$name} for {$guests} pax at {$time}{$table}";
        }

        return implode("\n", $lines);
    }

    protected function formatAvailableMenus(array $menus): string
    {
        if (empty($menus)) {
            return "Available Menus:\n- No menus available.";
        }

        $lines = ["Available Menus:"];
        foreach ($menus as $menu) {
            $name = $menu->name ?? 'Unknown';
            $price = isset($menu->price) ? number_format($menu->price, 0, ',', '.') : '0';
            $cat = $menu->categoryName ? "[{$menu->categoryName}]" : '';
            $lines[] = "- {$cat} {$name} — IDR {$price}";
        }

        return implode("\n", $lines);
    }

    protected function formatGeneric(string $toolName, array $data): string
    {
        $readable = str_replace('_', ' ', $toolName);
        $readable = ucfirst($readable);

        return "{$readable}:\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
