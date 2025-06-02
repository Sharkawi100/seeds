# Features & Business Logic - Juzoor
Last Updated: December 2024

## Core Features

### 1. Educational Model (جُذور)
- 4 Roots: جَوهر (Essence), ذِهن (Mind), وَصلات (Connections), رُؤية (Vision)
- 3 Depth Levels per root (Surface, Medium, Deep)
- Holistic assessment approach

### 2. Quiz Creation Modes
- **Manual**: Teacher creates questions manually
- **AI-Powered**: Claude generates questions
- **Hybrid**: AI generates, teacher edits

### 3. Access Methods
- **Authenticated**: Teachers create, students take with accounts
- **Guest Access**: Via 6-character PIN, results stored for 7 days
- **Public Sharing**: Direct quiz links

### 4. AI Integration
- Educational text generation (stories, articles, dialogues)
- Question generation from text
- Balanced question distribution across roots
- Support for Arabic, English, Hebrew

### 5. Results & Analytics
- Root-wise scoring visualization (radar chart)
- Detailed answer review
- AI-generated performance reports
- Progress tracking over time

## User Roles
1. **Admin**: Full system access, user management
2. **Teacher**: Create quizzes, view all results
3. **Student**: Take quizzes, view own results
4. **Guest**: Take quiz via PIN, temporary results

## Business Rules
- Quizzes must have at least 1 question
- Each question must have 2-6 options
- PIN codes are 6 characters (alphanumeric)
- Guest results expire after 7 days
- AI usage is tracked and limited
