# Complete Database Schema - Juzoor
Last Updated: December 2024

## Tables & Relationships

### users
- id, name, email, password, is_admin, is_school
- Has many: quizzes, results

### quizzes  
- id, user_id, title, subject (arabic|english|hebrew)
- grade_level (1-9), settings (JSON), pin
- Has many: questions, results

### questions
- id, quiz_id, question, passage, passage_title
- root_type (jawhar|zihn|waslat|roaya)
- depth_level (1|2|3)
- options (JSON array), correct_answer
- Has many: answers

### results
- id, quiz_id, user_id (nullable), guest_token
- scores (JSON: {jawhar: %, zihn: %, waslat: %, roaya: %})
- total_score, expires_at
- Has many: answers

### answers
- id, question_id, result_id
- selected_answer, is_correct

### ai_usage_logs
- id, type, model, count, user_id, created_at
