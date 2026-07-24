# 🤖 D'Mario AI Business Intelligence Agent

The D'Mario AI Copilot has evolved into a robust **Business Intelligence Analyst**. It is deeply integrated into the restaurant's operational database, utilizing a secure, decoupled architecture to provide real-time analytics, context-aware assistance, and robust operational protection.

## 🏗 Architecture Overview

The AI ecosystem is divided into two primary pillars, adhering to strict Clean Architecture principles:

### 1. The Orchestration Layer (`AIOrchestrator.php`)
The brain of the operation. It implements a **Native LLM Tool Calling (ReAct)** loop.
- **Workflow:** Retrieves conversation memory -> Assembles Prompt -> Queries the AI Provider -> Analyzes LLM Tool Requests -> Executes local PHP Repository functions -> Loops back to the AI Provider with the formatted data to generate a human-readable answer.
- **Memory Management (`SessionMemoryStore.php`):** Completely stateless and UUID-based. Enables the seamless "New Chat" functionality while heavily compartmentalizing user context.

### 2. The Security & Provider Pipeline (`AIChatService.php`)
This pipeline guarantees the safety of the application before a single token is sent to the LLM.
- **`InputSanitizer`:** Automatically strips malicious HTML (XSS prevention), normalizes Unicode (preventing Zero-Width Space attacks), and enforces a strict 10,000-character limit to prevent payload exhaustion.
- **`PromptGuard`:** A robust regex engine that actively monitors and intercepts Jailbreaks, Role Injection (e.g., "Act as AdminBot"), System Prompt Leakage, and Data Exfiltration attempts.
- **`DomainGuard`:** Ensures the conversation remains strictly focused on restaurant operations, rejecting general knowledge or competitor queries.

---

## 🛠 Available AI Analytical Engines (Tools)

Instead of relying on rigid, single-purpose endpoints, the agent utilizes dynamic analytical engines. The LLM dictates the parameters (`metric`, `period`, `limit`, `sort_by`), and the PHP backend executes the corresponding repository aggregations.

### 1. `AnalyzeRevenueTool`
Calculates total gross revenue, average order value (AOV), and handles complex period-over-period revenue comparisons (e.g., "Compare this month's revenue to last month").

### 2. `AnalyzeOrdersTool`
Monitors overall order volume, filters by order status (Pending, Completed, Cancelled), and can analyze fulfillment efficiency.

### 3. `AnalyzeMenuPerformanceTool`
Determines the best and worst-performing menu items. Supports sorting by total quantity sold or total revenue generated (e.g., "What are our top 5 most profitable dishes?").

### 4. `AnalyzePaymentsTool`
Breaks down payment methods used by customers, calculating distribution percentages between QRIS, Cash, and internal transfers.

---

## 💻 UI & Streaming Integration

The frontend operates on **Livewire v3**, featuring:
- **Instant Fluid Streaming:** The `StreamBuffer` translates raw Provider chunks into fluid frontend text updates.
- **High-Performance Auto-Scroll:** Utilizes a native browser `MutationObserver` to smoothly scroll the chat window frame-by-frame as the AI speaks, automatically pausing if the user manually navigates upwards to read older messages.
- **Dynamic New Chat:** A "New Chat" button that instantly generates a new UUID session, providing a completely blank slate without requiring a database wipe.

## 🛡️ Enterprise Testing Suite

The entire AI architecture is fortified by a massive **Pest PHP** unit testing suite (`tests/Unit/AI/`). It includes over 40 distinct security assertions verifying the mitigation of OWASP LLM Top 10 vulnerabilities, alongside behavioral tests for the ReAct loop utilizing `Mockery` to mock dependencies.
