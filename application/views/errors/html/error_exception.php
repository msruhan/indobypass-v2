<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Exception Error</title>
    <style type="text/css">
        body { background-color: #fff; color: #333; font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #c00; }
        .container { border: 1px solid #ccc; padding: 20px; background: #f9f9f9; }
        .message { margin-top: 20px; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>An uncaught Exception was encountered</h1>
        <div class="message">
            <p><?php echo isset($message) ? $message : 'A PHP Error was encountered.'; ?></p>
            <p><strong>Severity:</strong> <?php echo isset($severity) ? $severity : ''; ?></p>
            <p><strong>Message:</strong> <?php echo isset($message) ? $message : ''; ?></p>
            <p><strong>Filename:</strong> <?php echo isset($filepath) ? $filepath : ''; ?></p>
            <p><strong>Line Number:</strong> <?php echo isset($line) ? $line : ''; ?></p>
        </div>
    </div>
</body>
</html>
