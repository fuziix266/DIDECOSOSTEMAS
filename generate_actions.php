<?php
$slugsFile = __DIR__ . '/slugs.txt';
$viewDir = __DIR__ . '/module/Departamentos/view/departamentos/discapacidad';
$viewPartial = 'departamentos/discapacidad/_tramite_detalle';

if (!file_exists($slugsFile)) die("No slugs.txt");

$slugsRaw = file($slugsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$generatedMethods = "";

function cleanActionName($slug)
{
    return str_replace('-', '', $slug);
}

foreach ($slugsRaw as $line) {
    if (strpos($line, 'SLUG:') !== 0) continue;

    // Parse line: SLUG:abc-def|NOMBRE:Abc Def
    list($slugPart, $nombrePart) = explode('|', $line, 2);
    $slug = substr($slugPart, 5); // remove SLUG:

    // 1. Generate Action Method
    $actionName = cleanActionName($slug);

    // If it's "solicitudterapiaocupacional", we check if it already exists in the file manually or just append.
    // I'll just generate the block for all of them.

    $method = "    public function {$actionName}Action()\n";
    $method .= "    {\n";
    $method .= "        return \$this->getTramiteView('{$slug}');\n";
    $method .= "    }\n\n";

    $generatedMethods .= $method;

    // 2. Create View File
    $viewFileName = $actionName . '.phtml';
    $viewFilePath = $viewDir . '/' . $viewFileName;

    $viewContent = "<?= \$this->render('{$viewPartial}', ['tramite' => \$tramite]) ?>";

    if (!file_exists($viewFilePath)) {
        file_put_contents($viewFilePath, $viewContent);
        echo "Created view: $viewFileName\n";
    } else {
        echo "View already exists: $viewFileName\n";
    }
}

file_put_contents('methods_generated.txt', $generatedMethods);
echo "Methods generated in methods_generated.txt\n";
