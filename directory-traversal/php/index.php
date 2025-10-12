<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureForum - Directory Traversal Demo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
            background-color: #f5f5f5;
        }
        .forum-post {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            background-color: #fff;
        }
        .forum-category {
            margin-bottom: 20px;
        }
        .vulnerability-info {
            background-color: #f8f8f8;
            border-left: 4px solid #f0ad4e;
            padding: 15px;
            margin: 20px 0;
        }
        .terminal {
            background-color: #222;
            color: #00ff00;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            margin: 15px 0;
            overflow-x: auto;
        }
        .file-content {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            white-space: pre-wrap;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
        }
        .nav-tabs {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="#">SecureForum</a>
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#vulnerability">Vulnerability Info</a></li>
                    <li><a href="#prevention">Prevention</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="span8">
                <h1>SecureForum - Chat & Discussion</h1>
                <p class="lead">Welcome to our educational forum demonstrating directory traversal vulnerabilities.</p>
                
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This application intentionally contains a directory traversal vulnerability for educational purposes only.
                </div>

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#forum" data-toggle="tab">Forum</a></li>
                    <li><a href="#file-viewer" data-toggle="tab">File Viewer</a></li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane active" id="forum">
                        <div class="forum-category">
                            <h3>General Discussion</h3>
                            <div class="forum-post">
                                <h4>Welcome to SecureForum!</h4>
                                <p>Posted by: Admin | Date: 2023-05-15</p>
                                <p>Welcome to our educational forum. This platform demonstrates various security vulnerabilities for learning purposes.</p>
                            </div>
                            <div class="forum-post">
                                <h4>How to use the File Viewer</h4>
                                <p>Posted by: Moderator | Date: 2023-05-16</p>
                                <p>Check out the File Viewer tab to access forum files. Try accessing different files using the file parameter.</p>
                            </div>
                        </div>
                        
                        <div class="forum-category">
                            <h3>Security Discussions</h3>
                            <div class="forum-post">
                                <h4>Understanding Directory Traversal</h4>
                                <p>Posted by: SecurityExpert | Date: 2023-05-17</p>
                                <p>Directory traversal is a security vulnerability that allows attackers to access files and directories outside the intended directory.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="file-viewer">
                        <h3>Forum File Viewer</h3>
                        <p>Access forum files by specifying the file name in the URL parameter.</p>
                        
                        <div class="well">
                            <form method="GET" action="">
                                <input type="hidden" name="tab" value="file-viewer">
                                <div class="input-append">
                                    <input type="text" name="file" class="span6" placeholder="Enter file name (e.g., posts.txt)" 
                                           value="<?php echo isset($_GET['file']) ? htmlspecialchars($_GET['file']) : ''; ?>">
                                    <button type="submit" class="btn">View File</button>
                                </div>
                            </form>
                        </div>
                        
                        <?php
                        if (isset($_GET['file'])) {
                            $file = $_GET['file'];
                            $baseDir = './forum_files/';
                            $filePath = $baseDir . $file;
                            
                            // VULNERABLE CODE: No validation of file path
                            // This allows directory traversal attacks
                            if (file_exists($filePath)) {
                                echo '<h4>Content of: ' . htmlspecialchars($file) . '</h4>';
                                echo '<div class="file-content">' . htmlspecialchars(file_get_contents($filePath)) . '</div>';
                            } else {
                                echo '<div class="alert alert-error">File not found: ' . htmlspecialchars($file) . '</div>';
                            }
                            
                            // Show the actual path being accessed (for educational purposes)
                            echo '<div class="vulnerability-info">';
                            echo '<h5>Debug Information:</h5>';
                            echo '<p>Attempting to access: ' . htmlspecialchars($filePath) . '</p>';
                            echo '<p>Current working directory: ' . htmlspecialchars(getcwd()) . '</p>';
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="vulnerability-info">
                            <h4>Try These Directory Traversal Payloads:</h4>
                            <ul>
                                <li><code>../index.php</code> - Access the main PHP file</li>
                                <li><code>../../../../etc/passwd</code> - Try to access system files (will fail on most systems)</li>
                                <li><code>../forum_files/secret.txt</code> - Access a file in a different directory</li>
                                <li><code>./forum_files/posts.txt</code> - Access a file using relative path</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="span4">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li class="nav-header">Quick Links</li>
                        <li><a href="#vulnerability">About Directory Traversal</a></li>
                        <li><a href="#prevention">Prevention Techniques</a></li>
                        <li class="nav-header">Sample Files</li>
                        <li><a href="?tab=file-viewer&file=posts.txt">View posts.txt</a></li>
                        <li><a href="?tab=file-viewer&file=users.txt">View users.txt</a></li>
                        <li><a href="?tab=file-viewer&file=config.txt">View config.txt</a></li>
                    </ul>
                </div>
                
                <div class="vulnerability-info">
                    <h4>Directory Traversal</h4>
                    <p>Directory traversal (also known as path traversal) is a web security vulnerability that allows an attacker to read arbitrary files on the server.</p>
                    <p>In this demo, the file viewer is vulnerable to directory traversal attacks.</p>
                </div>
            </div>
        </div>
        
        <div id="vulnerability" class="row">
            <div class="span12">
                <h2>Understanding Directory Traversal Vulnerability</h2>
                
                <div class="row">
                    <div class="span6">
                        <h3>What is Directory Traversal?</h3>
                        <p>Directory traversal is a vulnerability where an application uses user-supplied input to construct file paths without proper validation. This allows attackers to access files and directories outside the intended directory.</p>
                        
                        <h3>How it Works</h3>
                        <p>The vulnerability occurs when an application uses user input to access files without sanitizing it. Attackers can use sequences like "../" to move up in the directory structure.</p>
                        
                        <h3>Common Payloads</h3>
                        <ul>
                            <li><code>../</code> - Move up one directory</li>
                            <li><code>../../</code> - Move up two directories</li>
                            <li><code>../../../etc/passwd</code> - Access system files</li>
                            <li><code>....//</code> - Bypass simple filters</li>
                            <li><code>%2e%2e%2f</code> - URL encoded version of "../"</li>
                        </ul>
                    </div>
                    
                    <div class="span6">
                        <h3>Vulnerable Code Example</h3>
                        <div class="terminal">
// VULNERABLE CODE
 $baseDir = './forum_files/';
 $file = $_GET['file']; // User input without validation
 $filePath = $baseDir . $file;

if (file_exists($filePath)) {
    echo file_get_contents($filePath);
}
                        </div>
                        
                        <h3>Attack Example</h3>
                        <p>If an attacker provides the input <code>../index.php</code>, the application will try to access <code>./forum_files/../index.php</code>, which is actually the main application file.</p>
                        
                        <div class="terminal">
// Request URL
?file=../index.php

// Resolves to
./forum_files/../index.php

// Which is equivalent to
./index.php
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="prevention" class="row">
            <div class="span12">
                <h2>Prevention Techniques</h2>
                
                <div class="row">
                    <div class="span6">
                        <h3>Input Validation</h3>
                        <p>Validate user input to ensure it only contains allowed characters and doesn't include directory traversal sequences.</p>
                        
                        <div class="terminal">
// SECURE CODE
 $baseDir = './forum_files/';
 $file = $_GET['file'];

// Validate input
if (strpos($file, '../') !== false || 
    strpos($file, '..\\') !== false) {
    die('Invalid file name');
}

// Only allow specific files
 $allowedFiles = ['posts.txt', 'users.txt', 'config.txt'];
if (!in_array($file, $allowedFiles)) {
    die('File not allowed');
}

 $filePath = $baseDir . $file;
                        </div>
                    </div>
                    
                    <div class="span6">
                        <h3>Whitelist Approach</h3>
                        <p>Maintain a whitelist of allowed files and only serve those files.</p>
                        
                        <div class="terminal">
// SECURE CODE - WHITELIST
 $allowedFiles = [
    'posts.txt' => './forum_files/posts.txt',
    'users.txt' => './forum_files/users.txt',
    'config.txt' => './forum_files/config.txt'
];

 $file = $_GET['file'];

if (isset($allowedFiles[$file])) {
    echo file_get_contents($allowedFiles[$file]);
} else {
    die('File not found');
}
                        </div>
                        
                        <h3>Use realpath() Function</h3>
                        <p>The realpath() function resolves all symbolic links and references to '/./' and '/../' in the path.</p>
                        
                        <div class="terminal">
// SECURE CODE - realpath()
 $baseDir = realpath('./forum_files/') . '/';
 $file = $_GET['file'];
 $filePath = realpath($baseDir . $file);

// Check if the resolved path is within the base directory
if (strpos($filePath, $baseDir) !== 0) {
    die('Access denied');
}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="footer">
            <div class="container">
                <p class="muted">SecureForum - Educational Security Demo | For Educational Purposes Only</p>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
    
    <script>
        // Create some sample files for the demo
        $(document).ready(function() {
            // This would normally be done server-side, but for this demo we'll simulate it
            console.log("Directory Traversal Demo Loaded");
            
            // Add smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if( target.length ) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 50
                    }, 1000);
                }
            });
        });
    </script>
    
    <?php
    // Create sample files for the demo if they don't exist
    $forumDir = './forum_files/';
    if (!is_dir($forumDir)) {
        mkdir($forumDir, 0755, true);
    }
    
    // Create posts.txt
    if (!file_exists($forumDir . 'posts.txt')) {
        file_put_contents($forumDir . 'posts.txt', "Forum Posts Data\n================\n\n1. Welcome to SecureForum!\n   Posted by: Admin\n   Date: 2023-05-15\n   Content: Welcome to our educational forum.\n\n2. How to use the File Viewer\n   Posted by: Moderator\n   Date: 2023-05-16\n   Content: Check out the File Viewer tab.\n\n3. Understanding Directory Traversal\n   Posted by: SecurityExpert\n   Date: 2023-05-17\n   Content: Directory traversal is a security vulnerability.");
    }
    
    // Create users.txt
    if (!file_exists($forumDir . 'users.txt')) {
        file_put_contents($forumDir . 'users.txt', "User Database\n============\n\nUsername: admin\nEmail: admin@secureforum.com\nRole: Administrator\n\nUsername: moderator\nEmail: mod@secureforum.com\nRole: Moderator\n\nUsername: securityexpert\nEmail: expert@secureforum.com\nRole: Security Expert");
    }
    
    // Create config.txt
    if (!file_exists($forumDir . 'config.txt')) {
        file_put_contents($forumDir . 'config.txt', "Forum Configuration\n==================\n\nDatabase: localhost\nUsername: forum_user\nPassword: p@ssw0rd123\n\nAPI Key: sk-1234567890abcdef\n\nSecret: forum_secret_key_2023");
    }
    
    // Create secret.txt in parent directory
    if (!file_exists('./secret.txt')) {
        file_put_contents('./secret.txt', "SECRET FILE\n==========\n\nThis file should not be accessible through the forum!\n\nIf you can see this, you've successfully exploited the directory traversal vulnerability.\n\nIn a real application, this could contain sensitive information like database credentials, API keys, or other secrets.");
    }
    ?>
</body>
</html>
