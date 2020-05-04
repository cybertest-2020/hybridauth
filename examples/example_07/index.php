<?php
/**
 * Build a simple HTML page with multiple providers, opening provider authentication in a pop-up.
 */
@session_start();
$_SESSION['nonce'] = bin2hex(random_bytes(32));

require 'path/to/vendor/autoload.php';
require 'config.php';

use Hybridauth\Hybridauth;

$hybridauth = new Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example 07</title>

    <script>
        function auth_popup( provider ){
            // replace 'path/to/hybridauth' with the real path to this script
            var authWindow = window.open('https://path/to/hybridauth/examples/example_07/callback.php?provider='+provider+'&nonce=<?php echo htmlspecialchars($_SESSION['nonce']); ?>', 'authWindow', 'width=600,height=400,scrollbars=yes');
            return false;
        }
    </script>
    
</head>
<body>
    <h1>Sign in</h1>

    <ul>

<?php foreach ($hybridauth->getProviders() as $name) : ?>
    <?php if (!isset($adapters[$name])) : ?>
        <li>
            <a href="#" onclick="javascript:auth_popup('<?php echo htmlspecialchars($name) ?>');">
                Sign in with <?php echo htmlspecialchars($name) ?>
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
