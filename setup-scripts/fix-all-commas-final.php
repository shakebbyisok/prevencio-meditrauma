<?php
// Script AGRESIVO para corregir TODAS las comas que separan condiciones lógicas
// Busca " , " dentro de if statements y las reemplaza con " || "

$baseDir = __DIR__ . '/../current/src/Controller';
$files = glob($baseDir . '/*.php');

$totalFiles = 0;
$totalReplacements = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileReplacements = 0;
    
    // Buscar líneas que contengan "if (" y luego buscar comas dentro de esas líneas
    // Reemplazar " , " con " || " cuando está dentro de un contexto de condición
    
    // Enfoque: buscar todas las ocurrencias de " , " que están precedidas y seguidas por caracteres válidos de condición
    // Patrón: cualquier cosa seguida de espacio-coma-espacio seguida de cualquier cosa que parezca una condición
    
    // Buscar patrones específicos conocidos primero
    $specificPatterns = [
        // is_null(...) , is_null(...)
        '/is_null\(([^)]+)\)\s*,\s*is_null\(([^)]+)\)/' => 'is_null($1) || is_null($2)',
        
        // !$var , condición
        "/!\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*,\s*([^,)]+)/" => '!\$$1 || $2',
        
        // $var == valor , condición
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*==\s*([^,)]+)\s*,\s*([^,)]+)/" => '\$$1 == $2 || $3',
        
        // $var === valor , condición
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*===\s*([^,)]+)\s*,\s*([^,)]+)/" => '\$$1 === $2 || $3',
        
        // $var != valor , condición
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*!=\s*([^,)]+)\s*,\s*([^,)]+)/" => '\$$1 != $2 || $3',
        
        // str_contains(...) , str_contains(...)
        '/str_contains\(([^)]+)\)\s*,\s*str_contains\(([^)]+)\)/' => 'str_contains($1) || str_contains($2)',
        
        // !is_object(...) , condición
        '/!is_object\(([^)]+)\)\s*,\s*([^,)]+)/' => '!is_object($1) || $2',
        
        // $var == "" , $var == 0
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*==\s*\"\"\s*,\s*\\$\1\s*==\s*0/" => '\$$1 == "" || \$$1 == 0',
        
        // $var === num , $var === num , $var === num
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*===\s*(\d+)\s*,\s*\\$\1\s*===\s*(\d+)\s*,\s*\\$\1\s*===\s*(\d+)/" => '\$$1 === $2 || \$$1 === $3 || \$$1 === $4',
        
        // $var === num , $var === num
        "/\$([a-zA-Z_][a-zA-Z0-9_\[\]'\"]*)\s*===\s*(\d+)\s*,\s*\\$\1\s*===\s*(\d+)/" => '\$$1 === $2 || \$$1 === $3',
        
        // $parts[1] != '.' , $parts[1] != '..'
        '/\$parts\[1\]\s*!=\s*\'\.\'\s*,\s*\$parts\[1\]\s*!=\s*\'\.\.\'/' => '\$parts[1] != \'.\' && \$parts[1] != \'..\'',
        
        // !$obj->method() , !$obj->method()
        '/!\$([a-zA-Z_][a-zA-Z0-9_]*)->([a-zA-Z_][a-zA-Z0-9_]*\(\))\s*,\s*!\$\1->([a-zA-Z_][a-zA-Z0-9_]*\(\))/' => '!\$$1->$2 || !\$$1->$3',
        
        // !$var , is_numeric($var)
        '/!\$([a-zA-Z_][a-zA-Z0-9_]*)\s*,\s*is_numeric\(\$\1\)/' => '!\$$1 || is_numeric(\$$1)',
        
        // !$var , count($var) == 0
        '/!\$([a-zA-Z_][a-zA-Z0-9_]*)\s*,\s*count\(\$\1\)\s*==\s*0/' => '!\$$1 || count(\$$1) == 0',
    ];
    
    // Aplicar reemplazos múltiples veces hasta que no haya más cambios
    $maxIterations = 10;
    $iteration = 0;
    do {
        $changed = false;
        foreach ($specificPatterns as $pattern => $replacement) {
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

