<?php
function IsStrongPassword($pass)
{
    if (!preg_match('/\d/', $pass)) {
        return false;
    }
    if (!preg_match('/[a-zA-Z]/', $pass)) {
        return false;
    }
    if (!preg_match('/[^a-zA-Z0-9]/', $pass)) {
        return false;
    }
    if (strlen($pass) < 5) {
        return false;
    }
    return true;
}