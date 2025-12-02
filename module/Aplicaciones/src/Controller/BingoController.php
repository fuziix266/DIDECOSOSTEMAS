<?php

declare(strict_types=1);

namespace Aplicaciones\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Mpdf\Mpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

class BingoController extends AbstractActionController
{

    protected static string $simpleKey = 'bingo2025';

    public function indexAction()
    {
        $this->layout('layout/layout_bingo');
        $this->layout()->setVariable('heroTitle', 'Generador de Cartones de Bingo');
        $this->layout()->setVariable('visible-hero', 0);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $post      = $request->getPost();
            $files     = $request->getFiles();
            $cantidad  = (int) $post['cantidad'];
            $inicio    = (int) $post['inicio'];
            $infoHtml  = $post['informacion'];
            $batchSize = (int) $post['batchSize'];
            $color     = $post['color'];

            $imagenCentroBase64 = null;
            if (!empty($files['imagenCentro']['tmp_name']) && is_uploaded_file($files['imagenCentro']['tmp_name'])) {
                $rutaTemp = $files['imagenCentro']['tmp_name'];
                $mimeType = mime_content_type($rutaTemp);

                // Crear imagen GD desde el archivo original según su tipo
                switch ($mimeType) {
                    case 'image/jpeg':
                        $origen = imagecreatefromjpeg($rutaTemp);
                        break;
                    case 'image/png':
                        $origen = imagecreatefrompng($rutaTemp);
                        break;
                    case 'image/webp':
                        $origen = imagecreatefromwebp($rutaTemp);
                        break;
                    default:
                        $origen = null;
                }

                if ($origen) {
                    // Crear imagen nueva de 120x120
                    $destino = imagecreatetruecolor(75, 80);
                    imagealphablending($destino, false);
                    imagesavealpha($destino, true);

                    // Redimensionar
                    $anchoOrig = imagesx($origen);
                    $altoOrig  = imagesy($origen);
                    imagecopyresampled($destino, $origen, 0, 0, 0, 0, 75, 80, $anchoOrig, $altoOrig);

                    // Guardar temporalmente como PNG en buffer
                    ob_start();
                    imagepng($destino);
                    $contenidoRedimensionado = ob_get_clean();
                    imagedestroy($origen);
                    imagedestroy($destino);

                    $imagenCentroBase64 = 'data:image/png;base64,' . base64_encode($contenidoRedimensionado);
                }
            }


            $pdfOutput = $this->generarPdfBingo($cantidad, $inicio, $infoHtml, $batchSize, $color, $imagenCentroBase64);

            $response = $this->getResponse();
            $response->setContent($pdfOutput);
            $headers = $response->getHeaders();
            $headers->addHeaderLine('Content-Type', 'application/pdf');
            $headers->addHeaderLine('Content-Disposition', 'attachment; filename="bingo.pdf"');
            return $response;
        }

