<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Composer autoload

class PusherManager {
    private $pusher;
    
    public function __construct() {
        $this->pusher = new Pusher\Pusher(
            PUSHER_KEY,
            PUSHER_SECRET,
            PUSHER_APP_ID,
            [
                'cluster' => PUSHER_CLUSTER,
                'useTLS' => true
            ]
        );
    }
    
    public function triggerSessionStart($moduleId, $sessionData) {
        return $this->pusher->trigger("module-{$moduleId}", 'session-started', $sessionData);
    }
    
    public function triggerTransaction($userId, $transactionData) {
        return $this->pusher->trigger("user-{$userId}", 'transaction-update', $transactionData);
    }
    
    public function triggerCharityUpdate() {
        return $this->pusher->trigger('global', 'charities-updated', []);
    }
}
?>