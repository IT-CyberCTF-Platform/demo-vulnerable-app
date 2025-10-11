<?php
define('SAFE_DIR', __DIR__ . '/demo_files');

if (!is_dir(SAFE_DIR)) {
    mkdir(SAFE_DIR, 0775, true);
}
file_put_contents(SAFE_DIR . '/song.txt', "Twinkle, twinkle, little star.");
file_put_contents(SAFE_DIR . '/secret.txt', "This is a secret file within the allowed directory.");
file_put_contents(__DIR__ . '/system_log.txt', "SYSTEM LOG: An attacker should not see this!");

function displayFileContent(string $title, string $path): void
{
    echo "<h3>{$title}</h3>";
    echo "<p>Path: " . htmlspecialchars($path) . "</p>";
    if (is_file($path)) {
        $content = file_get_contents($path);
        echo "<div style='background:#f0f0f0; border:1px solid #ccc; padding:10px; white-space:pre-wrap;'>";
        echo htmlspecialchars($content);
        echo "</div>";
    } else {
        echo "<p style='color:red;'>ERROR: File not found.</p>";
    }
    echo "<hr>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dir Traversal Demo</title>
</head>
<body>
    <div style="background:#ffebee; border:1px solid #f44336; padding:15px;">
        <h2>⚠️ FOR EDUCATIONAL PURPOSES ONLY ⚠️</h2>
        <p>This script contains <strong>intentional security vulnerabilities</strong>. Do not use on a live server.</p>
    </div>

    <h1>Directory Traversal Demo</h1>
    <p>Try: <code>song.txt</code>, <code>secret.txt</code>, or <code>../system_log.txt</code></p>

    <form method="GET" action="">
        <label for="file"><strong>Filename:</strong></label><br>
        <input type="text" id="file" name="file" value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : 'song.txt'; ?>">
        <button type="submit">Read</button>
    </form>

    <hr>

    <?php
    if (isset($_GET['file']) && !empty($_GET['file'])) {
        $userInput = $_GET['file'];
        echo "<h2>Input: <code>" . htmlspecialchars($userInput) . "</code></h2>";

        $vulnerablePath = SAFE_DIR . '/' . $userInput;
        displayFileContent("Vulnerable", $vulnerablePath);

        $safeFilename = basename($userInput);
        $securePath = SAFE_DIR . '/' . $safeFilename;
        displayFileContent("Secure (basename)", $securePath);

        $realBasePath = realpath(SAFE_DIR);
        $userPath = realpath(SAFE_DIR . '/' . $userInput);

        echo "<h3>Secure (realpath)</h3>";
        if ($userPath !== false && strpos($userPath, $realBasePath) === 0) {
            echo "<p style='color:green;'>VALIDATION PASSED</p>";
            $content = file_get_contents($userPath);
            echo "<div style='background:#f0f0f0; border:1px solid #ccc; padding:10px; white-space:pre-wrap;'>";
            echo htmlspecialchars($content);
            echo "</div>";
        } else {
            echo "<p style='color:red;'>VALIDATION FAILED: Access denied.</p>";
        }
    } else {
        echo "<hr><p>Enter a filename to see the demo.</p>";
    }
    ?>
</body>
</html>
