<?php

/*
 * Hilfsmethoden
 */
function firstCharToUpper($className) {
  return strtoupper(substr($className, 0, 1)) . substr($className, 1);
}

?>
