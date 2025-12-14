<?php
// Script para corregir TODOS los operadores || que fueron reemplazados incorrectamente con ,
$baseDir = __DIR__ . '/../current/src/Controller';
$files = glob($baseDir . '/*.php');

$totalFiles = 0;
$totalReplacements = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    $fileReplacements = 0;
    
    // Patrones específicos para corregir
    $patterns = [
        // Patrón: !$var , is_numeric($var)
        "/!\$([a-zA-Z_][a-zA-Z0-9_]*)\s*,\s*is_numeric\(\$\1\)/",
        
        // Patrón: !is_object($var) , !$var instanceof
        "/!is_object\(\$([a-zA-Z_][a-zA-Z0-9_]*)\)\s*,\s*!\$\1\s+instanceof/",
        
        // Patrón: is_null($var) , is_null($var) (múltiples)
        "/is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)/",
        "/is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)/",
        "/is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)/",
        "/is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)/",
        "/is_null\(\$([^)]+)\)\s*,\s*is_null\(\$([^)]+)\)/",
        
        // Patrón: !$obj->method() , !$obj->method()
        "/!\$([a-zA-Z_][a-zA-Z0-9_]*)->([a-zA-Z_][a-zA-Z0-9_]*\(\))\s*,\s*!\$\1->([a-zA-Z_][a-zA-Z0-9_]*\(\))/",
        
        // Patrón: $var == "" , $var == 0
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*==\s*\"\"\s*,\s*\$\1\s*==\s*0/",
        
        // Patrón: $var != "" , $var != ""
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*!=\s*\"\"\s*,\s*\$\1\s*!=\s*\"\"/",
        
        // Patrón: $parts[1] != '.' , $parts[1] != '..'
        "/\$parts\[1\]\s*!=\s*'\.'\s*,\s*\$parts\[1\]\s*!=\s*'\.\.'/",
        
        // Patrón: $var === 0 , $var === "0"
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*===\s*0\s*,\s*\$\1\s*===\s*\"0\"/",
        
        // Patrón: $var === "0" , $var === "0"
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*===\s*\"0\"\s*,\s*\$\1\s*===\s*\"0\"/",
        
        // Patrón: !$var , count($var) == 0
        "/!\$([a-zA-Z_][a-zA-Z0-9_]*)\s*,\s*count\(\$\1\)\s*==\s*0/",
        
        // Patrón: $var != "" , $var != ""
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*!=\s*\"\"\s*,\s*\$\1\s*!=\s*\"\"/",
        
        // Patrón: $var == "" , $var == 0
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*==\s*\"\"\s*,\s*\$\1\s*==\s*0/",
        
        // Patrón: $var === num1 , $var === num2 , $var === num3
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*===\s*(\d+)\s*,\s*\$\1\s*===\s*(\d+)\s*,\s*\$\1\s*===\s*(\d+)/",
        
        // Patrón: $var === num1 , $var === num2
        "/\$([a-zA-Z_][a-zA-Z0-9_]*)\s*===\s*(\d+)\s*,\s*\$\1\s*===\s*(\d+)/",
    ];
    
    $replacements = [
        "!\$$1 || is_numeric(\$$1)",
        "!is_object(\$$1) || !\$$1 instanceof",
        "is_null(\$$1) || is_null(\$$2) || is_null(\$$3) || is_null(\$$4) || is_null(\$$5) || is_null(\$$6)",
        "is_null(\$$1) || is_null(\$$2) || is_null(\$$3) || is_null(\$$4) || is_null(\$$5)",
        "is_null(\$$1) || is_null(\$$2) || is_null(\$$3) || is_null(\$$4)",
        "is_null(\$$1) || is_null(\$$2) || is_null(\$$3)",
        "is_null(\$$1) || is_null(\$$2)",
        "!\$$1->$2 || !\$$1->$3",
        "\$$1 == \"\" || \$$1 == 0",
        "\$$1 != \"\" || \$$1 != \"\"",
        "\$parts[1] != '.' && \$parts[1] != '..'",
        "\$$1 === 0 || \$$1 === \"0\"",
        "\$$1 === \"0\" || \$$1 === \"0\"",
        "!\$$1 || count(\$$1) == 0",
        "\$$1 != \"\" || \$$1 != \"\"",
        "\$$1 == \"\" || \$$1 == 0",
        "\$$1 === $2 || \$$1 === $3 || \$$1 === $4",
        "\$$1 === $2 || \$$1 === $3",
    ];
    
    // Aplicar reemplazos múltiples veces hasta que no haya más cambios
    $maxIterations = 10;
    $iteration = 0;
    do {
        $changed = false;
        foreach ($patterns as $index => $pattern) {
            $newContent = preg_replace($pattern, $replacements[$index], $content);
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

