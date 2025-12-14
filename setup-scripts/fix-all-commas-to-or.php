<?php
// Script para corregir TODAS las comas que separan condiciones lógicas y reemplazarlas con ||
// Busca patrones como: condición1 , condición2 y los reemplaza con condición1 || condición2

$baseDir = __DIR__ . '/../current/src/Controller';
$files = glob($baseDir . '/*.php');

$totalFiles = 0;
$totalReplacements = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileReplacements = 0;
    
    // Buscar patrones donde hay una coma entre condiciones lógicas en if statements
    // Patrón: condición , condición dentro de if (...)
    // Esto es más agresivo pero debería capturar todos los casos
    
    // Buscar líneas que contengan "if (" seguido de algo, luego una coma, luego algo más
    // y que termine con ")" en la misma línea o siguiente
    
    // Patrón más específico: buscar comas rodeadas de espacios que están dentro de condiciones if
    // Reemplazar: " , " con " || " cuando está dentro de un if
    
    // Enfoque: buscar todas las ocurrencias de " , " que están precedidas por caracteres que sugieren una condición
    // y seguidas por caracteres que sugieren otra condición
    
    // Patrones específicos conocidos:
    $patterns = [
        // $var == valor , condición
        '/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*==\s*([^,)]+)\s*,\s*([^,)]+)/' => '\$$1 == $2 || $3',
        
        // $var === valor , condición  
        '/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*===\s*([^,)]+)\s*,\s*([^,)]+)/' => '\$$1 === $2 || $3',
        
        // !$var , condición
        '/!\$([a-zA-Z_][a-zA-Z0-9_]*)\s*,\s*([^,)]+)/' => '!\$$1 || $2',
        
        // is_null($var) , condición
        '/is_null\(\$([^)]+)\)\s*,\s*([^,)]+)/' => 'is_null(\$$1) || $2',
        
        // !is_object($var) , condición
        '/!is_object\(\$([^)]+)\)\s*,\s*([^,)]+)/' => '!is_object(\$$1) || $2',
        
        // $var != valor , condición
        '/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*!=\s*([^,)]+)\s*,\s*([^,)]+)/' => '\$$1 != $2 || $3',
        
        // str_contains($var, 'texto') , str_contains($var, 'texto')
        '/str_contains\(\$([^,)]+),\s*([^)]+)\)\s*,\s*str_contains\(\$([^,)]+),\s*([^)]+)\)/' => 'str_contains(\$$1, $2) || str_contains(\$$3, $4)',
        
        // str_contains(strtolower($var['key']), 'texto') , str_contains(strtolower($var['key']), 'texto')
        '/str_contains\(strtolower\(\$([^)]+)\)\s*,\s*([^)]+)\)\s*,\s*str_contains\(strtolower\(\$([^)]+)\)\s*,\s*([^)]+)\)/' => 'str_contains(strtolower(\$$1), $2) || str_contains(strtolower(\$$3), $4)',
    ];
    
    // Aplicar reemplazos múltiples veces
    $maxIterations = 5;
    $iteration = 0;
    do {
        $changed = false;
        foreach ($patterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $changed = true;
                $fileReplacements++;
                $content = $newContent;
            }
        }
        $iteration++;
    } while ($changed && $iteration < $maxIterations);
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        $totalFiles++;
        $totalReplacements += $fileReplacements;
        echo "Fixed: " . basename($file) . " ($fileReplacements replacements)\n";
    }
}

echo "\nTotal files fixed: $totalFiles\n";
echo "Total replacements: $totalReplacements\n";

