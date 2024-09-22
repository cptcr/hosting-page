<?php
require 'vendor/autoload.php';

$Parsedown = new Parsedown();
$dir = __DIR__ . '/posts/';
$filename = isset($_GET['post']) ? $_GET['post'] : '';

if ($filename && file_exists($dir . $filename . '.md')) {
    $postContent = file_get_contents($dir . $filename . '.md');
    
    // Parse YAML front-matter
    preg_match('/---(.*?)---/s', $postContent, $metaMatch);
    if ($metaMatch) {
        $postContent = preg_replace('/---(.*?)---/s', '', $postContent);  // Remove meta
    }
    
    // Parse Markdown
    $htmlContent = $Parsedown->text($postContent);
} else {
    $htmlContent = 'Post not found.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
</head>
<body>
    <div class="post-content">
        <?php echo $htmlContent; ?>
    </div>
    <a href="index.php">Back to Blog</a>
</body>
</html>
