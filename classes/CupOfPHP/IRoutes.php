<?php

namespace CupOfPHP;

interface IRoutes
{
    public function getRoutes(): array;
    public function getLayoutVars($title, $output): array;
    public function getAuthentication(): \CupOfPHP\Authentication;
    public function checkPermission($permission): bool;
}
