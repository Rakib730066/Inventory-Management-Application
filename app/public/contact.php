<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name !== '' && $email !== '' && $message !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        ?>
        <h2>Thank you, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <p>Your message has been received:</p>
        <blockquote><?php echo nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')); ?></blockquote>
        <p>We'll contact you at <strong><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></strong> soon.</p>
        <p><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">Back to form</a></p>
        <?php
    } else {
        ?>
        <p style="color:red;">Please fill in all fields with valid information.</p>
        <p><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">Back to form</a></p>
        <?php
    }
} else {
    ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" cols="30" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
    <?php
}