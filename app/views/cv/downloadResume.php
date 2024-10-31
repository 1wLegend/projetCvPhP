<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cv_id']) && isset($_POST['user_id'])) {
    $cvId = $_POST['cv_id'];
    $userId = $_POST['user_id'];
    
    if (empty($cvId) || empty($userId)) {
        error_log('cv_id or user_id is empty');
        header('Location: /dashboard');
        exit;
    }

    $pdo = getPdo();
    $query = $pdo->prepare('SELECT * FROM resumes WHERE cv_id = :cv_id AND user_id = :user_id');
    $query->execute(['cv_id' => $cvId, 'user_id' => $userId]);
    $cv = $query->fetch();

    if ($cv) {
        try {
            $pdfContent = $member->downloadResumeAsPdfForThisId($cv['cv_id'], $cv['user_id']);
            
            // Set headers to force download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="cv_' . $cvId . '.pdf"');
            header('Content-Length: ' . strlen($pdfContent));
            
            // Output the PDF content
            echo $pdfContent;
            exit;
        } catch (Exception $e) {
            error_log('PDF generation failed: ' . $e->getMessage());
            header('Location: /home');
            exit;
        }
    } else {
        error_log('No CV found for cv_id: ' . $cvId . ' and user_id: ' . $userId);
        header('Location: /home');
        exit;
    }
} else {
    error_log('Invalid request method or cv_id/user_id not set');
    header('Location: /login');
    exit;
}
