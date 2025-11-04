<?php
// api.php

// Enable CORS for the frontend to be able to communicate
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Handle pre-flight OPTIONS request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// We MUST use sessions to store the process resource between requests
session_start();

// Set content type to JSON for all responses
header('Content-Type: application/json');

/**
 * Helper function to send a JSON response and exit
 */
function send_response($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data);
    exit;
}

// Get the requested action from the URL, e.g., /api.php?action=start
 $action = $_GET['action'] ?? '';

switch ($action) {
    case 'start':
        // If a process is already running, end it first
        if (isset($_SESSION['process'])) {
            proc_terminate($_SESSION['process']);
            proc_close($_SESSION['process']);
            unset($_SESSION['process'], $_SESSION['pipes']);
        }

        // Use proc_open to start a shell process
        // We use /bin/bash for a common, interactive shell
        $descriptorspec = [
            0 => ['pipe', 'r'],  // stdin is a pipe that the child will read from
            1 => ['pipe', 'w'],  // stdout is a pipe that the child will write to
            2 => ['pipe', 'w']   // stderr is a pipe that the child will write to
        ];

        // IMPORTANT: Run this as a non-privileged user!
        $process = proc_open('/bin/bash', $descriptorspec, $pipes);

        if (!is_resource($process)) {
            send_response(['error' => 'Could not start shell process.'], 500);
        }

        // Store the process and its pipes in the session
        $_SESSION['process'] = $process;
        $_SESSION['pipes'] = $pipes;

        // Set streams to non-blocking mode
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        send_response(['status' => 'Shell started.']);
        break;

    case 'command':
        if (!isset($_SESSION['process']) || !is_resource($_SESSION['process'])) {
            send_response(['error' => 'No active shell session. Please start a new one.'], 400);
        }

        // Get the command from the POST body
        $input = json_decode(file_get_contents('php://input'), true);
        $command = $input['command'] ?? '';

        if (empty($command)) {
            send_response(['error' => 'Command cannot be empty.'], 400);
        }

        // Write the command to the shell's stdin
        // IMPORTANT: This is where command injection happens. Sanitize if you must.
        // For this example, we are passing it directly.
        fwrite($_SESSION['pipes'][0], $command . PHP_EOL);

        // Read output from stdout and stderr without blocking
        $output = '';
        $error_output = '';

        // Use stream_select to wait for data to be available
        $read = [$_SESSION['pipes'][1], $_SESSION['pipes'][2]];
        $write = null;
        $except = null;
        
        // Wait for up to 0.2 seconds for output
        if (stream_select($read, $write, $except, 0, 200000)) {
            foreach ($read as $stream) {
                if ($stream === $_SESSION['pipes'][1]) {
                    $output .= stream_get_contents($stream);
                }
                if ($stream === $_SESSION['pipes'][2]) {
                    $error_output .= stream_get_contents($stream);
                }
            }
        }
        
        // A small delay to allow the command to finish and produce output
        // This is a simple but not perfect way to handle command timing
        usleep(50000); // 50ms

        // Read any remaining output
        $output .= stream_get_contents($_SESSION['pipes'][1]);
        $error_output .= stream_get_contents($_SESSION['pipes'][2]);

        send_response([
            'output' => $output,
            'error' => $error_output
        ]);
        break;

    case 'end':
        if (isset($_SESSION['process']) && is_resource($_SESSION['process'])) {
            // Close all pipes
            foreach ($_SESSION['pipes'] as $pipe) {
                fclose($pipe);
            }
            // Terminate the process and close it
            proc_terminate($_SESSION['process']);
            proc_close($_SESSION['process']);
        }
        
        // Unset session variables
        unset($_SESSION['process'], $_SESSION['pipes']);
        session_destroy();

        send_response(['status' => 'Shell session ended.']);
        break;

    default:
        send_response(['error' => 'Invalid action.'], 404);
        break;
}
