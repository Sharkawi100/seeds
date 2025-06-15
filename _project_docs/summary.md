# جُذور (Juzoor) - Educational Assessment Platform

## Updated Project Documentation

Last Updated: December 2024

---

## 🌍 **Language Support**

### **Interface Language**

-   **Arabic Only**: The entire website interface, navigation, forms, and user interactions are in Arabic
-   **RTL Layout**: Full right-to-left text direction support
-   **Arabic Typography**: Optimized fonts and text rendering for Arabic content
-   **No Multi-language Interface**: Unlike previous versions, there is no language switcher for the UI

### **Content Languages**

The platform supports creating quizzes and educational content in:

-   **Arabic** (اللغة العربية) - Primary language
-   **English** (اللغة الإنجليزية) - Secondary support
-   **Hebrew** (اللغة العبرية) - Secondary support

### **Important Distinction**

```
Interface Language: Arabic ONLY
Content Languages: Arabic, English, Hebrew
```

---

## 🏗️ **Architecture Overview**

### **Technology Stack**

-   **Backend**: Laravel 11.x, PHP 8.2+
-   **Database**: MySQL 8.0 with UTF-8 collation for Arabic support
-   **Frontend**: Blade templates, Tailwind CSS 3.x, Alpine.js 3.x
-   **AI Integration**: Anthropic Claude API (claude-3-5-sonnet-20241022)
-   **Hosting**: Namecheap shared hosting with subdirectory `/roots`

### **Core Educational Model**

The platform implements the unique **4-Roots (الجُذور الأربعة)** assessment model:

1. **جَوهر (Jawhar)** - Essence: "What is it?" - Definitions and core understanding
2. **ذِهن (Zihn)** - Mind: "How does it work?" - Analysis and critical thinking
3. **وَصلات (Waslat)** - Connections: "How does it connect?" - Relationships and integration
4. **رُؤية (Roaya)** - Vision: "How can we use it?" - Application and innovation

Each root supports 3 depth levels: **سطحي (Surface)**, **متوسط (Medium)**, **عميق (Deep)**

---

## 🎯 **Key Features**

### **Quiz Creation**

-   **3 Creation Methods**: Manual, AI-powered, Hybrid
-   **Wizard Interface**: 3-step creation process with modern UI
-   **AI Text Generation**: Claude-powered educational content creation
-   **Configuration Settings**: Time limits, question shuffling, auto-activation
-   **4-Roots Distribution**: Intelligent question categorization

### **Access Methods**

-   **Teacher Dashboard**: Full quiz management and analytics
-   **PIN-based Access**: 6-character codes for student access
-   **Guest Mode**: No registration required for students
-   **Direct URLs**: `/quiz/{id}/take` for authenticated users

### **Assessment Features**

-   **Root-wise Scoring**: Individual percentages for each of the 4 roots
-   **Real-time Results**: Immediate feedback with visual charts
-   **Guest Results**: 7-day temporary access for non-registered users
-   **Analytics Dashboard**: Comprehensive teacher insights

---

## 🗄️ **Database Schema**

### **Core Tables**

```sql
-- Users with Arabic-focused fields
users (id, name, email, user_type, is_admin, school_name, ...)

-- Subjects supporting multiple languages
subjects (id, name, slug, is_active, sort_order)

-- Quizzes with configuration settings
quizzes (id, user_id, title, subject_id, grade_level, pin,
         time_limit, passing_score, shuffle_questions,
         shuffle_answers, show_results, is_active, settings)

-- Questions with 4-roots classification
questions (id, quiz_id, question, root_type, depth_level,
           options, correct_answer, passage, passage_title)

-- Results with root-wise scoring
results (id, quiz_id, user_id, guest_token, guest_name,
         scores, total_score, expires_at)

-- Individual answer tracking
answers (id, question_id, result_id, selected_answer, is_correct)
```

### **Important Fields**

-   `root_type`: ENUM('jawhar', 'zihn', 'waslat', 'roaya')
-   `depth_level`: INTEGER(1|2|3)
-   `scores`: JSON with individual root percentages
-   `guest_token`: 32-character string for guest access

---

## 🔐 **User Management**

### **User Types**

-   **Admin** (`is_admin = 1`): Full system access
-   **Teacher** (`user_type = 'teacher'`): Quiz creation and management
-   **Student** (`user_type = 'student'`): Quiz taking only
-   **Guest**: Temporary access via PIN (no account required)

### **Authentication Flow**

-   **Arabic Interface**: All auth forms in Arabic
-   **Teacher Approval**: New teachers require admin approval
-   **Student Registration**: Optional (PIN access available)
-   **Session Management**: 120-minute sessions with remember tokens

---

## 🚀 **Deployment Configuration**

### **Production Environment**

-   **URL**: `https://www.iseraj.com/roots`
-   **Subdirectory Setup**: Laravel app in `/roots` folder
-   **Database**: `jqfujdmy_iseraj_roots`
-   **Environment**: Production (`APP_ENV=production`)

### **Key Configuration**

```env
APP_URL=https://www.iseraj.com/roots
ASSET_URL=https://www.iseraj.com/roots
APP_LOCALE=ar                    # Arabic interface
APP_FALLBACK_LOCALE=en          # Fallback only
SESSION_DOMAIN=.iseraj.com
SESSION_PATH=/roots
```

### **Arabic-Specific Settings**

-   **Timezone**: `Asia/Jerusalem`
-   **Locale**: `ar` (Arabic)
-   **Faker Locale**: `ar_SA` (Saudi Arabic)
-   **Database Charset**: `utf8mb4_unicode_ci`

---

## 📱 **Frontend Architecture**

### **Arabic-First Design**

