<?php
global $root;
  function rootDirectory()
  {
    // Change the second parameter to suit your needs
    return dirname(__FILE__, 2);
  }

$root = rootDirectory();