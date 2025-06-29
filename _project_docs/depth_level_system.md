## 🔧 **STEP 3**: Create `_project_docs/depth_level_system.md`

**Create a new documentation file:**

````markdown
# Depth Level System - جُذور Platform

## Overview

The جُذور platform implements a sophisticated 3-level depth system for each of the 4 educational roots, allowing teachers to create nuanced assessments that target specific cognitive levels.

## The 4 Roots × 3 Depth Matrix

### Educational Framework

| Root (جذر)          | Level 1 (سطحي)     | Level 2 (متوسط)        | Level 3 (عميق)         |
| ------------------- | ------------------ | ---------------------- | ---------------------- |
| **جَوهر (Jawhar)**  | Basic definitions  | Detailed understanding | Conceptual mastery     |
| **ذِهن (Zihn)**     | Simple analysis    | Critical thinking      | Complex reasoning      |
| **وَصلات (Waslat)** | Basic connections  | Pattern recognition    | System integration     |
| **رُؤية (Roaya)**   | Direct application | Creative solutions     | Innovation & synthesis |

### Technical Implementation

#### Database Schema

```sql
questions (
    id INT PRIMARY KEY,
    quiz_id INT,
    question TEXT,
    root_type ENUM('jawhar', 'zihn', 'waslat', 'roaya'),
    depth_level INT(1) CHECK (depth_level BETWEEN 1 AND 3),
    options JSON,
    correct_answer VARCHAR(500),
    created_at TIMESTAMP
);
```
````

// User selections in quiz creation form
{
"jawhar": {"levels": {"3": {"depth": 3, "count": 1}}}, // عميق
"zihn": {"levels": {"2": {"depth": 2, "count": 1}}}, // متوسط  
 "waslat": {"levels": {"1": {"depth": 1, "count": 1}}}, // سطحي
"roaya": {"levels": {"3": {"depth": 3, "count": 1}}} // عميق
}

// Transformation for AI generation
$rootsForAI = [
'jawhar' => ['1' => 0, '2' => 0, '3' => 1],
'zihn' => ['1' => 0, '2' => 1, '3' => 0],
'waslat' => ['1' => 1, '2' => 0, '3' => 0],
'roaya' => ['1' => 0, '2' => 0, '3' => 1]
];