        return new ViewModel();
    }

    // Función simple para codificar datos
    private function codificarDatos(string $data): string
    {
        if (empty($data)) {
            return '';
        }

        $resultado = '';
        $keyLength = strlen(self::$simpleKey);

        for ($i = 0; $i < strlen($data); $i++) {
            $char = $data[$i];
            $keyChar = self::$simpleKey[$i % $keyLength];
            $resultado .= chr(ord($char) ^ ord($keyChar));
        }

        return bin2hex($resultado);
    }

    // Función simple para decodificar datos
    private function decodificarDatos(string $encodedData): string
    {
        if (empty($encodedData)) {
            return '';
        }

        $binaryData = hex2bin($encodedData);
        if ($binaryData === false) {
            return '';
        }

        $resultado = '';
        $keyLength = strlen(self::$simpleKey);

        for ($i = 0; $i < strlen($binaryData); $i++) {
            $char = $binaryData[$i];
            $keyChar = self::$simpleKey[$i % $keyLength];
            $resultado .= chr(ord($char) ^ ord($keyChar));
        }

        return $resultado;
    }


    private function generarPdfBingo(int $cantidad, int $inicio, string $infoHtml, int $batchSize, string $color, ?string $imagenCentroBase64): string
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [139.7, 215.9], // Media carta vertical
            'orientation' => 'P',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);

        $css = <<<CSS
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .page {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .carton {
            width: 100%;
            height: 100%;
            padding: 6mm 6mm 2mm 6mm;
            background-color: inherit;
            border: none;
        }
        .carton-header {
            font-size: 10pt;
            text-align: left;
            margin-bottom: 4mm;
        }
        .bingo-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6mm;
        }
        .bingo-grid thead td {
            font-size: 36pt;
            font-weight: bold;
            text-align: center;
            height: 18mm;
        }
        .bingo-grid tbody td {
            width: 20mm;
            height: 22mm;
            border: 2pt solid #000;
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            background-color: #fff;
        }
        .star-cell {
            font-size: 26pt;
            color: #000;
        }
        .carton-footer {
            width: 100%;
            border-collapse: collapse;
        }
        .info-cell {
            width: 65%;
            height: 30mm;
            background-color: #fff;
            border: 2pt solid #000;
            padding: 4mm;
            font-size: 10pt;
            line-height: 1.2;
            vertical-align: top;
        }
        .qr-cell {
            width: 35%;
            text-align: right;
            font-size: 9.5pt;
            vertical-align: middle;
        }
        .qr-code {
            width: 34mm;
            height: 34mm;
            margin-top: 2mm;
        }

        .gestion {
            font-size: 7pt;
            text-align: center;
            margin-top: 2mm;
        }

        /* Colores por lote */
        .color-0 { background-color: #f8e8d0; }  /* Color base */
        .color-1 { background-color: #d0f0e8; }
        .color-2 { background-color: #f0d0e8; }
        .color-3 { background-color: #e8f0d0; }
        .color-4 { background-color: #d0e0f8; }
        .color-5 { background-color: #f8d8d0; }
        CSS;

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        $html = '';

        for ($i = 0; $i < $cantidad; $i++) {
            $pagina = '<div class="page">';
            $colorIndex = ($batchSize > 0) ? (int)floor($i / $batchSize) % 6 : 0;
            $colorClass = 'color-' . $colorIndex;
            $pagina .= '<div class="carton ' . $colorClass . '">';
            $pagina .= $this->generarCartonHtml($i, $inicio, $infoHtml, $batchSize, $imagenCentroBase64);
            $pagina .= '</div></div>';

            $mpdf->WriteHTML($pagina, \Mpdf\HTMLParserMode::HTML_BODY);
        }
        return $mpdf->Output('', 'S');
    }




    private function generarCartonHtml(int $index, int $inicio, string $infoHtml, int $batchSize, ?string $imagenCentroBase64): string
    {
        $folio = $inicio + $index;
        $numeros = $this->generarNumerosCarton();
        $serie = $this->calcularSerie($numeros);

        // Generar QR Code
        // Generar QR Code
        $qrPayload = json_encode([
            'folio' => $folio,
            'serie' => $serie,
            'numeros' => implode(',', $numeros),
        ]);

        if ($qrPayload === false || $qrPayload === null) {
            $qrPayload = "folio:{$folio},serie:{$serie},numeros:" . implode(',', $numeros);
        }

        $datosCodeificados = $this->codificarDatos($qrPayload);

        $qrCode = QrCode::create("http://www.didecoarica.cl/didecosistemas/public/aplicaciones/bingo/cartondigital?data={$datosCodeificados}")
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrDataUri = $qrResult->getDataUri();

        // Determinar color de fondo
        $colorIndex = ($batchSize > 0) ? (int)floor($index / $batchSize) % 6 : 0;
        $colorClass = 'color-' . $colorIndex;

        $html = '<div class="carton ' . $colorClass . '" style="width: 100%;">';

        // Header con información
        $html .= '<div class="carton-header">';
        $html .= '<strong>Número de cartón:</strong> ' . sprintf('%04d', $folio) . '<br>';
        $html .= '<strong>Serie del cartón :</strong> ' . $serie;
        $html .= '</div>';


        // Grid de números
        $html .= '<table class="bingo-grid">';
        $html .= '<thead><tr>';
        foreach (['B', 'I', 'N', 'G', 'O'] as $letra) {
            $html .= '<td>' . $letra . '</td>';
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        for ($fila = 0; $fila < 5; $fila++) {
            $html .= '<tr>';
            for ($col = 0; $col < 5; $col++) {
                $idx = $fila * 5 + $col;
                $num = $numeros[$idx];
                if ($num === '★') {
                    if ($imagenCentroBase64) {
                        $html .= '<td class="star-cell"><img src="' . $imagenCentroBase64 . '" alt="logo" style="max-width:100%; max-height:100%;" /></td>';
                    } else {
                        $html .= '<td class="star-cell">★</td>';
                    }
                } else {
                    $html .= '<td>' . $num . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // Footer con información y QR
        $html .= '<table class="carton-footer">';
        $html .= '<tr>';
        $html .= '<td class="info-cell">' . (!empty(trim($infoHtml)) ? $infoHtml : '&nbsp;') . '</td>';
        $html .= '<td class="qr-cell">';
        $html .= '<div class="qr-text">Juega desde tu celular</div>';
        $html .= '<img src="' . $qrDataUri . '" class="qr-code" />';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div class="gestion">';
        $html .= '<p>Control y Gestión Digital - DIDECO 2025</p>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    private function generarNumerosCarton(): array
    {
        // Rangos por columna según BINGO estándar de 75 bolas
        $rangos = [
            'B' => range(1, 15),   // Columna 0
            'I' => range(16, 30),  // Columna 1  
            'N' => range(31, 45),  // Columna 2
            'G' => range(46, 60),  // Columna 3
            'O' => range(61, 75),  // Columna 4
        ];

        // Generar 5 números aleatorios para cada columna
        $columnas = [];
        foreach ($rangos as $letra => $rango) {
            shuffle($rango);
            $columnas[$letra] = array_slice($rango, 0, 5);
        }

        // Organizar los números por filas (no por columnas)
        $carton = [];
        for ($fila = 0; $fila < 5; $fila++) {
            for ($col = 0; $col < 5; $col++) {
                $letras = ['B', 'I', 'N', 'G', 'O'];
                $carton[$fila * 5 + $col] = $columnas[$letras[$col]][$fila];
            }
        }

        // Centro libre (posición 12: fila 2, columna 2)
        $carton[2 * 5 + 2] = '★';

        return $carton;
    }

    private function calcularSerie(array $numeros): string
    {
        // Primer B, último O, último B, primer O
        $firstB  = str_pad((string) $numeros[0],  2, '0', STR_PAD_LEFT);
        $lastO   = str_pad((string) $numeros[24], 2, '0', STR_PAD_LEFT);
        $lastB   = str_pad((string) $numeros[20],  2, '0', STR_PAD_LEFT);
        $firstO  = str_pad((string) $numeros[4], 2, '0', STR_PAD_LEFT);

        return 'B' . $firstB . '0' . $lastO . '0' . $lastB . '0' . $firstO;
    }

    public function cartondigitalAction()
    {
        $this->layout('layout/layout_bingo');
        $request = $this->getRequest();
        $datosCodeificados = $request->getQuery('data', '');

        $datos = null;

        if (!empty($datosCodeificados)) {
            $datosDecodificados = $this->decodificarDatos($datosCodeificados);

            if (!empty($datosDecodificados)) {
                $datos = json_decode($datosDecodificados, true);
            }
        }

        return new ViewModel([
            'datos' => $datos,
            'numeros' => isset($datos['numeros']) ? explode(',', $datos['numeros']) : [],
            'folio' => $datos['folio'] ?? '',
            'serie' => $datos['serie'] ?? ''
        ]);
    }
}
