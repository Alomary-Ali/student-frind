<?php

declare(strict_types=1);

return [
    // General
    'title' => 'خدمات الطالب',
    'description' => 'جميع الخدمات المتاحة للطلاب',

    // Dashboard
    'dashboard' => [
        'title' => 'لوحة التحكم',
        'welcome' => 'مرحباً بك في خدمات الطالب',
        'total_requests' => 'إجمالي الطلبات',
        'pending_requests' => 'الطلبات المعلقة',
        'completed_requests' => 'الطلبات المكتملة',
        'recent_activity' => 'النشاط الأخير',
    ],

    // Service Requests
    'requests' => [
        'title' => 'طلباتي',
        'create' => 'طلب جديد',
        'list' => 'قائمة الطلبات',
        'show' => 'تفاصيل الطلب',
        'track' => 'تتبع الطلب',
        'ref_number' => 'رقم الطلب',
        'status' => 'الحالة',
        'priority' => 'الأولوية',
        'notes' => 'ملاحظات',
        'admin_notes' => 'ملاحظات الإدارة',
        'created_at' => 'تاريخ التقديم',
        'updated_at' => 'تاريخ التحديث',
        'cancel' => 'إلغاء الطلب',
        'cancel_reason' => 'سبب الإلغاء',
        'submit' => 'تقديم الطلب',
        'update' => 'تحديث الطلب',
        'approve' => 'اعتماد الطلب',
        'reject' => 'رفض الطلب',
        'complete' => 'إكمال الطلب',
        'review' => 'مراجعة الطلب',
    ],

    // Documents
    'documents' => [
        'title' => 'المستندات',
        'request' => 'طلب مستند',
        'list' => 'قائمة المستندات',
        'verify' => 'التحقق من المستند',
        'type' => 'نوع المستند',
        'status' => 'الحالة',
        'file_path' => 'مسار الملف',
        'verification_code' => 'رمز التحقق',
        'download' => 'تحميل المستند',
        'generate' => 'إنشاء المستند',
        'generated' => 'تم الإنشاء',
        'verified' => 'تم التحقق',
        'pending' => 'قيد الانتظار',
        'expired' => 'منتهي الصلاحية',
    ],

    // Knowledge Base
    'knowledge' => [
        'title' => 'قاعدة المعرفة',
        'articles' => 'المقالات',
        'categories' => 'التصنيفات',
        'search' => 'بحث في المقالات',
        'read' => 'قراءة المقال',
        'views' => 'المشاهدات',
        'tags' => 'الوسوم',
        'related' => 'مقالات ذات صلة',
        'published' => 'منشور',
        'draft' => 'مسودة',
        'archived' => 'أرشيف',
    ],

    // FAQ
    'faq' => [
        'title' => 'الأسئلة الشائعة',
        'question' => 'السؤال',
        'answer' => 'الإجابة',
        'category' => 'التصنيف',
        'search' => 'بحث في الأسئلة',
        'helpful' => 'هل كانت الإجابة مفيدة؟',
        'yes' => 'نعم',
        'no' => 'لا',
    ],

    // Assistant
    'assistant' => [
        'title' => 'المساعد الذكي',
        'chat' => 'محادثة',
        'history' => 'سجل المحادثات',
        'new_conversation' => 'محادثة جديدة',
        'send_message' => 'إرسال رسالة',
        'type_message' => 'اكتب رسالتك هنا...',
        'user' => 'أنت',
        'assistant' => 'المساعد',
        'active' => 'نشط',
        'closed' => 'مغلق',
        'archived' => 'أرشيف',
    ],

    // Workflows
    'workflows' => [
        'title' => 'سير العمل',
        'steps' => 'الخطوات',
        'current_step' => 'الخطوة الحالية',
        'next_step' => 'الخطوة التالية',
        'progress' => 'التقدم',
        'completed' => 'مكتمل',
        'in_progress' => 'قيد التنفيذ',
        'pending' => 'قيد الانتظار',
        'assignee' => 'المسؤول',
    ],

    // Services
    'services' => [
        'title' => 'الخدمات',
        'categories' => 'تصنيفات الخدمات',
        'academic' => 'خدمات أكاديمية',
        'administrative' => 'خدمات إدارية',
        'financial' => 'خدمات مالية',
        'housing' => 'خدمات سكنية',
        'health' => 'خدمات صحية',
    ],

    // Status
    'status' => [
        'new' => 'جديد',
        'under_review' => 'قيد المراجعة',
        'approved' => 'معتمد',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
    ],

    // Priority
    'priority' => [
        'low' => 'منخفض',
        'medium' => 'متوسط',
        'high' => 'عالي',
        'urgent' => 'عاجل',
    ],

    // Document Types
    'document_types' => [
        'certificate' => 'شهادة',
        'transcript' => 'كشف درجات',
        'statement' => 'بيان',
        'official_letter' => 'خطاب رسمي',
        'id_card' => 'بطاقة هوية',
    ],

    // Messages
    'messages' => [
        'request_created' => 'تم إنشاء الطلب بنجاح',
        'request_updated' => 'تم تحديث الطلب بنجاح',
        'request_cancelled' => 'تم إلغاء الطلب',
        'request_approved' => 'تم اعتماد الطلب',
        'request_rejected' => 'تم رفض الطلب',
        'request_completed' => 'تم إكمال الطلب',
        'document_generated' => 'تم إنشاء المستند بنجاح',
        'document_verified' => 'تم التحقق من المستند',
        'error_occurred' => 'حدث خطأ، يرجى المحاولة مرة أخرى',
    ],

    // Validation
    'validation' => [
        'required' => 'هذا الحقل مطلوب',
        'invalid' => 'قيمة غير صالحة',
        'max_length' => 'يجب أن لا يتجاوز :max حرف',
        'min_length' => 'يجب أن لا يقل عن :min حرف',
        'email' => 'يرجى إدخال بريد إلكتروني صالح',
        'uuid' => 'يرجى إدخال معرف UUID صالح',
    ],
];
