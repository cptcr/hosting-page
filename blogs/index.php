<?php
require 'vendor/autoload.php';
$dir = __DIR__ . '/posts/';
$files = glob($dir . '*.md');

function getPostData($file) {
    $content = file_get_contents($file);
    // Parse YAML front-matter for metadata (title, description, date)
    preg_match('/---(.*?)---/s', $content, $metaMatch);
    $meta = [];
    if ($metaMatch) {
        $metaData = explode("\n", trim($metaMatch[1]));
        foreach ($metaData as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $meta[trim($parts[0])] = trim($parts[1]);
            }
        }
    }
    
    // Post content (everything after front-matter)
    $content = preg_replace('/---(.*?)---/s', '', $content);
    
    return [
        'title' => $meta['title'] ?? 'No title',
        'description' => substr($meta['description'], 0, 128) ?? 'No description',
        'date' => $meta['date'] ?? 'Unknown date',
        'content' => $content,
        'filename' => basename($file, '.md')
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
</head>
<body>
    <h1>Blog Posts</h1>
    <div class="posts">
        <?php foreach ($files as $file): 
            $post = getPostData($file); ?>
            <div class="post-preview">
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo htmlspecialchars($post['description']); ?></p>
                <small><?php echo htmlspecialchars($post['date']); ?></small>
                <a href="post.php?post=<?php echo urlencode($post['filename']); ?>">Read More</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
