<?php

Interface ILogger {
  public static function logText(string|array ...$info): void;
}