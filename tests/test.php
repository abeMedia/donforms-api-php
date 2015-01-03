<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use donForms;

// Enter your API Key to test functions
$donforms = new donForms\donForms('YOUR_API_KEY_HERE');

echo "<pre>";

echo "Get all users";
echo "\n\n";
print_r($donforms->user->getList());

echo "\n\n";
echo "Get current user";
echo "\n\n";
print_r($donforms->user->get('brightonjapan'));

echo "\n\n";
echo "Get current user's forms";
echo "\n\n";
print_r($donforms->form->getList('brightonjapan'));

echo "\n\n";
echo "Get single form";
echo "\n\n";
print_r($donforms->form->get('Dq'));

echo "\n\n";
echo "Get form submissions";
echo "\n\n";
print_r($donforms->data->getList('Dq'));

echo "\n\n";
echo "Get single form submission";
echo "\n\n";
print_r($donforms->data->get('Dq'));

echo "</pre>";