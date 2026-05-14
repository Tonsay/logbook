<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_GET['id'])) {
    echo '<div style="text-align: center; color: #ff4d4d;">Error: No document ID provided.</div>';
    exit;
}

$doc_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM history_tb WHERE document_id = ? ORDER BY action_date DESC");
$stmt->bind_param("s", $doc_id);
$stmt->execute();
$history_logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($history_logs)) {
    echo '<div style="text-align: center; color: #aaa; padding: 20px; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px dashed rgba(255,255,255,0.1);">No history records found for this document yet.</div>';
} else {
    foreach ($history_logs as $log) {
        $date = date('F j, Y - g:i A', strtotime($log['action_date']));
        $action = htmlspecialchars($log['action_type']);
        $user = htmlspecialchars($log['performed_by']);
        $details = htmlspecialchars($log['action_details']);

      
        echo '<div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 15px; margin-bottom: 12px;">';
        echo '<div style="color: #00A5EF; font-size: 11px; font-weight: bold; margin-bottom: 5px;">🗓️ ' . $date . '</div>';
        echo '<div style="color: white; font-size: 15px; font-weight: bold; margin-bottom: 5px;">' . $action . '</div>';
        echo '<div style="color: #b9e6ff; font-size: 12px; margin-bottom: 8px;">👤 Performed by: <strong>' . $user . '</strong></div>';
        
        if (!empty($details)) {
            echo '<div style="color: #aaa; font-size: 12px; background: rgba(0,0,0,0.2); padding: 8px 10px; border-radius: 6px;">' . $details . '</div>';
        }
        echo '</div>';
    }
}
?>