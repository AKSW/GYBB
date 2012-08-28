<?php

/*
 * Hilfsmethoden
 */
function firstCharToUpper($className) {
  return strtoupper(substr($className, 0, 1)) . substr($className, 1);
}

function firstCharToLower($className) {
  return strtolower(substr($className, 0, 1)) . substr($className, 1);
}


?>