-   **RTL Layout**: All components designed for right-to-left reading
-   **Arabic Typography**: Optimized for Arabic text rendering
-   **Cultural Colors**: Color schemes appropriate for Arabic users
-   **Icon Direction**: All arrows and directional elements mirrored for RTL

### **CSS Framework**

```css
/* Tailwind CSS with RTL utilities */
.rtl\:space-x-reverse > :not([hidden]) ~ :not([hidden]) {
    --tw-space-x-reverse: 1;
}

/* Arabic font optimization */
body {
    font-family: "Tajawal", "Cairo", sans-serif;
}
```

### **JavaScript Patterns**

-   **Form Validation**: Arabic error messages
-   **AJAX Endpoints**: All responses in Arabic
-   **Date/Time**: Arabic or localized number formats

---

## 🤖 **AI Integration**

### **Claude API Configuration**

-   **Model**: `claude-3-5-sonnet-20241022`
-   **Max Tokens**: 4000 per request
-   **Temperature**: 0.7 for creative content
-   **Timeout**: 60 seconds

### **Arabic AI Capabilities**

-   **Text Generation**: Native Arabic educational content
-   **Question Creation**: Arabic questions with proper grammar
-   **Root Classification**: AI understands the 4-roots model in Arabic
-   **Cross-language**: Can generate content in Arabic, English, or Hebrew

### **Usage Tracking**

```php
// AI usage logged for monitoring
ai_usage_logs (id, type, model, count, user_id, created_at)
```

---

## 🔧 **Development Guidelines**

### **Arabic Development Standards**

1. **All user-facing text in Arabic**: Error messages, notifications, labels
2. **RTL-first CSS**: Design for Arabic, adapt for other languages
3. **Arabic validation**: Proper Arabic text validation rules
4. **Cultural sensitivity**: Colors, imagery appropriate for Arabic users

### **Code Conventions**

```php
// Arabic display names in views
$roots = [
    'jawhar' => ['name' => 'جَوهر', 'icon' => '🎯'],
    'zihn' => ['name' => 'ذِهن', 'icon' => '🧠'],
    'waslat' => ['name' => 'وَصلات', 'icon' => '🔗'],
    'roaya' => ['name' => 'رُؤية', 'icon' => '👁️']
];

// Arabic success messages
return redirect()->with('success', 'تم إنشاء الاختبار بنجاح');

// Arabic validation rules
'title' => 'required|string|max:255|regex:/^[\p{Arabic}\s\p{P}\p{N}]+$/u'
```

### **Database Best Practices**

-   **UTF-8 Collation**: All text fields use `utf8mb4_unicode_ci`
-   **Arabic Indexing**: Proper indexing for Arabic text searches
-   **Field Names**: English field names, Arabic content

---

## 📊 **Analytics & Reporting**

### **Arabic Dashboard**

-   **Chart Labels**: All in Arabic
-   **Date Formats**: Arabic/Hijri calendar support
-   **Number Formats**: Arabic-Indic numerals where appropriate
-   **Export Options**: PDF reports with proper Arabic rendering

### **Performance Metrics**

-   **Page Load**: Optimized for Arabic fonts and RTL layouts
-   **Database**: Indexed for Arabic text searches
-   **Caching**: Arabic content cached efficiently
-   **Mobile**: RTL-optimized mobile experience

---

## 🚨 **Known Limitations**

### **Language Constraints**

-   **Interface**: Arabic only - no language switcher
-   **Support**: Customer support primarily in Arabic
-   **Documentation**: User guides and help in Arabic
-   **Error Messages**: System errors displayed in Arabic

### **Technical Considerations**

-   **SEO**: Arabic-focused meta tags and content
-   **URLs**: Latin characters for SEO, Arabic content
-   **Sitemap**: Arabic content structure
-   **Social Sharing**: Arabic descriptions and titles

---

## 🔮 **Future Roadmap**

### **Phase 1: Current (Arabic Foundation)**

-   ✅ Arabic-only interface established
-   ✅ 4-Roots model implemented
-   ✅ AI integration working
-   ✅ Guest access via PIN

### **Phase 2: Enhanced Features**

-   🔄 Advanced analytics dashboard
-   🔄 Mobile app (Arabic RTL)
-   🔄 Offline quiz capability
-   🔄 Parent portal integration

### **Phase 3: Scale & Performance**

-   🔄 Multi-school deployment
-   🔄 Advanced AI features
-   🔄 Integration with LMS systems
-   🔄 API for third-party access

### **Not Planned: Interface Translations**

-   ❌ English interface version
-   ❌ Hebrew interface version
-   ❌ Multi-language UI switcher
-   ❌ Internationalization (i18n) for interface

---

## 📞 **Support & Maintenance**

### **Arabic Support Team**

-   **Primary Language**: Arabic
-   **Documentation**: All user guides in Arabic
-   **Training Materials**: Arabic video tutorials
-   **Community**: Arabic-speaking user community

### **Technical Stack Maintenance**

-   **Laravel Updates**: Keep framework current
-   **Security Patches**: Regular security updates
-   **Database Optimization**: Arabic text indexing
-   **Performance Monitoring**: RTL layout optimization

---

## 📄 **File Structure Summary**

```
roots/
├── app/
│   ├── Http/Controllers/          # Arabic response logic
│   ├── Models/                    # UTF-8 Arabic data models
│   └── Services/ClaudeService.php # AI Arabic content generation
├── resources/
│   ├── views/                     # Arabic RTL Blade templates
│   └── lang/ar/                   # Arabic language files
├── database/
│   └── migrations/                # UTF-8 schema definitions
├── public/
│   └── css/                       # RTL-optimized stylesheets
└── routes/
    └── web.php                    # Arabic route definitions
```

---

_This documentation reflects the current Arabic-only interface implementation while maintaining support for multilingual educational content creation._
