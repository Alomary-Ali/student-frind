<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sessions = DB::table('sessions')->get();
echo "Total Active Sessions: " . $sessions->count() . "\n";

foreach ($sessions as $session) {
    echo "-----------------------------------------\n";
    echo "Session ID: " . $session->id . "\n";
    echo "User ID: " . $session->user_id . "\n";
    echo "Last Activity: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
    
    if ($session->user_id) {
        $user = DB::table('users')->where('id', $session->user_id)->first();
        if ($user) {
            echo "Logged in User: " . $user->email . "\n";
            echo "Role: " . $user->role . "\n";
            $student = DB::table('academic_students')->where('user_id', $user->id)->first();
            echo "Student profile: " . ($student ? "FOUND (ID: {$student->id}, Status: {$student->academic_status})" : "NOT FOUND") . "\n";
        } else {
            echo "User not found in database!\n";
        }
    } else {
        // Let's decode session payload to see if auth info is in there
        $payload = unserialize(base64_decode($session->payload));
        echo "Session Keys: " . implode(', ', array_keys($payload)) . "\n";
        if (isset($payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'])) {
            $userId = $payload['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
            echo "Auth User ID from payload: $userId\n";
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user) {
                echo "Payload User: " . $user->email . "\n";
                echo "Role: " . $user->role . "\n";
            }
        }
    }
}
