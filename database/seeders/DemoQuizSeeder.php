<?php
// File: database/seeders/DemoQuizSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;

class DemoQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo teacher account
        $demoTeacher = User::firstOrCreate(
            ['email' => 'demo@juzoor.test'],
            [
                'name' => 'معلم تجريبي',
                'password' => bcrypt('demo123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Create demo quiz about plants
        $demoQuiz = Quiz::create([
            'user_id' => $demoTeacher->id,
            'title' => 'اختبار تجريبي: النباتات',
            'subject' => 'science',
            'grade_level' => 4,
            'settings' => [
                'jawhar' => [
                    ['depth' => 1, 'count' => 2],
                    ['depth' => 2, 'count' => 1]
                ],
                'zihn' => [
                    ['depth' => 1, 'count' => 1],
                    ['depth' => 2, 'count' => 2]
                ],
                'waslat' => [
                    ['depth' => 2, 'count' => 2]
                ],
                'roaya' => [
                    ['depth' => 2, 'count' => 1],
                    ['depth' => 3, 'count' => 1]
                ]
            ],
            'is_demo' => true,
            'is_public' => true,
            'is_active' => true,
            'pin' => 'DEMO01'
        ]);

        // Add passage and questions
        $passage = "النباتات كائنات حية مدهشة تعيش في كل مكان حولنا. تحتاج النباتات إلى الماء والهواء وضوء الشمس لتنمو وتزدهر. تمتص النباتات الماء من التربة عبر جذورها، وتستخدم أوراقها الخضراء لصنع غذائها من ضوء الشمس في عملية تسمى البناء الضوئي. خلال هذه العملية، تأخذ النباتات ثاني أكسيد الكربون من الهواء وتطلق الأكسجين الذي نتنفسه. النباتات مهمة جداً لحياتنا، فهي تعطينا الغذاء والأكسجين وتجعل عالمنا جميلاً بألوانها المختلفة.";

        $questions = [
            // Jawhar - Level 1
            [
                'question' => 'ما هي الأشياء الثلاثة التي تحتاجها النباتات لتنمو؟',
                'options' => ['الماء والتراب والحجارة', 'الماء والهواء وضوء الشمس', 'الماء والسكر والملح', 'الهواء والنار والتراب'],
                'correct_answer' => 'الماء والهواء وضوء الشمس',
                'root_type' => 'jawhar',
                'depth_level' => 1,
                'passage' => $passage,
                'passage_title' => 'النباتات من حولنا'
            ],
            [
                'question' => 'من أين تمتص النباتات الماء؟',
                'options' => ['من الأوراق', 'من الأزهار', 'من الجذور', 'من الساق'],
                'correct_answer' => 'من الجذور',
                'root_type' => 'jawhar',
                'depth_level' => 1
            ],
            // Jawhar - Level 2
            [
                'question' => 'ما اسم العملية التي تصنع فيها النباتات غذاءها؟',
                'options' => ['التنفس', 'البناء الضوئي', 'الهضم', 'النمو'],
                'correct_answer' => 'البناء الضوئي',
                'root_type' => 'jawhar',
                'depth_level' => 2
            ],
            // Zihn - Level 1
            [
                'question' => 'لماذا أوراق النباتات خضراء اللون؟',
                'options' => ['لأنها تحب اللون الأخضر', 'لتصنع غذاءها من ضوء الشمس', 'لأنها مصبوغة', 'لتخيف الحشرات'],
                'correct_answer' => 'لتصنع غذاءها من ضوء الشمس',
                'root_type' => 'zihn',
                'depth_level' => 1
            ],
            // Zihn - Level 2
            [
                'question' => 'كيف تساعد النباتات في تنظيف الهواء؟',
                'options' => ['تمتص الغبار فقط', 'تأخذ ثاني أكسيد الكربون وتطلق الأكسجين', 'تطلق ثاني أكسيد الكربون', 'تمتص الأكسجين فقط'],
                'correct_answer' => 'تأخذ ثاني أكسيد الكربون وتطلق الأكسجين',
                'root_type' => 'zihn',
                'depth_level' => 2
            ],
            [
                'question' => 'ماذا يحدث للنبات إذا لم يحصل على ضوء الشمس؟',
                'options' => ['ينمو بشكل أسرع', 'يصبح لونه أصفر ويموت', 'يتحول إلى شجرة', 'لا يحدث شيء'],
                'correct_answer' => 'يصبح لونه أصفر ويموت',
                'root_type' => 'zihn',
                'depth_level' => 2
            ],
            // Waslat - Level 2
            [
                'question' => 'ما العلاقة بين النباتات والحيوانات؟',
                'options' => ['لا توجد علاقة', 'النباتات تأكل الحيوانات', 'الحيوانات تأكل النباتات والنباتات تعطي الأكسجين', 'الحيوانات تعطي الماء للنباتات'],
                'correct_answer' => 'الحيوانات تأكل النباتات والنباتات تعطي الأكسجين',
                'root_type' => 'waslat',
                'depth_level' => 2
            ],
            [
                'question' => 'كيف ترتبط النباتات بحياة الإنسان اليومية؟',
                'options' => ['لا ترتبط', 'نأكلها ونتنفس الأكسجين منها', 'نستخدمها للعب فقط', 'نستخدمها للزينة فقط'],
                'correct_answer' => 'نأكلها ونتنفس الأكسجين منها',
                'root_type' => 'waslat',
                'depth_level' => 2
            ],
            // Roaya - Level 2
            [
                'question' => 'كيف يمكنك المساعدة في حماية النباتات في مدرستك؟',
                'options' => ['قطع الأوراق', 'سقيها بانتظام وعدم دوسها', 'تركها بدون ماء', 'اللعب بالتراب حولها'],
                'correct_answer' => 'سقيها بانتظام وعدم دوسها',
                'root_type' => 'roaya',
                'depth_level' => 2
            ],
            // Roaya - Level 3
            [
                'question' => 'إذا أردت إنشاء حديقة صغيرة في المنزل، ما أول شيء يجب أن تفكر فيه؟',
                'options' => ['شراء أغلى النباتات', 'اختيار مكان به ضوء شمس كافٍ', 'وضع النباتات في الظلام', 'استخدام الماء المالح'],
                'correct_answer' => 'اختيار مكان به ضوء شمس كافٍ',
                'root_type' => 'roaya',
                'depth_level' => 3
            ]
        ];

        foreach ($questions as $q) {
            Question::create([
                'quiz_id' => $demoQuiz->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'root_type' => $q['root_type'],
                'depth_level' => $q['depth_level'],
                'passage' => $q['passage'] ?? null,
                'passage_title' => $q['passage_title'] ?? null,
            ]);
        }

        $this->command->info('Demo quiz created successfully!');
        $this->command->info('Demo PIN: DEMO01');
    }
}

// =====================================
// File: database/seeders/DatabaseSeeder.php (Update to include demo seeder)

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DemoQuizSeeder::class,
        ]);
    }
}