<?php

class DashboardLib {
    public function printLoginForm() {
        echo "<form method='POST'>
        <label for='username'>Username:</label>
        <input type='text' name='username' id='username' required>
        <br>
        <label for='password'>Password:</label>
        <input type='password' name='password' id='password' required>
        <br>
        <input type='submit'>
        </form>";
    }
    
    public function logoutUser() {
        session_destroy();
    }

    public function printLogoutForm() {
        echo "<h3>login: </h3><form method='POST'>
        <input type='submit' name='logout'>
        </form>";
    }
}

?>