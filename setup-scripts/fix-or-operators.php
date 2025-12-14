<?php
// Script para corregir los operadores || que fueron reemplazados incorrectamente con ,
$baseDir = __DIR__ . '/../current/src/Controller';
$files = glob($baseDir . '/*.php');

$replacements = [
    // Corregir operadores OR lógicos en PHP que fueron reemplazados con comas
    "/is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)/" => "is_null($1) || is_null($2)",
    "/\$parts\[1\]\s*!=\s*'\.'\s*,\s*\$parts\[1\]/" => "\$parts[1] != '.' && \$parts[1]",
];

$totalFiles = 0;
$totalReplacements = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileReplacements = 0;
    
    // Aplicar múltiples pasadas para corregir todas las ocurrencias
    do {
        $changed = false;
        foreach ($replacements as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $changed = true;
                $fileReplacements++;
                $content = $newContent;
            }
        }
    } while ($changed);
    
    // También buscar y reemplazar patrones más complejos de is_null con múltiples comas
    $pattern = "/is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)/";
    $replacement = "is_null($1) || is_null($2) || is_null($3) || is_null($4) || is_null($5) || is_null($6)";
    $newContent = preg_replace($pattern, $replacement, $content);
    if ($newContent !== $content) {
        $fileReplacements++;
        $content = $newContent;
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        $totalFiles++;
        $totalReplacements += $fileReplacements;
        echo "Fixed: " . basename($file) . " ($fileReplacements replacements)\n";
    }
}

echo "\nTotal files fixed: $totalFiles\n";
echo "Total replacements: $totalReplacements\n";

