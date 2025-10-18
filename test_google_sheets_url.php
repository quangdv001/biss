#!/usr/bin/env php
<?php

/**
 * Test script to verify Google Sheets URL parsing
 * Run: php test_google_sheets_url.php
 */

$testUrl = "https://docs.google.com/spreadsheets/d/1-2LO-6XKqAiwysFnsp-Y_B_qqfr8rZkSExn5wdBA3DY/edit?gid=965454359#gid=965454359";

echo "Testing Google Sheets URL Parser\n";
echo "=================================\n\n";

echo "Input URL:\n$testUrl\n\n";

// Extract spreadsheet ID
preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $testUrl, $matches);
if (isset($matches[1])) {
    $spreadsheetId = $matches[1];
    echo "✓ Spreadsheet ID: $spreadsheetId\n";
} else {
    echo "✗ Failed to extract Spreadsheet ID\n";
    exit(1);
}

// Extract gid
preg_match('/gid=([0-9]+)/', $testUrl, $gidMatches);
$gid = isset($gidMatches[1]) ? $gidMatches[1] : '0';
echo "✓ Sheet GID: $gid\n";

// Generate export URL
$exportUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=xlsx&gid={$gid}";
echo "\n✓ Export URL:\n$exportUrl\n\n";

echo "Test completed successfully!\n";
echo "\nYou can test downloading by visiting the export URL in your browser.\n";
echo "The file should download automatically if the sheet is publicly shared.\n";
