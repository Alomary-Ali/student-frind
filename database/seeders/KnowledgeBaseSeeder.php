<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentFaqItem;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentKnowledgeArticle;
use Modules\StudentServices\Infrastructure\Persistence\Eloquent\EloquentKnowledgeCategory;

final class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedKnowledgeCategories();
        $this->seedKnowledgeArticles();
        $this->seedFaqItems();
    }

    private function seedKnowledgeCategories(): void
    {
        $categories = [
            [
                'name' => 'التسجيل',
                'slug' => 'registration',
                'description' => 'مقالات وإرشادات حول التسجيل في المقررات',
                'parent_id' => null,
                'sort_order' => 1,
            ],
            [
                'name' => 'الجدول الدراسي',
                'slug' => 'schedule',
                'description' => 'معلومات حول الجدول الدراسي وتعديله',
                'parent_id' => null,
                'sort_order' => 2,
            ],
            [
                'name' => 'الامتحانات',
                'slug' => 'exams',
                'description' => 'معلومات حول الامتحانات والتقييم',
                'parent_id' => null,
                'sort_order' => 3,
            ],
            [
                'name' => 'الخدمات الإدارية',
                'slug' => 'administrative-services',
                'description' => 'الخدمات الإدارية المتاحة للطلاب',
                'parent_id' => null,
                'sort_order' => 4,
            ],
            [
                'name' => 'الدعم الفني',
                'slug' => 'technical-support',
                'description' => 'الدعم الفني وحل المشكلات التقنية',
                'parent_id' => null,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            EloquentKnowledgeCategory::firstOrCreate(
                ['slug' => $category['slug']],
                array_merge($category, ['id' => (string) Str::uuid()]),
            );
        }
    }

    private function seedKnowledgeArticles(): void
    {
        $categories = EloquentKnowledgeCategory::all();

        $articlesByCategory = [
            'registration' => [
                [
                    'title' => 'كيفية التسجيل في المقررات',
                    'slug' => 'how-to-register-courses',
                    'content' => 'يتم التسجيل في المقررات من خلال بوابة الطالب. يجب التأكد من استيفاء المتطلبات المسبقة قبل التسجيل. يمكن التسجيل خلال فترة التسجيل المحددة في التقويم الأكاديمي.',
                    'tags' => ['تسجيل', 'مقررات', 'دليل'],
                    'status' => 'published',
                ],
                [
                    'title' => 'المتطلبات المسبقة للتسجيل',
                    'slug' => 'registration-prerequisites',
                    'content' => 'لكل مقرر متطلبات مسبقة يجب إكمالها قبل التسجيل. يمكن عرض المتطلبات من خلال صفحة المقرر في بوابة الطالب.',
                    'tags' => ['تسجيل', 'متطلبات', 'مقررات'],
                    'status' => 'published',
                ],
            ],
            'schedule' => [
                [
                    'title' => 'تعديل الجدول الدراسي',
                    'slug' => 'modify-schedule',
                    'content' => 'يمكن تعديل الجدول الدراسي خلال فترة الإضافة والحذف. يجب مراجعة المرشد الأكاديمي قبل إجراء أي تعديلات جوهرية على الجدول.',
                    'tags' => ['جدول', 'تعديل', 'إضافة', 'حذف'],
                    'status' => 'published',
                ],
                [
                    'title' => 'فترة الإضافة والحذف',
                    'slug' => 'add-drop-period',
                    'content' => 'فترة الإضافة والحذف هي الفترة المحددة في التقويم الأكاديمي التي يمكن للطلاب خلالها تعديل جدولهم الدراسي.',
                    'tags' => ['جدول', 'إضافة', 'حذف', 'فترة'],
                    'status' => 'published',
                ],
            ],
            'exams' => [
                [
                    'title' => 'الإجراءات في حال الغياب عن الامتحان',
                    'slug' => 'exam-absence-procedures',
                    'content' => 'في حال الغياب عن الامتحان بعذر مقبول، يجب تقديم طلب رسمي مع الوثائق المؤيدة خلال 3 أيام من تاريخ الامتحان. يتم مراجعة الطلب من قبل لجنة الامتحانات.',
                    'tags' => ['امتحانات', 'غياب', 'عذر', 'إجراءات'],
                    'status' => 'published',
                ],
                [
                    'title' => 'جدول الامتحانات النهائية',
                    'slug' => 'final-exam-schedule',
                    'content' => 'يتم نشر جدول الامتحانات النهائية قبل أسبوعين من بدء الامتحانات. يمكن الاطلاع عليه من خلال بوابة الطالب.',
                    'tags' => ['امتحانات', 'جدول', 'نهائي'],
                    'status' => 'published',
                ],
            ],
            'administrative-services' => [
                [
                    'title' => 'طلب إثبات قيد دراسي',
                    'slug' => 'request-enrollment-certificate',
                    'content' => 'يمكن طلب إثبات قيد دراسي من خلال بوابة الخدمات الإدارية. يستغرق إصدار الشهادة عادة 3-5 أيام عمل. يمكن استلامها من شؤون الطلاب أو تحميلها إلكترونياً.',
                    'tags' => ['إثبات قيد', 'شهادة', 'خدمات', 'إدارية'],
                    'status' => 'published',
                ],
                [
                    'title' => 'طلب تأجيل دراسي',
                    'slug' => 'request-academic-deferral',
                    'content' => 'يمكن للطالب التأجيل الدراسي لفصل دراسي واحد كحد أقصى خلال العام الدراسي. يجب تقديم طلب التأجيل قبل بداية الفصل بمدة لا تقل عن أسبوعين.',
                    'tags' => ['تأجيل', 'خدمات', 'إدارية'],
                    'status' => 'published',
                ],
            ],
            'technical-support' => [
                [
                    'title' => 'استخدام نظام رفيق الطالب',
                    'slug' => 'using-student-friend-system',
                    'content' => 'نظام رفيق الطالب هو منصة شاملة لإدارة الشؤون الأكاديمية. يتضمن الجدول الدراسي، الدرجات، المهام، والمزيد. يمكن الوصول إليه من أي جهاز متصل بالإنترنت.',
                    'tags' => ['رفيق الطالب', 'نظام', 'دليل', 'استخدام'],
                    'status' => 'published',
                ],
                [
                    'title' => 'استعادة كلمة المرور',
                    'slug' => 'password-recovery',
                    'content' => 'يمكن استعادة كلمة المرور من خلال رابط "نسيت كلمة المرور" في صفحة تسجيل الدخول. سيتم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني المسجل.',
                    'tags' => ['دعم فني', 'كلمة مرور', 'استعادة'],
                    'status' => 'published',
                ],
            ],
        ];

        foreach ($categories as $category) {
            $articles = $articlesByCategory[$category->slug] ?? [];

            foreach ($articles as $article) {
                EloquentKnowledgeArticle::firstOrCreate(
                    ['slug' => $article['slug']],
                    [
                        'id' => (string) Str::uuid(),
                        'category_id' => $category->id,
                        'title' => $article['title'],
                        'content' => $article['content'],
                        'tags' => json_encode($article['tags']),
                        'status' => $article['status'],
                        'view_count' => 0,
                    ],
                );
            }
        }
    }

    private function seedFaqItems(): void
    {
        $categories = EloquentKnowledgeCategory::all();

        $faqs = [
            [
                'question' => 'متى تبدأ فترة التسجيل؟',
                'answer' => 'تبدأ فترة التسجيل عادة في الأسبوع الأخير من الفصل السابق. يتم الإعلان عن التواريخ المحددة عبر البريد الإلكتروني وبوابة الطالب.',
                'sort_order' => 1,
            ],
            [
                'question' => 'كيف يمكنني الحصول على كشف درجات؟',
                'answer' => 'يمكن طلب كشف درجات من خلال بوابة الخدمات الإدارية. الكشف متاح إلكترونياً مجاناً، ويمكن طلب نسخة ورقية رسمية مقابل رسوم رمزية.',
                'sort_order' => 2,
            ],
            [
                'question' => 'ما هي شروط التخرج؟',
                'answer' => 'شروط التخرج تشمل: إكمال جميع المقررات المطلوبة في الخطة الدراسية، الحصول على معدل تراكمي لا يقل عن 2.0، وإكمال جميع المتطلبات الجامعية الأخرى.',
                'sort_order' => 3,
            ],
            [
                'question' => 'كيف يمكنني تغيير تخصصي؟',
                'answer' => 'يمكن تغيير التخصص بتقديم طلب رسمي خلال فترة محددة في بداية الفصل الدراسي. يجب استشارة المرشد الأكاديمي قبل تقديم الطلب.',
                'sort_order' => 4,
            ],
            [
                'question' => 'ماذا أفعل إذا نسيت كلمة المرور؟',
                'answer' => 'يمكن استعادة كلمة المرور من خلال رابط "نسيت كلمة المرور" في صفحة تسجيل الدخول. سيتم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني المسجل.',
                'sort_order' => 5,
            ],
            [
                'question' => 'كيف يمكنني التواصل مع المرشد الأكاديمي؟',
                'answer' => 'يمكن التواصل مع المرشد الأكاديمي من خلال حجز موعد عبر بوابة الطالب، أو إرسال بريد إلكتروني، أو زيارة مكتب المرشد خلال ساعات العمل الرسمية.',
                'sort_order' => 6,
            ],
            [
                'question' => 'هل يمكنني دراسة مقررات في فصل الصيف؟',
                'answer' => 'نعم، يمكن دراسة مقررات في فصل الصيف ضمن الحد الأقصى المسموح به (عادة 6 ساعات معتمدة). يجب التسجيل قبل بداية الفصل الصيف.',
                'sort_order' => 7,
            ],
            [
                'question' => 'كيف يمكنني تقديم شكوى؟',
                'answer' => 'يمكن تقديم الشكاوى من خلال بوابة الخدمات الإدارية أو مراجعة مكتب شؤون الطلاب مباشرة. يتم مراجعة جميع الشكاوى والرد عليها خلال 5 أيام عمل.',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            foreach ($faqs as $faq) {
                EloquentFaqItem::firstOrCreate(
                    ['category_id' => $category->id, 'question' => $faq['question']],
                    [
                        'id' => (string) Str::uuid(),
                        'answer' => $faq['answer'],
                        'sort_order' => $faq['sort_order'],
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
