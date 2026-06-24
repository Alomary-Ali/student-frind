<?php

declare(strict_types=1);

return [
    // General
    'title' => 'الإشعارات',
    'description' => 'جميع الإشعارات الخاصة بك',

    // Types
    'types' => [
        'info' => 'معلومة',
        'success' => 'نجاح',
        'warning' => 'تحذير',
        'error' => 'خطأ',
    ],

    // Channels
    'channels' => [
        'in_app' => 'داخل التطبيق',
        'email' => 'البريد الإلكتروني',
        'sms' => 'الرسائل النصية',
    ],

    // Actions
    'mark_as_read' => 'تحديد كمقروء',
    'mark_all_as_read' => 'تحديد الكل كمقروء',
    'delete' => 'حذف',
    'view_details' => 'عرض التفاصيل',

    // Messages
    'messages' => [
        'no_notifications' => 'لا توجد إشعارات جديدة',
        'notification_marked_read' => 'تم تحديد الإشعار كمقروء',
        'all_marked_read' => 'تم تحديد جميع الإشعارات كمقروء',
        'notification_deleted' => 'تم حذف الإشعار',
    ],

    // Service Request Notifications
    'service_request' => [
        'submitted' => 'تم تقديم طلبك بنجاح',
        'approved' => 'تم اعتماد طلبك',
        'rejected' => 'تم رفض طلبك',
        'completed' => 'تم إكمال طلبك',
        'cancelled' => 'تم إلغاء طلبك',
        'under_review' => 'طلبك قيد المراجعة',
    ],

    // Document Notifications
    'document' => [
        'generated' => 'تم إنشاء مستندك',
        'verified' => 'تم التحقق من مستندك',
        'ready' => 'مستندك جاهز للتحميل',
    ],

    // Academic Notifications
    'academic' => [
        'grade_posted' => 'تم نشر درجتك',
        'exam_scheduled' => 'تم جدولة امتحان جديد',
        'assignment_due' => 'واجبك يستحق قريباً',
        'enrollment_open' => 'فتح التسجيل للمقررات',
    ],

    // System Notifications
    'system' => [
        'maintenance' => 'صيانة النظام المجدولة',
        'update' => 'تحديث النظام',
        'announcement' => 'إعلان هام',
    ],

    // Empty States
    'empty' => [
        'title' => 'لا توجد إشعارات',
        'description' => 'ستظهر الإشعارات هنا عند توفرها',
    ],

    // Counters
    'count' => [
        'one' => 'إشعار واحد',
        'two' => 'إشعاران',
        'few' => ':count إشعارات',
        'many' => ':count إشعار',
        'other' => ':count إشعار',
    ],
];
