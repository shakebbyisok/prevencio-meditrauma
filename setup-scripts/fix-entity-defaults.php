<?php
// Script para corregir valores por defecto en entidades Doctrine
// Cambia default:"true" a default:1 y default:"false" a default:0

$entityDir = __DIR__ . '/../current/src/Entity';
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($entityDir),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$fixed = 0;
$errors = 0;

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;
        
        // Reemplazar default:"true" con default:1
        $content = preg_replace(
            '/options=\{\"default\":\"true\"\}/',
            'options={"default":1}',
            $content
        );
        
        // Reemplazar default:"false" con default:0
        $content = preg_replace(
            '/options=\{\"default\":\"false\"\}/',
            'options={"default":0}',
            $content
        );
        
        // Reemplazar VARCHAR(20000) con TEXT
        $content = preg_replace(
            '/@ORM\\\\Column\(type="string", length=20000/',
            '@ORM\Column(type="text"',
            $content
        );
        
        // Reemplazar VARCHAR(2000) con TEXT (para evitar problemas de tamaño de fila)
        $content = preg_replace(
            '/@ORM\\\\Column\(type="string", length=2000/',
            '@ORM\Column(type="text"',
            $content
        );
        
        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            $fixed++;
            echo "Fixed: " . basename($file->getPathname()) . "\n";
        }
    }
}

echo "\n";
echo "Archivos corregidos: $fixed\n";
if ($errors > 0) {
    echo "Errores: $errors\n";
}

