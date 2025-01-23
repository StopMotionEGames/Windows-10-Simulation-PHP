<?php
$pr_path = "/Windows_10";
session_start();
session_destroy();
header("Location: $pr_path");
exit;