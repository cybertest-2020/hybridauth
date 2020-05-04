<?php
/**
 * Build a simple HTML page with multiple providers.
 */
@session_start();
$_SESSION['nonce'] = bin2hex(random_bytes(32));

include 'vendor/autoload.php';
include 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example 06</title>
</head>
<body>
<h1>Sign in</h1>

<ul>
    <?php foreach ($hybridauth->getProviders() as $name) : ?>
        <?php if (!isset($adapters[$name])) : ?>
            <li>
                <a href="<?php echo htmlspecialchars($config['callback'] . "?provider=$name"); ?>&nonce=<?php echo htmlspecialchars($_SESSION['nonce']); ?>">
                    Sign in with <strong><?php echo htmlspecialchars($name); ?></strong>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<?php if ($adapters) : ?>
    <h1>You are logged in:</h1>
    <ul>
        <?php foreach ($adapters as $name => $adapter) : ?>
            <li>
                <strong><?php echo htmlspecialchars($adapter->getUserProfile()->displayName); ?></strong> from
                <i><?php echo htmlspecialchars($name); ?></i>
                <span>(<a href="<?php echo htmlspecialchars($config['callback'] . "?logout=$name"); ?>&nonce=<?php echo htmlspecialchars($_SESSION['nonce']); ?>">Log Out</a>)</span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>
