<?php
function showAlert($errorCode, $message, $redirectTo = null, $context = null) {
    $_SESSION['alert'] = [
        'code' => $errorCode,
        'message' => $message,
        'context' => $context 
    ];

    if ($redirectTo) {
        header("Location: $redirectTo");
        exit();
    }
}

function displayAlert($currentContext = null) {
    if (isset($_SESSION['alert']) && !empty($_SESSION['alert']['code']) && !empty($_SESSION['alert']['message'])) {
        $alert = $_SESSION['alert'];


        if ($currentContext && $alert['context'] === $currentContext) {
            unset($_SESSION['alert']); 

            echo "
            <div class='fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white p-4 rounded shadow-lg z-50'>
                <p><strong>Error {$alert['code']}:</strong> {$alert['message']}</p>
            </div>
            <script>
                setTimeout(() => {
                    const alertBox = document.querySelector('.fixed.top-4.left-1/2');
                    if (alertBox) alertBox.style.display = 'none';
                }, 5000); // Ocultar la notificación después de 5 segundos
            </script>
            ";
        }
    }
}
?>
