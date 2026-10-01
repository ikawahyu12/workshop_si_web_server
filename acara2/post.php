
    <h1>Form Login</h1>

    <form action="post.php" method="POST">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username">

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">

        <br><br>

        <button type="submit">Login</button>

    </form>

    <?php
    $username = $_POST['username'] ?? '';

    if ($username !== '') {
        echo "<p>Username yang dikirim: " . htmlspecialchars($username) . "</p>";
    }
    ?>
